import React, { useState, useMemo } from 'react';
import {
  Search,
  ShoppingCart,
  User,
  Menu,
  X,
  ShieldCheck,
  CheckCircle2,
  Phone,
  Mail,
  MapPin,
  Star,
  ArrowRight,
  Eye,
  Plus,
  Minus,
  Trash2,
  Laptop,
  Monitor,
  Cpu,
  Printer,
  Wifi,
  Zap,
  Check,
} from 'lucide-react';

import catLaptopImg from './assets/images/regenerated_image_1791994723082.png';
import catDesktopImg from './assets/images/regenerated_image_1791994733345.png';
import catPartsImg from './assets/images/regenerated_image_1791994739947.png';
import catPrinterImg from './assets/images/regenerated_image_1791994744798.png';
import catNetworkingImg from './assets/images/regenerated_image_1791994747595.png';
import catPowerImg from './assets/images/regenerated_image_1791994749597.jpg';
import heroShowcaseImg from './assets/images/regenerated_image_1791995491732.png';

// Product Interface
interface Product {
  id: number;
  name: string;
  slug: string;
  brand: string;
  category: string;
  price: number;
  compareAtPrice: number | null;
  rating: number;
  reviewsCount: number;
  image: string;
  inStock: boolean;
  stockQty: number;
  badge?: string;
}

// Category Interface
interface CategoryItem {
  id: string;
  name: string;
  slug: string;
  itemCount: string;
  image: string;
  tag: string;
  icon: React.ComponentType<{ className?: string }>;
}

// Sample authentic Pande Computers catalog matching database records
const CATEGORIES: CategoryItem[] = [
  {
    id: 'laptops',
    name: 'Laptops & Notebooks',
    slug: 'laptops-and-notebooks',
    itemCount: '24+ Models',
    image: catLaptopImg,
    tag: 'Core i5/i7 & Ryzen',
    icon: Laptop,
  },
  {
    id: 'desktops',
    name: 'Desktops & All-in-Ones',
    slug: 'desktop-computers-and-all-in-one-pcs',
    itemCount: '18+ Systems',
    image: catDesktopImg,
    tag: 'Towers & Micro PCs',
    icon: Monitor,
  },
  {
    id: 'parts',
    name: 'Computer Parts & Upgrades',
    slug: 'computer-components',
    itemCount: '50+ Components',
    image: catPartsImg,
    tag: 'NVMe SSDs & RAM',
    icon: Cpu,
  },
  {
    id: 'printers',
    name: 'Printers & Toners',
    slug: 'printers-and-toners',
    itemCount: '15+ Models',
    image: catPrinterImg,
    tag: 'EcoTank & LaserJets',
    icon: Printer,
  },
  {
    id: 'networking',
    name: 'Networking & WiFi',
    slug: 'networking-and-accessories',
    itemCount: '30+ Devices',
    image: catNetworkingImg,
    tag: 'Gigabit & WiFi 6',
    icon: Wifi,
  },
  {
    id: 'power',
    name: 'Power & UPS Backup',
    slug: 'power-and-ups',
    itemCount: '12+ Units',
    image: catPowerImg,
    tag: 'AVR Surge & Inverters',
    icon: Zap,
  },
];

