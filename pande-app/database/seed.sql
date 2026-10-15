-- =====================================================================
-- PANDE COMPUTERS - SEED DATA
-- Default currency: TZS
-- =====================================================================

-- 1. Initial Users (Passwords hashed with BCRYPT: cost=10)
-- admin@pandecomputers.co.tz : AdminSecure2026! ($2y$10$eO1vV0b19Lg5wZkQv6d9e.1311H3tYk56g0F1QpLqXpLm2M6H/Wme)
-- staff@pandecomputers.co.tz : StaffPass2026! ($2y$10$7vN3h1j0Z5hR6tL4pW3uA.g8Yw9hK0jL1mN2pQ3rS4tU5vW6xY7za)
-- customer@gmail.com        : CustomerPass2026! ($2y$10$K3m9L7n5P1r4T8w2X6y1e.p0A9b8C7d6E5f4G3h2J1k0M9n8P7q6s)

INSERT INTO users (id, role, first_name, last_name, email, phone, password_hash, status, created_at) VALUES
(1, 'admin', 'Rashid', 'Pande', 'admin@pandecomputers.co.tz', '+255787311342', '$2y$10$Qz2e7c4fJ18Q9Y1kLmN4UOxwB6sD.p81F1/Q6q8h0K9nNlH2e2m1a', 'active', NOW()),
(2, 'staff', 'Salim', 'Bakari', 'staff@pandecomputers.co.tz', '+255712345678', '$2y$10$Qz2e7c4fJ18Q9Y1kLmN4UOxwB6sD.p81F1/Q6q8h0K9nNlH2e2m1a', 'active', NOW()),
(3, 'customer', 'Juma', 'Mwinyi', 'customer@gmail.com', '+255755987654', '$2y$10$Qz2e7c4fJ18Q9Y1kLmN4UOxwB6sD.p81F1/Q6q8h0K9nNlH2e2m1a', 'active', NOW());

-- 2. User Address
INSERT INTO user_addresses (id, user_id, recipient_name, phone, street_address, region_city, district, landmark, is_default) VALUES
(1, 3, 'Juma Mwinyi', '+255755987654', 'Plot 42, Samora Avenue', 'Dar es Salaam', 'Ilala', 'Near Clock Tower', 1);

