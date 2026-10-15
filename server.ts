import express from 'express';
import http from 'http';
import { spawn, execSync, ChildProcess } from 'child_process';
import path from 'path';
import fs from 'fs';

const app = express();
const PORT = 3000;
const PHP_PORT = 8088;

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

function ensurePhpInstalled() {
  try {
    execSync('which php', { stdio: 'ignore' });
    console.log('[Server] PHP is available.');
  } catch {
    console.log('[Server] PHP not found in environment. Automatically installing PHP runtime...');
    try {
      execSync('DEBIAN_FRONTEND=noninteractive apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" php-cli php-sqlite3 php-mbstring php-curl php-xml', { stdio: 'inherit' });
      console.log('[Server] PHP runtime installed successfully.');
    } catch (e) {
      console.error('[Server] Failed to auto-install PHP:', e);
    }
  }
}

let phpProcess: ChildProcess | null = null;
let isShuttingDown = false;

function startPhpServer() {
  if (isShuttingDown) return;
  ensurePhpInstalled();

  console.log('[Server] Starting PHP built-in server on 127.0.0.1:' + PHP_PORT);
  console.log('[Server] Document root:', pandeAppPublicDir);

  phpProcess = spawn('php', [
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

  phpProcess.stdout?.on('data', (data: Buffer) => {
    console.log(`[PHP] ${data.toString().trim()}`);
  });

  phpProcess.stderr?.on('data', (data: Buffer) => {
    console.error(`[PHP Log] ${data.toString().trim()}`);
  });

  phpProcess.on('error', (err: Error) => {
    console.error('[PHP Process Error]', err);
    if (!isShuttingDown) {
      setTimeout(startPhpServer, 2000);
    }
  });

  phpProcess.on('close', (code: number | null) => {
    console.log(`[PHP Process exited with code ${code}]`);
    if (!isShuttingDown) {
      setTimeout(startPhpServer, 1000);
    }
  });
}

// Start PHP server
startPhpServer();

process.on('SIGINT', () => {
  isShuttingDown = true;
  if (phpProcess) phpProcess.kill();
  process.exit(0);
});

process.on('SIGTERM', () => {
  isShuttingDown = true;
  if (phpProcess) phpProcess.kill();
  process.exit(0);
});

// Serve static assets from src/ (for regenerated media and developer assets)
app.use('/src', express.static(path.resolve(process.cwd(), 'src')));

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

    // Rewrite Location header to keep redirects relative
    if (headers['location'] && typeof headers['location'] === 'string') {
      headers['location'] = headers['location']
        .replace(/^https?:\/\/127\.0\.0\.1:\d+/i, '')
        .replace(/^https?:\/\/[^\/]+/i, '');
      if (!headers['location'].startsWith('/')) {
        headers['location'] = '/' + headers['location'];
      }
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
