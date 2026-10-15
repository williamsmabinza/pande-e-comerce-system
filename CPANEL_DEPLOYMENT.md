# PANDE COMPUTERS — CPANEL PRODUCTION DEPLOYMENT GUIDE

**Brand:** PANDE COMPUTERS  
**Slogan:** YOUR TRUSTED TECH PARTNER  
**Tagline:** UNCOMPROMISED POWER. UNBEATABLE PRICES.  
**Location:** Ndanda & Aggrey Street, Kariakoo, Dar es Salaam, Tanzania  
**WhatsApp / Telephone:** +255 787 311 342  
**Target Hosting:** Shared cPanel Hosting (PHP 8.2+, MySQL 8.0+ / MariaDB)

---

## 1. Prerequisites Checklist
- A standard cPanel hosting account.
- PHP version set to **PHP 8.2** (or 8.1+) in cPanel **MultiPHP Manager**.
- Required PHP extensions enabled in **Select PHP Version** / **Extensions**:
  - `pdo`
  - `pdo_mysql`
  - `mbstring`
  - `openssl`
  - `json`
  - `curl`
  - `fileinfo` (for secure image upload validation)
  - `gd` or `imagick`

---

## 2. Step-by-Step cPanel Deployment Instructions

### Step 1: Create the MySQL Database & User in cPanel
1. Log in to your cPanel dashboard.
2. Under the **Databases** section, click **MySQL® Database Wizard**.
3. **Step 1:** Enter a database name (e.g., `pande_ecommerce` -> full name will be `yourcpaneluser_pande`). Click **Next Step**.
4. **Step 2:** Create a database user (e.g., `pande_admin`) and generate a strong password. Click **Create User**.
5. **Step 3:** Check **ALL PRIVILEGES** to grant the user full access to the database. Click **Make Changes**.
6. Note down the Database Name, Username, and Password.

### Step 2: Import the Database Schema & Seed Data
1. Return to the cPanel main dashboard and open **phpMyAdmin**.
2. Select your newly created database in the left sidebar.
3. Click the **Import** tab at the top.
4. Click **Choose File** and select `database/schema.sql`. Click **Import**.
5. Once complete, click **Import** again and choose `database/seed.sql` to import the 20 official tech categories, starter products, and default store configurations.

### Step 3: Upload the Application Archive
1. In cPanel, open **File Manager**.
2. Recommended structure for maximum security:
   - Extract the `pande-computers-cpanel.zip` into your user home directory (outside public_html):
     ```
     /home/yourusername/pande-app/
     ├── app/
     ├── config/
     ├── database/
     ├── storage/
     └── .env
     ```
   - Copy or move the contents of `public_html/` directly into your cPanel `/public_html/` root.
   - Edit `/public_html/index.php` to ensure paths point correctly to `/home/yourusername/pande-app/app/Config/app.php`.
   - *Alternatively (Standard shared hosting option):* Upload the entire zip directly into `public_html` and keep `.htaccess` protecting the `app/` and `.env` files.

### Step 4: Configure Environment & Database Credentials
1. In cPanel File Manager, create or edit the file `.env` in the root application directory:
   ```env
   APP_NAME="PANDE COMPUTERS"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL="https://yourdomain.co.tz"

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=yourcpaneluser_pande
   DB_USERNAME=yourcpaneluser_pande_admin
   DB_PASSWORD=YourSecurePasswordHere2026!
   ```
2. Save the file.

### Step 5: Configure Permissions
1. Ensure the `storage/` directory and its subdirectories (`storage/logs/`, `storage/backups/`, `storage/sessions/`) have `0755` write permissions so logs and image uploads can be saved.
2. Ensure public upload directories have `0755` write permissions:
   - `public_html/assets/uploads/hero/` (for custom hero photography & banners)
   - `public_html/assets/uploads/categories/` (for department visual media)
   - `public_html/assets/uploads/products/` (for product hardware catalogue photos)

### Step 6: Enable HTTPS / SSL
1. In cPanel, navigate to **SSL/TLS Status** or **Let's Encrypt SSL**.
2. Click **Run AutoSSL** on your domain to generate a free SSL certificate.
3. Your `.htaccess` file is pre-configured with secure HTTP headers.

---

## 3. Initial Administrative Access

Default initial administrative account created by `database/seed.sql`:

- **Admin Login URL:** `https://yourdomain.co.tz/login` or `https://yourdomain.co.tz/admin`
- **Administrator Email:** `admin@pandecomputers.co.tz`
- **Temporary Password:** `AdminSecure2026!`
- **Staff Email:** `staff@pandecomputers.co.tz`
- **Staff Password:** `StaffPass2026!`

> **Security Requirement:** Immediately log in to the Admin Dashboard upon installation, navigate to **Staff & Users**, and change the default administrative password to your own unique credentials.

---

## 4. WhatsApp Checkout Configuration
1. Go to **Admin Dashboard &rarr; Store Settings**.
2. Verify the **WhatsApp Number**: `+255 787 311 342` (stored internationally as `+255787311342`).
3. You can update this number at any time without modifying any source code.
4. When a customer orders, the application:
   - Validates stock atomically in MySQL.
   - Saves a persistent historical order record.
   - Deducts the inventory count.
   - Formats a URL-encoded message containing order ID, products, and prices in TZS.
   - Redirects the customer to WhatsApp click-to-chat.
   - An administrator verifies the mobile money payment manually before marking the order as confirmed.

---

## 5. Routine Backup & Maintenance
- **Database Backup:** Go to cPanel **Backup** or **phpMyAdmin &rarr; Export** and save a gzip copy of your MySQL database weekly.
- **File Backup:** In cPanel File Manager, compress `pande-app/` into a `.tar.gz` or `.zip` and download to local storage.