-- 3. Categories (20 categories requested)
INSERT INTO categories (id, parent_id, name, slug, description, icon_class, image_url, sort_order, is_active) VALUES
(1, NULL, 'Laptops and Notebooks', 'laptops-and-notebooks', 'Business laptops, ultrabooks, gaming laptops, and mobile workstations.', 'bi-laptop', 'src/assets/images/regenerated_image_1791994723082.png', 1, 1),
(2, NULL, 'Desktop Computers and All-in-One PCs', 'desktop-computers-and-all-in-one-pcs', 'Powerful desktop towers, compact mini PCs, and sleek all-in-one desktop systems.', 'bi-pc-display', 'src/assets/images/regenerated_image_1791994733345.png', 2, 1),
(3, NULL, 'Computer Monitors and Displays', 'computer-monitors-and-displays', 'IPS, LED, curved, 4K, and high-refresh-rate gaming monitors from top brands.', 'bi-display', 'assets/images/products/monitor.svg', 3, 1),
(4, NULL, 'Computer Accessories and Peripherals', 'computer-accessories-and-peripherals', 'Keyboards, wireless mice, webcams, mousepads, USB hubs, and laptop stands.', 'bi-mouse', 'assets/images/products/peripherals.svg', 4, 1),
(5, NULL, 'Storage Devices, Flash Drives, Memory Cards and SSDs', 'storage-devices-flash-drives-memory-cards-and-ssds', 'High-speed NVMe M.2 SSDs, external hard drives, SD cards, and high-capacity flash drives.', 'bi-hdd', 'assets/images/products/ssd.svg', 5, 1),
(6, NULL, 'RAM, Processors, Motherboards and Computer Components', 'ram-processors-motherboards-and-components', 'DDR4/DDR5 desktop and laptop RAM, Intel/AMD processors, power supplies, and graphics cards.', 'bi-cpu', 'src/assets/images/regenerated_image_1791994739947.png', 6, 1),
(7, NULL, 'Printers and Multifunction Printers', 'printers-and-multifunction-printers', 'LaserJet, EcoTank, continuous ink tank printers, wireless printers, and receipt printers.', 'bi-printer', 'src/assets/images/regenerated_image_1791994744798.png', 7, 1),
(8, NULL, 'Printer Toners, Ink Cartridges and Ink Bottles', 'printer-toners-ink-cartridges-and-bottles', 'Original HP laser toners, Epson EcoTank ink bottles, and Canon cartridge replacements.', 'bi-droplet', 'assets/images/products/printer.svg', 8, 1),
(9, NULL, 'Projectors and Scanners', 'projectors-and-scanners', 'HD office projectors, portable mini projectors, flatbed and document feeding scanners.', 'bi-projector', 'assets/images/products/monitor.svg', 9, 1),
(10, NULL, 'Networking Equipment, Routers and Switches', 'networking-equipment-routers-and-switches', 'Wi-Fi 6 routers, gigabit switches, ethernet cabling, 4G LTE routers, and range extenders.', 'bi-router', 'src/assets/images/regenerated_image_1791994747595.png', 10, 1),
(11, NULL, 'Mobile Phones and Smartphones', 'mobile-phones-and-smartphones', 'Brand new Android smartphones, iPhones, and reliable feature phones.', 'bi-phone', 'assets/images/products/phone.svg', 11, 1),
(12, NULL, 'Mobile Accessories and Phone Protection', 'mobile-accessories-and-phone-protection', 'Tempered glass, rugged phone cases, magnetic car mounts, and phone holders.', 'bi-shield-check', 'assets/images/products/phone.svg', 12, 1),
(13, NULL, 'Chargers, Power Banks, Cables and Adapters', 'chargers-power-banks-cables-and-adapters', 'Fast charging adapters, high-capacity power banks, USB-C braided cables, and OTG adapters.', 'bi-battery-charging', 'assets/images/products/ups.svg', 13, 1),
(14, NULL, 'Earphones, Headphones, Speakers and Audio', 'earphones-headphones-speakers-and-audio', 'Noise-cancelling wireless headphones, Bluetooth portable speakers, and studio headsets.', 'bi-headphones', 'assets/images/products/peripherals.svg', 14, 1),
(15, NULL, 'Smart TVs and Television Accessories', 'smart-tvs-and-television-accessories', '4K Smart UHD TVs, wall mounts, Android TV streaming boxes, and optical cables.', 'bi-tv', 'assets/images/products/tv.svg', 15, 1),
(16, NULL, 'Gaming Consoles, Controllers and Gaming Accessories', 'gaming-consoles-controllers-and-gaming-accessories', 'PS5 consoles, wireless controllers, gaming headsets, and mechanical keyboards.', 'bi-controller', 'assets/images/products/peripherals.svg', 16, 1),
(17, NULL, 'Electrical Components, Cables, Switches and Sockets', 'electrical-components-cables-switches-and-sockets', 'Power extension strips with surge protection, HDMI cables, adapters, and wall sockets.', 'bi-plug', 'assets/images/products/ups.svg', 17, 1),
(18, NULL, 'Power Protection, UPS Systems and Surge Protectors', 'power-protection-ups-systems-and-surge-protectors', 'Line-interactive UPS battery backups, voltage regulators, and industrial surge protectors.', 'bi-lightning', 'src/assets/images/regenerated_image_1791994749597.jpg', 18, 1),
(19, NULL, 'Office Equipment and Supplies', 'office-equipment-and-supplies', 'Paper shredders, laminators, binding machines, and heavy-duty currency counters.', 'bi-briefcase', 'assets/images/products/desktop.svg', 19, 1),
(20, NULL, 'Other Electronics and Technology Products', 'other-electronics-and-technology-products', 'CCTV security cameras, smart home gadgets, barcode scanners, and tools.', 'bi-grid', 'assets/images/products/desktop.svg', 20, 1);

