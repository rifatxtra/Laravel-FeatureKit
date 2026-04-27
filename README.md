# 📦 rifatxtra/laravel-feature-kit (v3.0.0)

### The Professional Laravel 12 Feature-Driven Starter Kit (Laravel Feature Kit).

Built for developers who want to skip the "boring" setup and start building production-grade applications from day one.

---

## 🆕 What's New in v3.0.0

### 🏛️ Professional MVC Architecture Overhaul
The backend has been completely refactored from a feature-driven structure to a **standardized, professional Laravel MVC** architecture. This ensures maximum compatibility with the Laravel ecosystem, better IDE support, and a cleaner developer experience while keeping the powerful frontend features intact.

#### ✨ Key Improvements
- **Consolidated Models**: No more duplicate models! Core models live in `app/Models` as the single source of truth.
- **Centralized Routing**: Removed complex auto-discovery logic for standard, explicit, and performant routing in `routes/web.php`.
- **Organized Layering**: Controllers, Services, and Requests are now organized by role (`Admin`, `User`, `Auth`) within their standard Laravel directories.
- **Improved Performance**: Faster boot times by removing filesystem-heavy route discovery.
- **Standardized Naming**: Fixed various typos and inconsistent naming conventions across the backend.

---

## 🆕 What's New in v2.2.0

### 📊 Advanced Traffic Analytics Console
The built-in traffic tracker has been completely rebuilt from a basic visitor log into a **professional-grade analytics console** — on par with lightweight alternatives to Google Analytics, running entirely within your own Laravel app.

#### ✨ New Features
- **3-Tab Dashboard UI**: Overview, Real-Time, and Behavior tabs — each with dedicated charts and data.
- **Real-Time Visitor Monitoring**: REST-based polling (no WebSockets needed) refreshes live stats every 15 seconds — active visitors, pages being viewed, hit stream, and active countries.
- **Traffic Heatmap**: A 7×24 hour/day intensity grid showing when your site is busiest.
- **Geographic Tracking**: Country detection via `ip-api.com` — displayed with flag emojis, no extra PHP packages needed.
- **Referrer Categorization**: Automatically classifies traffic into Direct, Search, Social, and Other.
- **Session Analytics**: Bounce rate, average pages per session, new vs returning visitors — all computed from session-ID hashes (privacy-safe, no cookies).
- **HTTP Status Code Monitoring**: 2xx/3xx/4xx/5xx breakdown with visual indicators.
- **Response Time Tracking**: Per-request timing in milliseconds with trend charts and color-coded performance indicators.
- **Real IP Resolution**: Proxy-aware IP detection supporting Cloudflare (`CF-Connecting-IP`), nginx (`X-Real-IP`), and standard load balancers (`X-Forwarded-For`) via Laravel's `trustProxies`.
- **Enhanced Log Viewer**: New columns for status code, response time, country, device type, and new visitor status with new filter dropdowns.
- **Detailed Visit Modal**: Click any log row to inspect the full request audit — UA string, browser, OS, country, response time, session info.

#### 📂 New/Modified Files
- `database/migrations/2026_04_11_…_add_advanced_fields_to_traffic_logs_table.php` — 6 new columns.
- `app/Models/TrafficLog.php` — consolidated model.
- `app/Http/Middleware/TrackTraffic.php` — captures response time, status code, session ID; proxy-aware IP resolution.
- `app/Jobs/ProcessTrafficLog.php` — geo lookup, new vs returning detection, extended browser/OS/bot parsing.
- `app/Services/Admin/TrafficAnalyticsService.php` — 16 aggregations + real-time stats endpoint.
- `app/Http/Controllers/Admin/TrafficController.php` — new `GET /admin/traffic/realtime` REST endpoint.
- `routes/web.php` — added realtime route.
- `resources/js/pages/(portals)/admin/traffic/page.jsx` — complete 3-tab dashboard redesign.
- `resources/js/pages/(portals)/admin/traffic/logs/page.jsx` — enhanced with new columns and filters.
- `bootstrap/app.php` — `trustProxies(at: '*')` for correct real-IP resolution.

---

## 🆕 What's New in v2.1.2

### ⚙️ Dynamic System Settings & Branding
You can now manage your application's identity and availability directly from the Admin Portal without touching a single line of code.