const PRODUCTS: Product[] = [
  {
    id: 1,
    name: 'HP EliteBook 840 G8 Core i7 16GB 512GB SSD',
    slug: 'hp-elitebook-840-g8-core-i7-16gb-512gb-ssd',
    brand: 'HP',
    category: 'Laptops',
    price: 1850000,
    compareAtPrice: 2100000,
    rating: 5.0,
    reviewsCount: 18,
    image: '/assets/images/products/laptop.svg',
    inStock: true,
    stockQty: 12,
    badge: 'Save 12%',
  },
  {
    id: 2,
    name: 'Dell Latitude 5420 Core i5 16GB 256GB SSD',
    slug: 'dell-latitude-5420-core-i5-16gb-256gb-ssd',
    brand: 'Dell',
    category: 'Laptops',
    price: 1350000,
    compareAtPrice: 1500000,
    rating: 4.9,
    reviewsCount: 22,
    image: '/assets/images/products/laptop.svg',
    inStock: true,
    stockQty: 8,
    badge: 'Popular',
  },
  {
    id: 3,
    name: 'Lenovo ThinkPad T14 Gen 2 Ryzen 7 PRO 16GB 512GB SSD',
    slug: 'lenovo-thinkpad-t14-gen-2-ryzen-7-16gb-512gb',
    brand: 'Lenovo',
    category: 'Laptops',
    price: 1950000,
    compareAtPrice: 2250000,
    rating: 5.0,
    reviewsCount: 14,
    image: '/assets/images/products/laptop.svg',
    inStock: true,
    stockQty: 5,
    badge: 'Pro Choice',
  },
  {
    id: 4,
    name: 'Dell OptiPlex 7090 Micro Core i7 16GB 512GB SSD',
    slug: 'dell-optiplex-7090-micro-core-i7-16gb-512gb',
    brand: 'Dell',
    category: 'Desktops',
    price: 1450000,
    compareAtPrice: 1650000,
    rating: 4.8,
    reviewsCount: 9,
    image: '/assets/images/products/desktop.svg',
    inStock: true,
    stockQty: 14,
  },
  {
    id: 12,
    name: 'Epson EcoTank L3250 3-in-1 Wi-Fi Multifunction Ink Tank Printer',
    slug: 'epson-ecotank-l3250-wifi-printer',
    brand: 'Epson',
    category: 'Printers',
    price: 720000,
    compareAtPrice: 800000,
    rating: 4.9,
    reviewsCount: 31,
    image: '/assets/images/products/printer.svg',
    inStock: true,
    stockQty: 18,
    badge: 'Best Seller',
  },
  {
    id: 16,
    name: 'TP-Link Archer AX12 Wi-Fi 6 Dual-Band Gigabit Wireless Router',
    slug: 'tp-link-archer-ax12-wifi-6-gigabit-router',
    brand: 'TP-Link',
    category: 'Networking',
    price: 175000,
    compareAtPrice: 205000,
    rating: 4.9,
    reviewsCount: 27,
    image: '/assets/images/products/networking.svg',
    inStock: true,
    stockQty: 35,
  },
  {
    id: 17,
    name: 'APC Back-UPS 650VA 230V AVR Universal Sockets UPS',
    slug: 'apc-back-ups-650va-avr-universal-sockets',
    brand: 'APC',
    category: 'Power & UPS',
    price: 230000,
    compareAtPrice: 265000,
    rating: 4.8,
    reviewsCount: 16,
    image: '/assets/images/products/ups.svg',
    inStock: true,
    stockQty: 28,
  },
  {
    id: 9,
    name: 'Samsung 980 1TB NVMe M.2 PCIe 3.0 Internal Solid State Drive',
    slug: 'samsung-980-1tb-nvme-m2-ssd',
    brand: 'Samsung',
    category: 'Computer Parts',
    price: 280000,
    compareAtPrice: 320000,
    rating: 5.0,
    reviewsCount: 42,
    image: '/assets/images/products/ssd.svg',
    inStock: true,
    stockQty: 30,
    badge: 'High Speed',
  },
];

const TRUST_PILLARS = [
  {
    title: 'Genuine Products',
    desc: '100% authentic hardware with manufacturer warranty',
    icon: ShieldCheck,
  },
  {
    title: 'Warranty Support',
    desc: 'Direct warranty backing and local repair assistance',
    icon: CheckCircle2,
  },
  {
    title: 'Secure Payments',
    desc: 'Cash on delivery, bank transfer & mobile money',
    icon: Zap,
  },
  {
    title: 'Local IT Support',
    desc: 'Expert Kariakoo team ready to assist your business',
    icon: Phone,
  },
];

function formatCurrency(amount: number): string {
  return 'TZS ' + amount.toLocaleString('en-US');
}

