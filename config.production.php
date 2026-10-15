<?php
/**
 * Pande Computers - cPanel Production Database Configuration Example
 * 
 * INSTRUCTIONS FOR CPANEL:
 * 1. Rename this file to '.env' or copy these parameters into your environment.
 * 2. Update with the MySQL credentials created in cPanel MySQL Database Wizard.
 */

return [
    'driver' => 'mysql',
    'mysql' => [
        'host' => 'localhost',              // On cPanel, usually 'localhost' or '127.0.0.1'
        'port' => 3306,
        'database' => 'cpaneluser_pande',   // e.g. username_database
        'username' => 'cpaneluser_dbadmin', // e.g. username_dbuser
        'password' => 'YourStrongDbPassword2026!',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
];