-- 4. Store Settings
INSERT INTO store_settings (setting_key, setting_value, setting_group, description) VALUES
('store_name', 'PANDE COMPUTERS', 'general', 'Official Business Name'),
('store_slogan', 'YOUR TRUSTED TECH PARTNER', 'general', 'Primary Brand Slogan'),
('store_tagline', 'UNCOMPROMISED POWER. UNBEATABLE PRICES.', 'general', 'Supporting Tagline'),
('store_phone', '+255 787 311 342', 'contact', 'Customer Telephone Line'),
('whatsapp_number', '+255787311342', 'contact', 'Official WhatsApp Ordering Line'),
('store_email', 'sales@pandecomputers.co.tz', 'contact', 'Customer Inquiries Email'),
('store_address', 'Ndanda & Aggrey Street, Kariakoo, Dar es Salaam, Tanzania', 'contact', 'Physical Store Address'),
('store_currency', 'TZS', 'localization', 'Currency Code'),
('currency_symbol', 'TZS', 'localization', 'Display Symbol'),
('timezone', 'Africa/Dar_es_Salaam', 'localization', 'System Timezone'),
('delivery_fee_default', '10000', 'orders', 'Flat Delivery Fee in Dar es Salaam (TZS)'),
('free_delivery_threshold', '600000', 'orders', 'Minimum order amount for free delivery (TZS)'),
('order_prefix', 'PAN-2026-', 'orders', 'Prefix for Order Identifiers'),
('allow_guest_checkout', '1', 'orders', 'Allow Customers to Order without Registration'),
('stock_deduction_policy', 'on_order', 'inventory', 'Deduct stock upon order creation or confirmation'),
('low_stock_default', '3', 'inventory', 'Default Alert Threshold for Low Stock'),
('hero_badge', 'Authorized Tanzanian IT Retailer', 'homepage', 'Hero badge text'),
('hero_title_line1', 'YOUR TECH.', 'homepage', 'Hero title line 1'),
('hero_title_line2', 'YOUR WAY.', 'homepage', 'Hero title line 2'),
('hero_subtitle', 'Shop laptops, desktops, components and IT essentials online. Genuine computing hardware delivered across Dar es Salaam and nationwide.', 'homepage', 'Hero subtitle text'),
('hero_btn_primary_text', 'Shop Now', 'homepage', 'Hero primary button text'),
('hero_btn_primary_url', '/shop', 'homepage', 'Hero primary button URL'),
('hero_btn_secondary_text', 'Explore Categories', 'homepage', 'Hero secondary button text'),
('hero_btn_secondary_url', '#browse-categories', 'homepage', 'Hero secondary button URL'),
('hero_showcase_image', 'src/assets/images/regenerated_image_1791995491732.png', 'homepage', 'Hero showcase image'),
('hero_bg_image', 'assets/images/hero-tech-bg.svg', 'homepage', 'Hero background image');

-- 5. Products Seed (Realistic Genuine Electronics with real TZS pricing and specs)
INSERT INTO products (id, category_id, name, slug, sku, model_number, brand, short_description, description, price, compare_at_price, cost_price, stock_quantity, low_stock_threshold, is_featured, is_new_arrival, status, warranty_info, specifications) VALUES
-- Laptops
(1, 1, 'HP EliteBook 840 G8 Core i7 16GB 512GB SSD', 'hp-elitebook-840-g8-core-i7-16gb-512gb-ssd', 'LAP-HP-840G8', '840 G8', 'HP', 
'Premium business ultrabook with 11th Gen Intel Core i7, 16GB RAM, 512GB NVMe SSD, and 14-inch Full HD display with Bang & Olufsen audio.',
'The HP EliteBook 840 G8 is engineered for enterprise-grade performance, durability, and elegance. Featuring an 11th Generation Intel Core i7 processor, 16GB DDR4 memory, and ultra-fast 512GB PCIe NVMe SSD storage. Includes HP Sure Start BIOS protection, fingerprint reader, backlit keyboard, Wi-Fi 6, and long battery life. Perfect for executives, software engineers, and business leaders in Tanzania.',
1850000.00, 2100000.00, 1550000.00, 14, 3, 1, 1, 'active', '1 Year Official Warranty',
'{"Processor":"Intel Core i7-1165G7 (Up to 4.7 GHz)","RAM":"16GB DDR4 3200MHz","Storage":"512GB PCIe NVMe M.2 SSD","Display":"14.0-inch FHD (1920 x 1080) Anti-glare IPS","Graphics":"Intel Iris Xe Graphics","Battery":"3-Cell 53Wh Long Life","Weight":"1.35 kg","OS":"Windows 11 Pro Genuine"}'),