- **Dynamic branding**: Change **App Name**, upload a **Logo**, and a **Favicon** instantly.
- **Favicon Engine**: Automated GD-powered conversion of any image to a professional 32x32 `.ico` file.
- **Smart Maintenance Mode**:
    - **Admin Bypass**: Keeps admins productive by allowing access to `/admin` and `/auth` routes during maintenance.
    - **SPA Support**: Detects Inertia requests and forces a full reload to the branded 503 page.
    - **Dynamic Duration**: Set estimated downtime from the UI, reflected on the maintenance page.
- **Source of Truth**: All settings are cached indefinitely using `Setting::get($key, $default)` for maximum performance.

#### 📂 New/Modified Files:
- `app/Http/Requests/Admin/UpdateProfileRequest.php` — updated requests.
- `app/Utils/FaviconUtil.php` — Image to ICO conversion utility.
- `app/Http/Middleware/CheckMaintenanceMode.php` — Advanced maintenance gate.
- `resources/views/errors/503.blade.php` — Premium branded downtime template.
- `resources/js/pages/(portals)/admin/settings/page.jsx` — Interactive settings dashboard.

---

## 🚀 Key Features

- **🏛️ Standardized MVC Architecture**: Clean separation of concerns with Controllers, Services, Models, and Requests organized into standard Laravel directories with role-based subfolders.
- **⚛️ Next-Gen Frontend**: Next.js (App Router) style folder structure with React 19 + Inertia.js v3 for SPA portals. Blade + Tailwind v4 for SEO-critical pages.
- **🛡️ Intelligent Layouts**: Automatic persistent layout injection (MainLayout) for all dashboard pages — zero configuration required.
- **📬 Universal Mail System**: A single queued `GeneralMail` class with a professional Markdown master template handles every email in your app.
- **🧰 11-Module JS Utility Suite**: Image Compression, Toast, Storage, Clipboard, Date, Number/Currency, String, Validation, Performance (debounce/throttle), and Web Vitals monitoring.
- **🎨 Tailwind CSS v4**: Semantic `@theme` design tokens (primary, secondary, surface, error, success) with OKLCH color space for accessible, white-label–ready theming.
- **🔐 Complete Auth System**: Login, Register, Forgot Password, and Reset Password — fully coded with Blade views, form requests, and service-layer logic.
- **🎯 Pre-Built UI Kit**: React components for Toast, Modal, LoadingSpinner, Pagination, SeoHead, BasicEditor (TipTap), and 5 PromoTemplate variants.
- **🪝 20+ Custom React Hooks**: `useAuth`, `useUser`, `useHasRole`, `useHasPermission`, `useFlash`, `useErrors`, `useRoute`, and more — all in one barrel export.
- **⚡ Single-Command Dev**: `composer dev` launches the Laravel server, queue listener, Pail log viewer, and Vite HMR simultaneously via `concurrently`.
- **🗄️ Clean Routing**: Centralized, explicit routing in `routes/web.php` and `routes/api.php` — no manual discovery overhead.
- **🛡️ Built-in Middleware**: `HandleInertiaRequests` (shares auth, flash, CSRF, config to all pages) + `RoleMiddleware` (gate routes with `role:admin`).
- **📊 Analytics Console**: Built-in full-stack traffic analytics with real-time monitoring, heatmaps, geo tracking, session metrics, and performance trends — no third-party service needed.

---

## ⚡ Quick Start

```bash
composer create-project rifatxtra/laravel-feature-kit my-app
cd my-app
composer setup    # installs deps, copies .env, generates key, migrates DB, builds assets
composer dev      # starts server + queue + logs + vite concurrently
```

> **Note:** Uses SQLite by default — no database server required. Switch to MySQL/Postgres via `.env`.
> **Queue Worker Required:** For traffic analytics geo-lookup, run `php artisan queue:work` alongside `composer dev`.

---

