# 📦 rifatxtra/laravel-featurekit (v2.0.0)

### Professional Laravel 12 Feature-Driven Starter Kit.

Built for developers who want to skip the "boring" setup and start building production-grade applications from day one.

---

## 🚀 Key Features

- **🏛️ Feature-Driven Architecture**: Self-contained domains in `app/Features/` — each feature owns its Controllers, Services, Models, Requests, Observers, Events, Exceptions & Routes.
- **⚛️ Next-Gen Frontend**: Next.js (App Router) style folder structure with React 19 + Inertia.js v2 for SPA portals. Blade + Tailwind v4 for SEO-critical pages.
- **🛡️ Intelligent Layouts**: Automatic persistent layout injection (MainLayout) for all dashboard pages — zero configuration required.
- **📬 Universal Mail System**: A single queued `GeneralMail` class with a professional Markdown master template handles every email in your app.
- **🧰 11-Module JS Utility Suite**: Image Compression, Toast, Storage, Clipboard, Date, Number/Currency, String, Validation, Performance (debounce/throttle), and Web Vitals monitoring.
- **🏗️ 7 Custom Artisan Commands**: Generate entire features, controllers, services, requests, events, observers, and exceptions — all wired to the Feature-Driven Architecture.
- **🎨 Tailwind CSS v4**: Semantic `@theme` design tokens (primary, secondary, surface, error, success) with OKLCH color space for accessible, white-label–ready theming.
- **🔐 Complete Auth System**: Login, Register, Forgot Password, and Reset Password — fully coded with Blade views, form requests, and service-layer logic.
- **🎯 Pre-Built UI Kit**: React components for Toast, Modal, LoadingSpinner, Pagination, SeoHead, BasicEditor (TipTap), and 5 PromoTemplate variants.
- **🪝 20+ Custom React Hooks**: `useAuth`, `useUser`, `useHasRole`, `useHasPermission`, `useFlash`, `useErrors`, `useRoute`, and more — all in one barrel export.
- **⚡ Single-Command Dev**: `composer dev` launches the Laravel server, queue listener, Pail log viewer, and Vite HMR simultaneously via `concurrently`.
- **🗄️ Zero-Config Routing**: Routes inside `app/Features/*/routes/web.php` and `api.php` are auto-discovered at boot — no manual registration needed.
- **🛡️ Built-in Middleware**: `HandleInertiaRequests` (shares auth, flash, CSRF, config to all pages) + `RoleMiddleware` (gate routes with `role:admin`).

---

## ⚡ Quick Start

```bash
composer create-project rifatxtra/laravel-featurekit my-app
cd my-app
composer setup    # installs deps, copies .env, generates key, migrates DB, builds assets
composer dev      # starts server + queue + logs + vite concurrently
```

> **Note:** Uses SQLite by default — no database server required. Switch to MySQL/Postgres via `.env`.

---

## 📂 Project Structure

```
├── app/
│   ├── Console/Commands/       # 7 custom Artisan scaffolding commands
│   ├── Core/                   # BaseController, BaseService, ApiResponseTrait, BaseException
│   │   └── Middleware/         # HandleInertiaRequests, RoleMiddleware
│   ├── Features/               # 🏛️ Feature-Driven Domains
│   │   ├── Auth/               # Login, Register, ForgotPassword (full implementation)
│   │   │   ├── Controllers/    # LoginController, RegisterController, ForgotPasswordController
│   │   │   ├── Services/       # LoginService, RegisterService, ForgotPasswordService
│   │   │   ├── Models/         # User (Authenticatable)
│   │   │   ├── Requests/       # LoginRequest, RegisterRequest
│   │   │   ├── Observers/      # UserObserver (scaffold)
│   │   │   ├── Exceptions/     # InvalidCredentialsException (scaffold)
│   │   │   └── routes/         # web.php, api.php (auto-discovered)
│   │   └── Landing/            # Home, Documentation, Features pages
│   │       ├── Controllers/    # LandingController
│   │       └── routes/         # web.php (auto-discovered)
│   ├── Mail/                   # GeneralMail (universal queued mailable)
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
│   │           │   ├── layout.jsx # Admin layout component
│   │           └── user/
│   │               ├── layout.jsx # User layout component
│   └── views/
│       ├── app.blade.php       # Inertia root template (React SPA)
│       ├── emails/             # Email layout + content templates
│       ├── layout/             # Header & footer partials
│       └── pages/              # Blade pages (auth, home, components)
├── bootstrap/app.php           # Auto-route discovery engine
├── database/migrations/        # Users, sessions, cache, jobs tables
└── config/                     # Standard Laravel config files
```

---

## 🛠️ Tech Stack

| Layer        | Technology                                | Version |
| :----------- | :---------------------------------------- | :------ |
| **Backend**  | Laravel Framework                         | 12.x    |
| **Frontend** | React                                     | 19.x    |
| **Bridge**   | Inertia.js                                | 3.x     |
| **Styling**  | Tailwind CSS                              | 4.x     |
| **Build**    | Vite                                      | 8.x     |
| **PHP**      | PHP                                       | 8.2+    |
| **Database** | SQLite (default), MySQL, PostgreSQL       | —       |
| **Queue**    | Database driver (default)                 | —       |
| **Testing**  | PHPUnit                                   | 11.x   |

---

## 📖 Documentation & Guides

For detailed code examples, architectural deep-dives, every utility API, and all Artisan commands:

👉 **[DOCS.md](DOCS.md)**

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