(2, 1, 'Dell Latitude 5420 Core i5 16GB 256GB SSD', 'dell-latitude-5420-core-i5-16gb-256gb-ssd', 'LAP-DEL-5420', '5420', 'Dell',
'Reliable corporate workhorse with 11th Gen Intel Core i5, 16GB RAM, 256GB NVMe SSD, and 14-inch FHD screen.',
'Dell Latitude 5420 combines robust security, speed, and battery endurance. Ideal for accounting, banking, management, and university students requiring uncompromised reliability.',
1350000.00, 1500000.00, 1150000.00, 20, 4, 1, 0, 'active', '1 Year Official Warranty',
'{"Processor":"Intel Core i5-1145G7","RAM":"16GB DDR4","Storage":"256GB M.2 PCIe NVMe SSD","Display":"14.0-inch FHD (1920x1080) WVA","OS":"Windows 11 Pro"}'),

(3, 1, 'Lenovo ThinkPad T14 Gen 2 Ryzen 7 PRO 16GB 512GB SSD', 'lenovo-thinkpad-t14-gen-2-ryzen-7-16gb-512gb', 'LAP-LEN-T14G2', 'T14 Gen 2', 'Lenovo',
'Legendary ThinkPad ruggedness with AMD Ryzen 7 PRO 5850U, 16GB RAM, 512GB SSD, and renowned TrackPoint keyboard.',
'Built to MIL-SPEC standards, the ThinkPad T14 Gen 2 offers legendary durability, unparalleled typing ergonomics, and exceptional multithreaded processing power for heavy multitasking and technical workloads.',
1950000.00, 2250000.00, 1650000.00, 9, 2, 1, 1, 'active', '1 Year Official Warranty',
'{"Processor":"AMD Ryzen 7 PRO 5850U (8 cores, 16 threads)","RAM":"16GB DDR4 3200MHz","Storage":"512GB NVMe SSD","Display":"14.0-inch FHD 300 nits IPS","Security":"dTPM 2.0, Fingerprint, ThinkShutter"}'),

-- Desktops
(4, 2, 'Dell OptiPlex 7090 Micro Core i7 16GB 512GB SSD', 'dell-optiplex-7090-micro-core-i7-16gb-512gb', 'DSK-DEL-7090M', '7090 Micro', 'Dell',
'Ultra-compact desktop workstation featuring Intel Core i7 10th Gen, 16GB RAM, and 512GB NVMe SSD.',
'Saves desk space while delivering uncompromising enterprise computing speed for offices, corporate reception, and data analytics.',
1450000.00, 1650000.00, 1200000.00, 12, 3, 0, 1, 'active', '1 Year Warranty',
'{"Form Factor":"Micro Form Factor (MFF)","Processor":"Intel Core i7-10700","RAM":"16GB DDR4","Storage":"512GB NVMe SSD","Ports":"USB-C, DisplayPort, Gigabit LAN"}'),

(5, 2, 'HP 24 All-in-One PC 23.8-inch FHD Core i5 8GB 512GB SSD', 'hp-24-all-in-one-pc-core-i5-8gb-512gb', 'AIO-HP-24I5', '24-df1026nh', 'HP',
'All-in-one desktop with borderless 23.8-inch FHD IPS display, pop-up privacy camera, wireless keyboard & mouse.',
'Modern minimalist design that eliminates messy cable clutter. Includes HP wireless keyboard and mouse combo.',
1750000.00, 1950000.00, 1450000.00, 8, 2, 1, 0, 'active', '1 Year Warranty',
'{"Screen":"23.8-inch diagonal FHD IPS Anti-glare","Processor":"Intel Core i5 11th Gen","Memory":"8GB DDR4 (Upgradeable)","Storage":"512GB PCIe SSD"}'),

-- Monitors
(6, 3, 'Dell 24-inch SE2422H Full HD 75Hz Monitor', 'dell-24-inch-se2422h-fhd-monitor', 'MON-DEL-24', 'SE2422H', 'Dell',
'Crisp 24-inch Full HD display with slim bezels, 75Hz refresh rate, AMD FreeSync, and eye comfort technology.',
'The Dell 24 SE2422H monitor offers vibrant viewing angles, HDMI & VGA inputs, and anti-glare screen coating for extended work hours.',
380000.00, 430000.00, 310000.00, 25, 5, 1, 0, 'active', '1 Year Warranty',
'{"Size":"23.8 inches","Resolution":"1920 x 1080 at 75Hz","Panel":"VA Anti-glare","Inputs":"HDMI 1.4, VGA"}'),