## 📂 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/        # 🎮 Controllers (Admin, User, Auth, Landing)
│   │   ├── Middleware/         # 🛡️ Middleware (Inertia, Role, Maintenance, Traffic)
│   │   └── Requests/           # ✅ Form Requests (Admin, User, Auth)
│   ├── Services/               # 🧠 Business Logic (Admin, User, Auth)
│   ├── Models/                 # 🗄️ Core Models (User, Notification, Setting, etc.)
│   ├── Events/                 # 📢 Domain Events
│   ├── Listeners/              # 👂 Event Listeners
│   ├── Jobs/                   # ⚙️ Background Jobs
│   ├── Mail/                   # 📬 Universal Mail System
│   ├── Utils/                  # 🛠️ Helper Utilities (Favicon, etc.)
│   └── Providers/              # AppServiceProvider
├── resources/
│   ├── css/app.css             # Tailwind v4 @theme design tokens
│   ├── js/
│   │   ├── app.jsx             # Inertia entry point with auto-layout resolver
│   │   ├── Components/
│   │   │   ├── Layout/         # MainLayout (shared base)
│   │   │   └── ui/             # Toast, Modal, etc.
│   │   ├── Contexts/           # ModalContext (global modal state)
│   │   ├── Hooks/              # 20+ Inertia hooks (useAuth, useUser, etc.)
│   │   ├── Utils/              # 11 utility modules with barrel export
│   │   └── pages/              # ⚛️ React Page Components
│   │       └── (portals)/      # Role-based layouts & pages
│   │           ├── admin/
│   │           │   ├── traffic/  # 📊 Analytics dashboard (3-tab console)
│   │           │   └── layout.jsx
│   │           └── user/
│   │               └── layout.jsx
│   └── views/
│       ├── app.blade.php       # Inertia root template (React SPA)
│       ├── emails/             # Email layout + content templates
│       ├── layout/             # Header & footer partials
│       └── pages/              # Blade pages (auth, home, components)
├── bootstrap/app.php           # Auto-route discovery engine + trusted proxies
├── database/migrations/        # Users, sessions, cache, jobs, traffic_logs tables
└── config/                     # Standard Laravel config files
```

---

## 🛠️ Tech Stack

| Layer        | Technology                          | Version |
| :----------- | :---------------------------------- | :------ |
| **Backend**  | Laravel Framework                   | 12.x    |
| **Frontend** | React                               | 19.x    |
| **Bridge**   | Inertia.js                          | 3.x     |
| **Styling**  | Tailwind CSS                        | 4.x     |
| **Build**    | Vite                                | 8.x     |
| **PHP**      | PHP                                 | 8.2+    |
| **Database** | SQLite (default), MySQL, PostgreSQL | —       |
| **Queue**    | Database driver (default)           | —       |
| **Testing**  | PHPUnit                             | 11.x    |

---

## 📋 Changelog

### v3.0.0 — Professional MVC Refactor *(Current)*
- 🏗️ **Architecture Overhaul**: Refactored from FDD to standard Laravel MVC.
- 🗄️ **Model Consolidation**: Unified models in `app/Models`.
- 🎮 **Organized Controllers**: Role-based folders in `app/Http/Controllers`.
- 🧠 **Service Layer**: Business logic moved to role-based `app/Services`.
- ✅ **Standard Requests**: Validation moved to `app/Http/Requests`.
- 🗄️ **Central Routing**: Removed auto-route discovery for central `routes/web.php`.
- 🛡️ **Middleware & Events**: Moved to standard Laravel locations.

### v2.2.0 — Traffic Analytics Console
- ✨ Complete rebuild of Traffic Analytics into a 3-tab professional console
- ✨ Real-time visitor monitoring via REST polling (no WebSockets)
- ✨ 7×24 traffic heatmap, geographic breakdown with flag emojis
- ✨ Session metrics: bounce rate, pages/session, new vs returning
- ✨ HTTP status code tracking (2xx/3xx/4xx/5xx)
- ✨ Response time trend charts with ms-precision per-request timing
- ✨ Proxy-aware real IP resolution (Cloudflare, nginx, load balancers)
- ✨ Enhanced log viewer with status, response time, country, and device filters
- 🗄️ Migration: 6 new columns on `traffic_logs` table

### v2.1.2 — System Settings & Branding
- ✨ Dynamic app name, logo, and favicon management from Admin UI
- ✨ GD-powered favicon ICO conversion
- ✨ Smart maintenance mode with admin bypass and branded 503 page

### v2.1.1
- 🐛 Bug fixes and stability improvements

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

MIT Licensed. Open for everyone to scale.

---

Built with ❤️ for rapid Laravel development by [Rifat](https://rifatxtra.com).
