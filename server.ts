import express from 'express';
import http from 'http';
import { spawn } from 'child_process';
import path from 'path';
import fs from 'fs';

const app = express();
const PORT = 3000;
const PHP_PORT = 8080;

// Trust reverse proxy (Cloud Run SSL termination)
app.set('trust proxy', true);

// Enable CORS and handle preflight requests immediately
app.use((req, res, next) => {
  const origin = req.headers.origin;
  if (origin) {
    res.setHeader('Access-Control-Allow-Origin', origin);
    res.setHeader('Access-Control-Allow-Credentials', 'true');
  } else {
    res.setHeader('Access-Control-Allow-Origin', '*');
  }
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, HEAD');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, X-CSRF-Token, X-Session-ID, Origin');
  res.setHeader('Access-Control-Expose-Headers', 'X-Session-ID, Set-Cookie');

  if (req.method === 'OPTIONS') {
    return res.status(204).end();
  }
  next();
});

// Path to PHP application public directory
const pandeAppPublicDir = path.resolve(process.cwd(), 'pande-app/public_html');
const routerScript = path.resolve(pandeAppPublicDir, 'index.php');

console.log('[Server] Starting PHP built-in server on 127.0.0.1:' + PHP_PORT);
console.log('[Server] Document root:', pandeAppPublicDir);

// Spawn PHP built-in server with router script
const phpProcess = spawn('php', [
  '-S', `127.0.0.1:${PHP_PORT}`,
  '-t', pandeAppPublicDir,
  routerScript
], {
  cwd: path.resolve(process.cwd(), 'pande-app'),
  env: {
    ...process.env,
    APP_ENV: 'development',
    APP_DEBUG: '1',
    DB_CONNECTION: 'sqlite',
    APP_URL: '',
  }
});

phpProcess.stdout.on('data', (data) => {
  console.log(`[PHP] ${data.toString().trim()}`);
});

phpProcess.stderr.on('data', (data) => {
  console.error(`[PHP Log] ${data.toString().trim()}`);
});

phpProcess.on('error', (err) => {
  console.error('[PHP Process Error]', err);
});

phpProcess.on('close', (code) => {
  console.log(`[PHP Process exited with code ${code}]`);
});

// Download endpoint for cPanel ZIP archive
app.get('/download-cpanel-zip', (req, res) => {
  const zipPath = path.resolve(process.cwd(), 'pande-computers-cpanel.zip');
  if (fs.existsSync(zipPath)) {
    res.download(zipPath, 'pande-computers-cpanel.zip');
  } else {
    res.status(404).send('ZIP file not found');
  }
});

// Reverse Proxy all requests to PHP built-in web server
app.use((req, res) => {
  const forwardedProto = (req.headers['x-forwarded-proto'] as string) || req.protocol || 'http';
  const options: http.RequestOptions = {
    hostname: '127.0.0.1',
    port: PHP_PORT,
    path: req.url,
    method: req.method,
    headers: {
      ...req.headers,
      host: `127.0.0.1:${PHP_PORT}`,
      'x-forwarded-for': req.ip,
      'x-forwarded-proto': forwardedProto,
      'x-forwarded-host': (req.headers.host as string) || '',
    },
  };

  const proxyReq = http.request(options, (proxyRes) => {
    const headers = { ...proxyRes.headers };
    if (req.headers.origin) {
      headers['access-control-allow-origin'] = req.headers.origin as string;
      headers['access-control-allow-credentials'] = 'true';
    } else {
      headers['access-control-allow-origin'] = '*';
    }
    headers['access-control-allow-methods'] = 'GET, POST, PUT, DELETE, OPTIONS, HEAD';
    headers['access-control-allow-headers'] = 'Content-Type, Authorization, X-Requested-With, Accept, X-CSRF-Token, X-Session-ID, Origin';
    headers['access-control-expose-headers'] = 'X-Session-ID, Set-Cookie';

    // Rewrite Set-Cookie to ensure SameSite=None, Secure, and Partitioned (CHIPS) in iframes
    const rawSetCookie = proxyRes.headers['set-cookie'];
    if (rawSetCookie) {
      const cookiesList = Array.isArray(rawSetCookie) ? rawSetCookie : [rawSetCookie];
      headers['set-cookie'] = cookiesList.map((c) => {
        let cookie = c;
        if (/SameSite=(Lax|Strict)/i.test(cookie)) {
          cookie = cookie.replace(/SameSite=(Lax|Strict)/i, 'SameSite=None');
        } else if (!/SameSite=/i.test(cookie)) {
          cookie += '; SameSite=None';
        }
        if (!/Secure/i.test(cookie)) {
          cookie += '; Secure';
        }
        if (!/Partitioned/i.test(cookie)) {
          cookie += '; Partitioned';
        }
        return cookie;
      });
    }

    res.writeHead(proxyRes.statusCode || 200, headers);
    proxyRes.pipe(res, { end: true });
  });

  proxyReq.on('error', (err) => {
    console.error('[Proxy Error]', err.message);
    if (!res.headersSent) {
      res.status(502).send(`
        <html>
          <body style="font-family: sans-serif; text-align: center; padding: 50px;">
            <h2>502 Gateway Error</h2>
            <p>Waiting for PHP backend service to initialize...</p>
            <script>setTimeout(() => location.reload(), 1500);</script>
          </body>
        </html>
      `);
    }
  });

  req.pipe(proxyReq, { end: true });
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`[Pande Computers] App listening on http://0.0.0.0:${PORT}`);
});