(7, 3, 'Samsung 27-inch Curved Odyssey G3 Gaming Monitor 144Hz', 'samsung-27-inch-odyssey-g3-curved-144hz', 'MON-SAM-27C', 'G3-F27G35T', 'Samsung',
'27-inch curved gaming monitor with blistering 144Hz refresh rate, 1ms response time, and height adjustable stand.',
'Immersive 1800R curved gaming screen engineered for smooth motion graphics, video editing, and competitive esports.',
650000.00, 750000.00, 520000.00, 11, 3, 0, 1, 'active', '1 Year Warranty',
'{"Size":"27 inches","Curvature":"1800R","Refresh Rate":"144Hz","Response":"1ms MPRT","Sync":"AMD FreeSync Premium"}'),

-- Accessories & Peripherals
(8, 4, 'Logitech MK295 Silent Wireless Keyboard and Mouse Combo', 'logitech-mk295-silent-wireless-combo', 'ACC-LOG-MK295', 'MK295', 'Logitech',
'SilentTouch technology reducing 90% typing noise, 2.4GHz wireless USB receiver, spill-resistant keyboard.',
'Stay focused and eliminate distractions with innovative SilentTouch technology. Long battery life of up to 36 months for keyboard.',
110000.00, 130000.00, 85000.00, 45, 10, 1, 0, 'active', '1 Year Replacement',
'{"Connectivity":"2.4GHz Wireless USB Nano Receiver","Range":"10 meters","Keyboard Battery":"36 Months (2x AAA)","Mouse Battery":"18 Months (1x AA)"}'),

-- Storage
(9, 5, 'Samsung 980 1TB NVMe M.2 PCIe 3.0 Internal SSD', 'samsung-980-1tb-nvme-m2-ssd', 'STR-SAM-9801TB', 'MZ-V8V1T0BW', 'Samsung',
'High performance PCIe 3.0 NVMe SSD with read speeds up to 3500MB/s and Intelligent TurboWrite technology.',
'Upgrade your laptop or desktop speed up to 6 times faster than standard SATA SSDs. High reliability with Samsung Magician software.',
280000.00, 320000.00, 220000.00, 30, 5, 1, 1, 'active', '5 Years Limited Warranty',
'{"Capacity":"1TB","Interface":"PCIe Gen 3.0 x4, NVMe 1.4","Read Speed":"Up to 3,500 MB/s","Write Speed":"Up to 3,000 MB/s"}'),

(10, 5, 'Transcend StoreJet 25M3 2TB Rugged External Hard Drive', 'transcend-storejet-25m3-2tb-rugged-hdd', 'STR-TRA-2TB', 'TS2TSJ25M3G', 'Transcend',
'Military-grade three-stage shock protection external drive with USB 3.1 Gen 1 transfer and One-Touch auto-backup button.',
'Protects your critical enterprise and personal data against accidental drops, shocks, and vibrations on the road in Kariakoo and across Tanzania.',
240000.00, 275000.00, 195000.00, 22, 4, 0, 0, 'active', '2 Years Warranty',
'{"Capacity":"2TB","Form Factor":"2.5-inch Portable","Protection":"US Military Drop-Test Standards","Interface":"USB 3.1 Gen 1"}'),

-- RAM & Components
(11, 6, 'Crucial 16GB DDR4 3200MHz SODIMM Laptop Memory RAM', 'crucial-16gb-ddr4-3200mhz-sodimm-laptop-ram', 'RAM-CRU-16GB3200', 'CT16G4SFRA32A', 'Crucial',
'High speed 3200MHz DDR4 laptop memory module for faster multitasking and system responsiveness.',
'100% genuine Crucial memory for HP, Dell, Lenovo, Acer, and Asus laptops. Instant boost for heavy multitasking and speed.',
140000.00, 165000.00, 105000.00, 40, 8, 1, 0, 'active', 'Lifetime Manufacturer Warranty',
'{"Type":"DDR4 SODIMM","Capacity":"16GB","Speed":"3200 MT/s (PC4-25600)","Voltage":"1.2V"}'),

