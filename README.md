# PANDE COMPUTERS - E-COMMERCE & RETAIL SYSTEM

**"YOUR TRUSTED TECH PARTNER"**  
*UNCOMPROMISED POWER. UNBEATABLE PRICES.*  
Location: Ndanda & Aggrey Street, Kariakoo, Dar es Salaam, Tanzania  
Contact & WhatsApp: +255 787 311 342  
Currency: Tanzanian Shilling (TZS)  

---

## Overview
A complete, production-ready, database-driven PHP/MySQL e-commerce application designed specifically for **Pande Computers**, an established electronics and technology retailer in Kariakoo, Dar es Salaam.

Built with pure **PHP 8.2+ PDO**, **MySQL 8.0+**, **HTML5/Bootstrap 5**, clean architecture, role-based access control, CSRF protection, transactional inventory management, and an official **WhatsApp direct checkout workflow**.

---

## Core Capabilities

### 1. Storefront & Customer Experience
- **Responsive Electronics Storefront:** White background with navy accents and bold typography.
- **20 Suggested Product Departments:**
  1. Laptops and Notebooks
  2. Desktop Computers and All-in-One PCs
  3. Computer Monitors and Displays
  4. Computer Accessories and Peripherals
  5. Storage Devices, Flash Drives, Memory Cards and SSDs
  6. RAM, Processors, Motherboards and Computer Components
  7. Printers and Multifunction Printers
  8. Printer Toners, Ink Cartridges and Ink Bottles
  9. Projectors and Scanners
  10. Networking Equipment, Routers and Switches
  11. Mobile Phones and Smartphones
  12. Mobile Accessories and Phone Protection
  13. Chargers, Power Banks, Cables and Adapters
  14. Earphones, Headphones, Speakers and Audio
  15. Smart TVs and Television Accessories
  16. Gaming Consoles, Controllers and Gaming Accessories
  17. Electrical Components, Cables, Switches and Sockets
  18. Power Protection, UPS Systems and Surge Protectors
  19. Office Equipment and Supplies
  20. Other Electronics and Technology Products
- **Search & Filtering:** Search by keyword, category, price range, brand, availability, and sorting.
- **Product Details:** High-res gallery, full specs table, warranty terms, stock indicators, and related items.
- **Shopping Cart:** Quantity controls, line totals, free delivery threshold calculation in TZS.

### 2. Mandatory WhatsApp Checkout Workflow
- Complies strictly with Section 8 of the project specifications:
  - Generates unique order identifier (e.g. `PAN-2026-000001`).
  - Atomic database transactions reserve inventory immediately.
  - Retains immutable product snapshot (name, SKU, unit price, quantity).
  - Encodes click-to-chat URL: `https://wa.me/255787311342?text=...`.
  - Confirmation view allows reopening WhatsApp chat at any time.
  - Payment tracking is strictly separated from order creation; manual admin verification ensures security.

### 3. Administration & Business Management
- **Dashboard Metrics:** Real database summaries (Total orders, pending, awaiting payment, completed, verified sales in TZS, active items, low stock alerts).
- **Product Management:** Full CRUD with SKU, specs, image uploads, price comparison, cost prices.
- **Category Management:** Create, edit, and safely delete categories with product-association protection.
- **Inventory Control:** Manual stock adjustments with reasons and automated movement audit logging.
- **Order Processing:** Filter by status/date/payment, invoice printing view, status transitions, manual payment recording.
- **Reports & Analytics:** 14-day sales trends, top sellers, inventory valuation, CSV order exports.
- **Store Settings:** Configurable WhatsApp line, phone, address, and delivery policies.
- **User Management:** Create authorized staff and admin accounts.

---

## Automated Acceptance Testing
Run the automated test suite directly via CLI:
```bash
php pande-app/tests/run_tests.php
```
All 20 test suites pass with 100% success rate:
- Database connection & drivers
- Seed category loading
- Product search & filtering
- Customer registration & auth
- RBAC role enforcement (preventing customer admin access)
- Server-side cart total calculations
- Atomic stock reservations & transactions
- WhatsApp link URL encoding
- Idempotency & duplicate submission protection
- Status history & manual payment verification
- Stock release upon order cancellation
- CSRF validation & input sanitization