export default function App() {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState<string>('All');
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [cartDrawerOpen, setCartDrawerOpen] = useState(false);
  const [quickViewProduct, setQuickViewProduct] = useState<Product | null>(null);
  const [cartItems, setCartItems] = useState<{ product: Product; quantity: number }[]>([
    { product: PRODUCTS[0], quantity: 1 },
  ]);
  const [toastMessage, setToastMessage] = useState<string | null>(null);

  const totalCartCount = useMemo(() => {
    return cartItems.reduce((acc, item) => acc + item.quantity, 0);
  }, [cartItems]);

  const totalCartAmount = useMemo(() => {
    return cartItems.reduce((acc, item) => acc + item.product.price * item.quantity, 0);
  }, [cartItems]);

  const filteredProducts = useMemo(() => {
    return PRODUCTS.filter((p) => {
      const matchesSearch =
        searchQuery.trim() === '' ||
        p.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
        p.brand.toLowerCase().includes(searchQuery.toLowerCase());
      const matchesCategory =
        selectedCategory === 'All' || p.category === selectedCategory;
      return matchesSearch && matchesCategory;
    });
  }, [searchQuery, selectedCategory]);

  const showToast = (msg: string) => {
    setToastMessage(msg);
    setTimeout(() => {
      setToastMessage(null);
    }, 2800);
  };

  const addToCart = (product: Product) => {
    setCartItems((prev) => {
      const existing = prev.find((item) => item.product.id === product.id);
      if (existing) {
        return prev.map((item) =>
          item.product.id === product.id
            ? { ...item, quantity: item.quantity + 1 }
            : item
        );
      }
      return [...prev, { product, quantity: 1 }];
    });
    showToast(`Added ${product.name} to cart`);
  };

  const updateCartQty = (productId: number, delta: number) => {
    setCartItems((prev) => {
      return prev
        .map((item) => {
          if (item.product.id === productId) {
            const newQty = item.quantity + delta;
            return newQty > 0 ? { ...item, quantity: newQty } : null;
          }
          return item;
        })
        .filter(Boolean) as { product: Product; quantity: number }[];
    });
  };

  const removeCartItem = (productId: number) => {
    setCartItems((prev) => prev.filter((item) => item.product.id !== productId));
  };

  const whatsappCheckoutUrl = useMemo(() => {
    if (cartItems.length === 0) {
      return `https://wa.me/255787311342?text=${encodeURIComponent(
        'Hello Pande Computers! I would like to inquire about tech hardware.'
      )}`;
    }
    const lines = cartItems.map(
      (item) =>
        `- ${item.product.name} (x${item.quantity}) - ${formatCurrency(
          item.product.price * item.quantity
        )}`
    );
    const message = `Hello Pande Computers! I would like to order the following from your online store:\n\n${lines.join(
      '\n'
    )}\n\nTotal: ${formatCurrency(totalCartAmount)}\n\nPlease advise on delivery to my location.`;
    return `https://wa.me/255787311342?text=${encodeURIComponent(message)}`;
  }, [cartItems, totalCartAmount]);

  return (
    <div className="min-h-screen flex flex-col bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-600 selection:text-white">
      {/* Toast Notification */}
      {toastMessage && (
        <div className="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-slate-700 animate-bounce">
          <Check className="w-5 h-5 text-emerald-400" />
          <span className="text-sm font-medium">{toastMessage}</span>
        </div>
      )}

      {/* Top Utility Bar */}
      <div className="bg-slate-900 text-slate-300 text-xs border-b border-slate-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap justify-between items-center gap-2">
          <div className="flex items-center gap-4">
            <span className="flex items-center gap-1.5 text-slate-300">
              <MapPin className="w-3.5 h-3.5 text-amber-400" />
              Ndanda &amp; Aggrey St, Kariakoo, Dar es Salaam
            </span>
            <span className="hidden md:inline-block text-slate-600">|</span>
            <span className="hidden md:flex items-center gap-1.5 text-emerald-400">
              <CheckCircle2 className="w-3.5 h-3.5" />
              100% Genuine Authorized Tech
            </span>
          </div>
          <div className="flex items-center gap-4">
            <a
              href="https://wa.me/255787311342"
              target="_blank"
              rel="noopener noreferrer"
              className="text-emerald-400 hover:text-emerald-300 flex items-center gap-1 font-semibold transition"
            >
              <span>WhatsApp:</span> +255 787 311 342
            </a>
            <span className="text-slate-600">|</span>
            <a
              href="#contact"
              className="hover:text-white transition"
            >
              Contact Support
            </a>
          </div>
        </div>
      </div>

      {/* Main Header */}
      <header className="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-20 gap-4">
            {/* Logo */}
            <div className="flex items-center gap-3">
              <button
                type="button"
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="lg:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                aria-label="Toggle mobile menu"
              >
                {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
              </button>
              <a href="/" className="flex items-center gap-2 group">
                <div className="w-10 h-10 rounded-xl bg-blue-700 text-white flex items-center justify-center font-black text-xl shadow-md group-hover:bg-blue-800 transition">
                  P
                </div>
                <div className="flex flex-col">
                  <span className="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 group-hover:text-blue-700 transition">
                    PANDE<span className="text-blue-600"> COMPUTERS</span>
                  </span>
                  <span className="text-[10px] uppercase font-bold tracking-widest text-slate-500">
                    Your Tech. Your Way.
                  </span>
                </div>
              </a>
            </div>

            {/* Search Bar */}
            <div className="hidden md:flex flex-1 max-w-xl mx-4">
              <div className="relative w-full">
                <input
                  type="text"
                  placeholder="Search laptops, desktops, SSDs, printers, networking..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="w-full pl-11 pr-24 py-2.5 rounded-full border border-slate-300 bg-slate-50 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
                />
                <Search className="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <button
                  type="button"
                  className="absolute right-1.5 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-full hover:bg-blue-700 transition shadow-xs"
                >
                  Search
                </button>
              </div>
            </div>

            {/* Account & Cart Actions */}
            <div className="flex items-center gap-3">
              <a
                href="/login"
                className="hidden sm:flex items-center gap-2 text-slate-700 hover:text-blue-600 text-sm font-semibold px-3 py-2 rounded-lg hover:bg-slate-50 transition"
              >
                <User className="w-5 h-5 text-slate-500" />
                <span>Account</span>
              </a>

              {/* Shopping Cart Button */}
              <button
                type="button"
                onClick={() => setCartDrawerOpen(true)}
                className="relative flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3.5 py-2 rounded-xl text-sm font-semibold transition border border-blue-200"
                aria-label="View Shopping Cart"
              >
                <ShoppingCart className="w-5 h-5" />
                <span className="hidden sm:inline font-bold">Cart</span>
                <span className="bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                  {totalCartCount}
                </span>
              </button>
            </div>
          </div>

          {/* Mobile Search Bar */}
          <div className="md:hidden pb-3">
            <div className="relative w-full">
              <input
                type="text"
                placeholder="Search products..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 bg-slate-50 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
              />
              <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
            </div>
          </div>
        </div>

        {/* Horizontal Navigation Menu (Home, Laptops, Desktops, Computer Parts, Printers, Networking, Contact) */}
        <div className="border-t border-slate-200 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav className="flex items-center space-x-1 sm:space-x-4 overflow-x-auto py-2 text-sm font-semibold text-slate-700 no-scrollbar">
              <a
                href="/"
                className="px-3 py-1.5 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition whitespace-nowrap"
              >
                Home
              </a>
              <a
                href="#laptops"
                onClick={() => setSelectedCategory('Laptops')}
                className={`px-3 py-1.5 rounded-md transition whitespace-nowrap ${
                  selectedCategory === 'Laptops'
                    ? 'text-blue-700 bg-blue-50 font-bold'
                    : 'hover:text-blue-600 hover:bg-slate-50'
                }`}
              >
                Laptops
              </a>
              <a
                href="#desktops"
                onClick={() => setSelectedCategory('Desktops')}
                className={`px-3 py-1.5 rounded-md transition whitespace-nowrap ${
                  selectedCategory === 'Desktops'
                    ? 'text-blue-700 bg-blue-50 font-bold'
                    : 'hover:text-blue-600 hover:bg-slate-50'
                }`}
              >
                Desktops
              </a>
              <a
                href="#parts"
                onClick={() => setSelectedCategory('Computer Parts')}
                className={`px-3 py-1.5 rounded-md transition whitespace-nowrap ${
                  selectedCategory === 'Computer Parts'
                    ? 'text-blue-700 bg-blue-50 font-bold'
                    : 'hover:text-blue-600 hover:bg-slate-50'
                }`}
              >
                Computer Parts
              </a>
              <a
                href="#printers"
                onClick={() => setSelectedCategory('Printers')}
                className={`px-3 py-1.5 rounded-md transition whitespace-nowrap ${
                  selectedCategory === 'Printers'
                    ? 'text-blue-700 bg-blue-50 font-bold'
                    : 'hover:text-blue-600 hover:bg-slate-50'
                }`}
              >
                Printers
              </a>
              <a
                href="#networking"
                onClick={() => setSelectedCategory('Networking')}
                className={`px-3 py-1.5 rounded-md transition whitespace-nowrap ${
                  selectedCategory === 'Networking'
                    ? 'text-blue-700 bg-blue-50 font-bold'
                    : 'hover:text-blue-600 hover:bg-slate-50'
                }`}
              >
                Networking
              </a>
              <a
                href="#contact"
                className="px-3 py-1.5 rounded-md hover:text-blue-600 hover:bg-slate-50 transition whitespace-nowrap"
              >
                Contact
              </a>
            </nav>
          </div>
        </div>
      </header>

      {/* Main Content Area */}
      <main className="flex-1">
        {/* 1. Large Light-Blue Hero Section */}
        {/* CRITICAL DOM SELECTOR MUST MATCH:
            main:nth-of-type(1) > section:nth-of-type(1) > div:nth-of-type(1) > div:nth-of-type(1) > div:nth-of-type(2) > div:nth-of-type(1) > img:nth-of-type(1)
        */}
        <section className="bg-gradient-to-br from-sky-50 via-blue-50 to-indigo-100/60 py-12 lg:py-16 border-b border-blue-100">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid lg:grid-cols-2 gap-10 lg:gap-12 items-center">
              {/* Left Hero Content */}
              <div className="text-center lg:text-left space-y-6">
                <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-blue-700 text-xs sm:text-sm font-semibold border border-blue-200 shadow-xs">
                  <ShieldCheck className="w-4 h-4 text-blue-600" />
                  <span>Authorized Tanzanian IT Retailer</span>
                </div>

                <h1 className="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-tight">
                  YOUR TECH.{' '}
                  <span className="text-blue-600 block sm:inline">YOUR WAY.</span>
                </h1>

                <p className="text-lg sm:text-xl text-slate-600 max-w-xl mx-auto lg:mx-0 font-normal leading-relaxed">
                  Shop laptops, desktops, components and IT essentials online. Genuine computing hardware delivered across Dar es Salaam and nationwide.
                </p>

                {/* Hero CTAs */}
                <div className="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                  <a
                    href="#featured-products"
                    className="px-7 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition flex items-center gap-2 transform active:scale-95"
                  >
                    <span>Shop Now</span>
                    <ArrowRight className="w-4 h-4" />
                  </a>
                  <a
                    href="#categories"
                    className="px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-800 font-bold rounded-xl border border-slate-300 hover:border-slate-400 transition shadow-xs"
                  >
                    Explore Categories
                  </a>
                </div>

                {/* Quick Trust Highlights */}
                <div className="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-6 border-t border-blue-200/60 text-xs font-semibold text-slate-600 text-left">
                  <div className="flex items-center gap-2">
                    <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
                    <span>Kariakoo Walk-In</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <CheckCircle2 className="w-4 h-4 text-blue-600 shrink-0" />
                    <span>100% Genuine</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <CheckCircle2 className="w-4 h-4 text-amber-600 shrink-0" />
                    <span>Local Warranty</span>
                  </div>
                </div>
              </div>

              {/* Right Hero Visual Stage with Image */}
              <div className="flex justify-center items-center">
                <div className="w-full max-w-lg lg:max-w-none bg-white/70 backdrop-blur-xs p-4 sm:p-6 rounded-3xl border border-white/80 shadow-xl shadow-blue-900/5">
                  <img
                    src={heroShowcaseImg}
                    alt="Pande Computers Hardware Collection - Laptops, Desktops, Monitors, and Accessories"
                    className="w-full h-auto object-contain rounded-2xl drop-shadow-md hover:scale-[1.01] transition duration-300"
                    width={1100}
                    height={700}
                  />
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* 2. Trust Strip */}
        <section className="bg-white py-6 border-b border-slate-200">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              {TRUST_PILLARS.map((item, idx) => {
                const IconComponent = item.icon;
                return (
                  <div
                    key={idx}
                    className="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition"
                  >
                    <div className="p-2.5 rounded-lg bg-blue-100 text-blue-700 shrink-0">
                      <IconComponent className="w-5 h-5" />
                    </div>
                    <div>
                      <h4 className="text-sm font-bold text-slate-900">{item.title}</h4>
                      <p className="text-xs text-slate-500 mt-0.5 leading-relaxed">
                        {item.desc}
                      </p>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* 3. Six Image-Based Category Cards */}
        <section id="browse-categories" className="py-12 bg-slate-50 border-b border-slate-200">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-wrap items-end justify-between mb-8 gap-4">
              <div>
                <span className="text-xs font-bold uppercase tracking-wider text-blue-600">
                  Hardware Categories
                </span>
                <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                  Explore by Tech Department
                </h2>
              </div>
              <button
                type="button"
                onClick={() => setSelectedCategory('All')}
                className="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1"
              >
                <span>View All Products</span>
                <ArrowRight className="w-4 h-4" />
              </button>
            </div>

            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
              {CATEGORIES.map((cat) => (
                <button
                  type="button"
                  key={cat.id}
                  onClick={() => setSelectedCategory(cat.name.split(' ')[0])}
                  className={`group text-left p-4 rounded-2xl bg-white border transition duration-200 flex flex-col items-center justify-between shadow-xs hover:shadow-md hover:-translate-y-1 ${
                    selectedCategory === cat.name.split(' ')[0]
                      ? 'border-blue-600 ring-2 ring-blue-500/20'
                      : 'border-slate-200 hover:border-blue-300'
                  }`}
                >
                  <div className="w-full h-24 flex items-center justify-center p-2 mb-3 bg-slate-50 rounded-xl group-hover:bg-blue-50/50 transition">
                    <img
                      src={cat.image}
                      alt={cat.name}
                      className="max-h-20 max-w-full object-contain group-hover:scale-105 transition duration-200"
                    />
                  </div>
                  <div className="text-center w-full">
                    <h3 className="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-1">
                      {cat.name}
                    </h3>
                    <p className="text-[11px] text-slate-500 mt-0.5">{cat.itemCount}</p>
                  </div>
                </button>
              ))}
            </div>
          </div>
        </section>

        {/* 4. Featured Products Section (4-column layout) */}
        <section id="featured-products" className="py-12 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-wrap items-end justify-between mb-8 gap-4">
              <div>
                <span className="text-xs font-bold uppercase tracking-wider text-blue-600">
                  Top Recommended
                </span>
                <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                  Featured Products
                </h2>
                <p className="text-sm text-slate-500 mt-1">
                  Genuine business laptops, desktop powerhouses, and reliable peripherals.
                </p>
              </div>

              {/* Category Pills */}
              <div className="flex items-center gap-1.5 overflow-x-auto py-1">
                {['All', 'Laptops', 'Desktops', 'Printers', 'Networking'].map((c) => (
                  <button
                    key={c}
                    type="button"
                    onClick={() => setSelectedCategory(c)}
                    className={`px-3 py-1.5 rounded-full text-xs font-semibold transition whitespace-nowrap ${
                      selectedCategory === c
                        ? 'bg-blue-600 text-white shadow-xs'
                        : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                    }`}
                  >
                    {c}
                  </button>
                ))}
              </div>
            </div>

            {/* Product Cards Grid: 4 columns on desktop */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              {filteredProducts.map((prod) => (
                <div
                  key={prod.id}
                  className="group bg-white rounded-2xl border border-slate-200 hover:border-blue-400 p-4 flex flex-col justify-between transition duration-200 hover:shadow-lg hover:-translate-y-1 relative"
                >
                  {/* Brand Tag / Badge */}
                  <div className="flex items-center justify-between mb-2">
                    <span className="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-extrabold tracking-wide uppercase">
                      {prod.brand}
                    </span>
                    {prod.badge && (
                      <span className="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">
                        {prod.badge}
                      </span>
                    )}
                  </div>

                  {/* Product Image */}
                  <div className="w-full h-44 flex items-center justify-center p-3 mb-3 bg-slate-50/60 rounded-xl group-hover:bg-blue-50/30 transition relative overflow-hidden">
                    <img
                      src={prod.image}
                      alt={prod.name}
                      className="max-h-36 max-w-full object-contain group-hover:scale-105 transition duration-300"
                    />
                    <button
                      type="button"
                      onClick={() => setQuickViewProduct(prod)}
                      className="absolute inset-0 m-auto w-10 h-10 rounded-full bg-white/90 text-slate-700 shadow-md flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-blue-600 hover:text-white transition duration-200"
                      title="Quick Preview"
                    >
                      <Eye className="w-5 h-5" />
                    </button>
                  </div>

                  {/* Rating */}
                  <div className="flex items-center gap-1.5 mb-2">
                    <div className="flex text-amber-400">
                      {[...Array(5)].map((_, i) => (
                        <Star key={i} className="w-3.5 h-3.5 fill-current" />
                      ))}
                    </div>
                    <span className="text-xs text-slate-500 font-semibold">
                      {prod.rating.toFixed(1)} ({prod.reviewsCount})
                    </span>
                  </div>

                  {/* Product Title */}
                  <h3 className="text-sm font-bold text-slate-900 line-clamp-2 mb-2 group-hover:text-blue-600 transition">
                    {prod.name}
                  </h3>

                  {/* Price */}
                  <div className="mt-auto pt-2 border-t border-slate-100">
                    <div className="flex items-baseline gap-2 mb-3">
                      <span className="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                        {formatCurrency(prod.price)}
                      </span>
                      {prod.compareAtPrice && (
                        <span className="text-xs text-slate-400 line-through">
                          {formatCurrency(prod.compareAtPrice)}
                        </span>
                      )}
                    </div>

                    {/* Action Buttons: View Product & Add to Cart */}
                    <div className="grid grid-cols-2 gap-2">
                      <button
                        type="button"
                        onClick={() => setQuickViewProduct(prod)}
                        className="w-full py-2 px-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition text-center"
                      >
                        View Product
                      </button>
                      <button
                        type="button"
                        onClick={() => addToCart(prod)}
                        className="w-full py-2 px-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center justify-center gap-1 shadow-xs"
                      >
                        <ShoppingCart className="w-3.5 h-3.5" />
                        <span>Add</span>
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* 5. Custom Quote & Order Assistance Banner */}
        <section className="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white py-12">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-col md:flex-row items-center justify-between gap-6">
              <div className="text-center md:text-left space-y-2">
                <span className="text-xs font-bold uppercase tracking-wider text-blue-300">
                  Wholesale &amp; Institutional Supply
                </span>
                <h3 className="text-2xl sm:text-3xl font-extrabold">
                  Need Bulk Laptops or Office Hardware?
                </h3>
                <p className="text-slate-300 text-sm max-w-xl">
                  We supply corporate offices, schools, and NGOs across Tanzania with tax invoices, volume discounts, and on-site setup.
                </p>
              </div>
              <div className="flex flex-wrap gap-3">
                <a
                  href="https://wa.me/255787311342?text=Hello%20Pande%20Computers!%20I%20need%20a%20bulk%20hardware%20quotation."
                  target="_blank"
                  rel="noopener noreferrer"
                  className="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2"
                >
                  <Phone className="w-4 h-4" />
                  <span>WhatsApp Quotation</span>
                </a>
                <a
                  href="tel:+255787311342"
                  className="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl border border-white/20 transition"
                >
                  Call +255 787 311 342
                </a>
              </div>
            </div>
          </div>
        </section>
      </main>

      {/* Cart Drawer */}
      {cartDrawerOpen && (
        <div className="fixed inset-0 z-50 overflow-hidden">
          <div
            className="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            onClick={() => setCartDrawerOpen(false)}
          />
          <div className="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div className="w-screen max-w-md bg-white shadow-2xl flex flex-col">
              <div className="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div className="flex items-center gap-2">
                  <ShoppingCart className="w-5 h-5 text-blue-600" />
                  <h3 className="font-bold text-slate-900">Your Shopping Cart</h3>
                  <span className="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold">
                    {totalCartCount}
                  </span>
                </div>
                <button
                  type="button"
                  onClick={() => setCartDrawerOpen(false)}
                  className="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>

              <div className="flex-1 overflow-y-auto p-5 space-y-4">
                {cartItems.length === 0 ? (
                  <div className="text-center py-12 text-slate-400">
                    <ShoppingCart className="w-12 h-12 mx-auto mb-3 opacity-40" />
                    <p className="text-sm font-semibold">Your cart is currently empty.</p>
                    <button
                      type="button"
                      onClick={() => setCartDrawerOpen(false)}
                      className="mt-4 px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg"
                    >
                      Start Shopping
                    </button>
                  </div>
                ) : (
                  cartItems.map((item) => (
                    <div
                      key={item.product.id}
                      className="flex gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50/50"
                    >
                      <div className="w-16 h-16 bg-white rounded-lg p-1.5 flex items-center justify-center shrink-0 border border-slate-200">
                        <img
                          src={item.product.image}
                          alt={item.product.name}
                          className="max-h-full max-w-full object-contain"
                        />
                      </div>
                      <div className="flex-1 min-w-0">
                        <h4 className="text-xs font-bold text-slate-900 truncate">
                          {item.product.name}
                        </h4>
                        <p className="text-xs text-blue-600 font-extrabold mt-0.5">
                          {formatCurrency(item.product.price)}
                        </p>
                        <div className="flex items-center gap-2 mt-2">
                          <button
                            type="button"
                            onClick={() => updateCartQty(item.product.id, -1)}
                            className="w-6 h-6 rounded bg-white border border-slate-300 flex items-center justify-center text-slate-600 hover:bg-slate-100"
                          >
                            <Minus className="w-3 h-3" />
                          </button>
                          <span className="text-xs font-bold text-slate-800">
                            {item.quantity}
                          </span>
                          <button
                            type="button"
                            onClick={() => updateCartQty(item.product.id, 1)}
                            className="w-6 h-6 rounded bg-white border border-slate-300 flex items-center justify-center text-slate-600 hover:bg-slate-100"
                          >
                            <Plus className="w-3 h-3" />
                          </button>
                          <button
                            type="button"
                            onClick={() => removeCartItem(item.product.id)}
                            className="ml-auto text-slate-400 hover:text-red-500 p-1"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      </div>
                    </div>
                  ))
                )}
              </div>

              {cartItems.length > 0 && (
                <div className="p-5 border-t border-slate-200 bg-slate-50 space-y-4">
                  <div className="flex items-center justify-between text-sm">
                    <span className="text-slate-500 font-medium">Subtotal</span>
                    <span className="font-extrabold text-slate-900 text-base">
                      {formatCurrency(totalCartAmount)}
                    </span>
                  </div>
                  <a
                    href={whatsappCheckoutUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md text-center block transition text-sm"
                  >
                    Proceed to WhatsApp Order
                  </a>
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Quick View Product Modal */}
      {quickViewProduct && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div
            className="absolute inset-0 bg-slate-900/60 backdrop-blur-xs"
            onClick={() => setQuickViewProduct(null)}
          />
          <div className="relative bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-5">
            <button
              type="button"
              onClick={() => setQuickViewProduct(null)}
              className="absolute right-4 top-4 p-1.5 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100"
            >
              <X className="w-5 h-5" />
            </button>
            <div className="w-full h-52 bg-slate-50 rounded-2xl p-4 flex items-center justify-center">
              <img
                src={quickViewProduct.image}
                alt={quickViewProduct.name}
                className="max-h-full max-w-full object-contain"
              />
            </div>
            <div>
              <span className="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold uppercase">
                {quickViewProduct.brand}
              </span>
              <h3 className="text-lg font-bold text-slate-900 mt-2">
                {quickViewProduct.name}
              </h3>
              <div className="flex items-center gap-3 mt-3">
                <span className="text-2xl font-black text-slate-900">
                  {formatCurrency(quickViewProduct.price)}
                </span>
                {quickViewProduct.compareAtPrice && (
                  <span className="text-sm text-slate-400 line-through">
                    {formatCurrency(quickViewProduct.compareAtPrice)}
                  </span>
                )}
              </div>
            </div>
            <div className="flex gap-3 pt-2">
              <button
                type="button"
                onClick={() => {
                  addToCart(quickViewProduct);
                  setQuickViewProduct(null);
                }}
                className="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm flex items-center justify-center gap-2"
              >
                <ShoppingCart className="w-4 h-4" />
                <span>Add to Cart</span>
              </button>
              <a
                href={`https://wa.me/255787311342?text=${encodeURIComponent(
                  `Hello Pande Computers! I am interested in ${quickViewProduct.name} (${formatCurrency(
                    quickViewProduct.price
                  )}). Is it currently available?`
                )}`}
                target="_blank"
                rel="noopener noreferrer"
                className="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition text-sm flex items-center justify-center gap-1.5"
              >
                <span>WhatsApp</span>
              </a>
            </div>
          </div>
        </div>
      )}

      {/* 6. Dark Navy Footer */}
      <footer id="contact" className="bg-slate-950 text-slate-400 border-t border-slate-900 text-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            {/* Col 1: About Pande Computers */}
            <div className="space-y-4">
              <div className="flex items-center gap-2">
                <div className="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-lg">
                  P
                </div>
                <span className="text-xl font-black tracking-tight text-white">
                  PANDE<span className="text-blue-500"> COMPUTERS</span>
                </span>
              </div>
              <p className="text-xs text-slate-400 leading-relaxed">
                Your premier computer and technology retailer in Kariakoo, Dar es Salaam, Tanzania. Genuine laptops, monitors, printers, networking hardware, and professional IT solutions.
              </p>
              <div className="space-y-2 text-xs pt-1">
                <div className="flex items-start gap-2">
                  <MapPin className="w-4 h-4 text-amber-400 shrink-0 mt-0.5" />
                  <span>Ndanda &amp; Aggrey Street, Kariakoo, Dar es Salaam, Tanzania</span>
                </div>
                <div className="flex items-center gap-2">
                  <Phone className="w-4 h-4 text-emerald-400 shrink-0" />
                  <a
                    href="https://wa.me/255787311342"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-emerald-400 hover:text-emerald-300 font-semibold"
                  >
                    +255 787 311 342 (Call &amp; WhatsApp)
                  </a>
                </div>
                <div className="flex items-center gap-2">
                  <Mail className="w-4 h-4 text-blue-400 shrink-0" />
                  <span>info@pandecomputers.co.tz</span>
                </div>
              </div>
            </div>

            {/* Col 2: Quick Links */}
            <div>
              <h4 className="text-white font-bold text-sm mb-4 tracking-wider uppercase">
                Quick Navigation
              </h4>
              <ul className="space-y-2.5 text-xs">
                <li>
                  <a href="/" className="hover:text-white transition">
                    Home
                  </a>
                </li>
                <li>
                  <a href="#categories" className="hover:text-white transition">
                    Browse All Categories
                  </a>
                </li>
                <li>
                  <a href="#featured-products" className="hover:text-white transition">
                    Featured Products
                  </a>
                </li>
                <li>
                  <a href="/shop" className="hover:text-white transition">
                    Complete Store Catalog
                  </a>
                </li>
                <li>
                  <a href="/about" className="hover:text-white transition">
                    About Pande Computers
                  </a>
                </li>
                <li>
                  <a href="/contact" className="hover:text-white transition">
                    Store Location &amp; Directions
                  </a>
                </li>
              </ul>
            </div>

            {/* Col 3: Hardware Categories */}
            <div>
              <h4 className="text-white font-bold text-sm mb-4 tracking-wider uppercase">
                Hardware Departments
              </h4>
              <ul className="space-y-2.5 text-xs">
                <li>
                  <a href="#laptops" className="hover:text-white transition">
                    Business &amp; Gaming Laptops
                  </a>
                </li>
                <li>
                  <a href="#desktops" className="hover:text-white transition">
                    Desktops &amp; All-in-One PCs
                  </a>
                </li>
                <li>
                  <a href="#parts" className="hover:text-white transition">
                    Internal SSDs &amp; RAM Upgrades
                  </a>
                </li>
                <li>
                  <a href="#printers" className="hover:text-white transition">
                    Epson EcoTank &amp; HP LaserJets
                  </a>
                </li>
                <li>
                  <a href="#networking" className="hover:text-white transition">
                    Routers, Switches &amp; Access Points
                  </a>
                </li>
                <li>
                  <a href="#categories" className="hover:text-white transition">
                    APC Smart-UPS &amp; Surge Protectors
                  </a>
                </li>
              </ul>
            </div>

            {/* Col 4: Business Hours & Payment */}
            <div className="space-y-4">
              <h4 className="text-white font-bold text-sm tracking-wider uppercase">
                Showroom Hours
              </h4>
              <div className="text-xs space-y-1.5 text-slate-300">
                <p>
                  <strong className="text-white">Monday – Friday:</strong> 8:30 AM – 6:30 PM
                </p>
                <p>
                  <strong className="text-white">Saturday:</strong> 9:00 AM – 5:00 PM
                </p>
                <p>
                  <strong className="text-white">Sunday &amp; Holidays:</strong> Closed / WhatsApp Orders
                </p>
              </div>

              <div className="pt-2">
                <h5 className="text-xs font-bold text-white mb-2">Accepted Payment Methods</h5>
                <div className="flex flex-wrap gap-1.5 text-[11px]">
                  <span className="px-2 py-1 bg-slate-900 border border-slate-800 rounded text-slate-300">
                    M-Pesa
                  </span>
                  <span className="px-2 py-1 bg-slate-900 border border-slate-800 rounded text-slate-300">
                    Tigo Pesa
                  </span>
                  <span className="px-2 py-1 bg-slate-900 border border-slate-800 rounded text-slate-300">
                    Airtel Money
                  </span>
                  <span className="px-2 py-1 bg-slate-900 border border-slate-800 rounded text-slate-300">
                    Bank Transfer
                  </span>
                  <span className="px-2 py-1 bg-slate-900 border border-slate-800 rounded text-slate-300">
                    Cash on Delivery
                  </span>
                </div>
              </div>
            </div>
          </div>

          {/* Bottom Copyright */}
          <div className="mt-12 pt-8 border-t border-slate-900 text-xs flex flex-col sm:flex-row items-center justify-between gap-4 text-slate-500">
            <p>
              &copy; {new Date().getFullYear()} Pande Computers. All rights reserved. Kariakoo, Dar es Salaam.
            </p>
            <p className="flex items-center gap-1">
              <span>Your Trusted Tech Partner in Tanzania</span>
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
}