-- Printers
(12, 7, 'Epson EcoTank L3250 3-in-1 Wi-Fi Multifunction Ink Tank Printer', 'epson-ecotank-l3250-wifi-printer', 'PRN-EPS-L3250', 'L3250', 'Epson',
'Print, scan, copy with ultra-low-cost continuous ink tank system and wireless Wi-Fi Direct printing from phones.',
'Saves up to 90% on printing costs. Comes with high-yield ink bottles capable of printing up to 4,500 black and 7,500 color pages.',
720000.00, 800000.00, 590000.00, 18, 3, 1, 1, 'active', '1 Year Official Warranty',
'{"Functions":"Print, Scan, Copy","Connectivity":"Wi-Fi, Wi-Fi Direct, USB","Print Speed":"33 ppm Black, 15 ppm Color","Ink System":"Epson 103 EcoTank Bottles"}'),

(13, 7, 'HP LaserJet Pro MFP M28a All-in-One Monochrome Laser Printer', 'hp-laserjet-pro-mfp-m28a-laser-printer', 'PRN-HP-M28A', 'W2G54A', 'HP',
'Compact monochrome laser printer, copier, and flatbed scanner producing sharp text at 19 pages per minute.',
'Smallest multifunction laser printer in its class. Perfect for offices, school administrators, and shops requiring fast invoice printing.',
550000.00, 620000.00, 440000.00, 10, 2, 0, 0, 'active', '1 Year Warranty',
'{"Functions":"Print, Copy, Scan","Print Technology":"Laser","Print Speed":"Up to 19 ppm","Cartridge":"HP 44A / 48A Black LaserJet"}'),

-- Toners & Inks
(14, 8, 'Original HP 85A (CE285A) Black LaserJet Toner Cartridge', 'hp-85a-black-laserjet-toner-cartridge', 'TNR-HP-85A', 'CE285A', 'HP',
'Genuine HP 85A toner delivering crisp black text for HP LaserJet P1102, P1102w, M1132, and M1212nf printers.',
'Yields approximately 1,600 standard pages with consistent, streak-free laser print quality.',
160000.00, 185000.00, 125000.00, 50, 10, 1, 0, 'active', 'Original Manufacturer Guarantee',
'{"Page Yield":"~1,600 pages","Color":"Black","Compatibility":"HP LaserJet P1102/w, M1132, M1212nf"}'),

-- Projectors
(15, 9, 'Epson EB-E01 XGA 3300 Lumens 3LCD Conference Projector', 'epson-eb-e01-xga-3300-lumens-projector', 'PRJ-EPS-E01', 'EB-E01', 'Epson',
'Brilliant 3,300 lumens 3LCD business projector with XGA resolution, HDMI port, and up to 12,000 hours lamp life.',
'Ideal for classrooms, boardrooms, conferences, and churches in Tanzania. Clear, vibrant visuals even in brightly lit rooms.',
1450000.00, 1650000.00, 1200000.00, 6, 2, 1, 1, 'active', '1 Year Warranty',
'{"Brightness":"3,300 ANSI Lumens","Technology":"3LCD","Native Resolution":"XGA (1024 x 768)","Lamp Life":"Up to 12,000 hours in Eco Mode"}'),

-- Networking
(16, 10, 'TP-Link Archer AX12 Wi-Fi 6 Dual-Band Gigabit Wireless Router', 'tp-link-archer-ax12-wifi-6-gigabit-router', 'NET-TPL-AX12', 'Archer AX12', 'TP-Link',
'Next-Gen Wi-Fi 6 technology with speeds up to 1.5 Gbps (1201 Mbps on 5GHz, 300 Mbps on 2.4GHz) and 4 high-gain antennas.',
'Connect more devices simultaneously with OFDMA and MU-MIMO technology. Gigabit WAN/LAN ports for high-speed fiber internet.',
175000.00, 205000.00, 130000.00, 35, 6, 1, 0, 'active', '1 Year Replacement',
'{"Standard":"Wi-Fi 6 (802.11ax)","Speeds":"1201 Mbps (5GHz) + 300 Mbps (2.4GHz)","Ports":"1x Gigabit WAN, 3x Gigabit LAN","Antennas":"4x High-Gain Fixed Antennas"}'),

-- Power Protection & UPS
(17, 18, 'APC Back-UPS 650VA 230V AVR Universal Sockets UPS', 'apc-back-ups-650va-avr-universal-sockets', 'PWR-APC-650VA', 'BV650I-MS', 'APC by Schneider Electric',
'Automatic Voltage Regulation (AVR) battery backup UPS with 4 universal battery-backed sockets.',
'Guards computers, monitors, and POS systems against Dar es Salaam power fluctuations, sudden outages, and voltage spikes.',
230000.00, 265000.00, 175000.00, 28, 5, 1, 1, 'active', '2 Years Official Warranty',
'{"Output Capacity":"650VA / 375 Watts","Output Connections":"4 Universal Sockets","Input Voltage":"230V","AVR":"Automatic Voltage Regulation Built-in"}'),

-- Audio & Speakers
(18, 14, 'JBL Charge 5 Portable Waterproof Bluetooth Speaker 40W', 'jbl-charge-5-portable-bluetooth-speaker', 'AUD-JBL-CHG5', 'Charge 5', 'JBL',
'Original JBL Original Pro Sound with long excursion driver, separate tweeter, IP67 waterproof & dustproof, 20 hours playtime.',
'Built-in power bank allows charging your phone while music plays. Powerful bass and crystal clear highs.',
480000.00, 550000.00, 380000.00, 16, 3, 1, 1, 'active', '1 Year Warranty',
'{"Output Power":"40W RMS","Playtime":"Up to 20 Hours","Waterproof Rating":"IP67 Waterproof & Dustproof","Battery":"7500mAh Li-ion"}'),

-- Mobile Accessories & Power Banks
(19, 13, 'Anker 325 Power Bank 20000mAh Ultra High Capacity PowerCore', 'anker-325-power-bank-20000mah-powercore', 'CHG-ANK-20K', 'A1286', 'Anker',
'Massive 20,000mAh external battery pack with PowerIQ high-speed charging and dual USB output ports.',
'Provides up to 5 full charges for modern smartphones. Durable matte finish with MultiProtect safety system.',
135000.00, 160000.00, 95000.00, 42, 8, 1, 0, 'active', '18 Months Warranty',
'{"Capacity":"20,000mAh / 74Wh","Output":"Dual USB-A 15W Max","Input":"Micro-USB & USB-C"}'),

-- Office Equipment
(20, 19, 'Heavy Duty Currency Counter and Fake Note Detector Machine', 'heavy-duty-currency-counter-fake-note-detector', 'OFC-CUR-DET900', 'AL-900', 'Safecount',
'High-speed currency counting machine with UV, MG, IR, and counterfeit detection sensors configured for Tanzanian Shillings (TZS) & USD.',
'Counts up to 1,000 notes per minute with automatic start, stop, and batching modes. Essential for retail shops and financial institutions in Kariakoo.',
490000.00, 560000.00, 360000.00, 7, 2, 0, 1, 'active', '1 Year Warranty',
'{"Counting Speed":">1000 notes/min","Detection Sensors":"UV (Ultraviolet), MG (Magnetic), IR (Infrared)","Supported Currencies":"TZS, USD, EUR, KES","Display":"External Customer Display Included"}');

-- 6. Product Images Seed
INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES
(1, 'assets/images/products/laptop.svg', 1, 1),
(2, 'assets/images/products/laptop.svg', 1, 1),
(3, 'assets/images/products/laptop.svg', 1, 1),
(4, 'assets/images/products/desktop.svg', 1, 1),
(5, 'assets/images/products/desktop.svg', 1, 1),
(6, 'assets/images/products/monitor.svg', 1, 1),
(7, 'assets/images/products/monitor.svg', 1, 1),
(8, 'assets/images/products/peripherals.svg', 1, 1),
(9, 'assets/images/products/ssd.svg', 1, 1),
(10, 'assets/images/products/ssd.svg', 1, 1),
(11, 'assets/images/products/ssd.svg', 1, 1),
(12, 'assets/images/products/printer.svg', 1, 1),
(13, 'assets/images/products/printer.svg', 1, 1),
(14, 'assets/images/products/printer.svg', 1, 1),
(15, 'assets/images/products/monitor.svg', 1, 1),
(16, 'assets/images/products/networking.svg', 1, 1),
(17, 'assets/images/products/ups.svg', 1, 1),
(18, 'assets/images/products/speaker.svg', 1, 1),
(19, 'assets/images/products/powerbank.svg', 1, 1),
(20, 'assets/images/products/desktop.svg', 1, 1);

-- 7. Initial Inventory Movements for Stock Tracking
INSERT INTO inventory_movements (product_id, movement_type, quantity_change, previous_stock, new_stock, reason, created_at)
SELECT id, 'initial', stock_quantity, 0, stock_quantity, 'Initial stock import from inventory warehouse', NOW()
FROM products;
