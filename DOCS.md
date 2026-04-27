# 🚀 Laravel Feature Kit v3.0.0 — Complete Technical Documentation

Welcome to the definitive guide for **Laravel Feature Kit (rifatxtra/laravel-feature-kit)**. This document covers every system, pattern, utility, and command in the project — no detail omitted.

### ⚙️ System Settings & Branding

Core interface for dynamic app configuration.

- **Models**: `Setting` (key/value with caching).
- **Logic**: `SettingsService` (branded asset management), `FaviconUtil` (ICO conversion).
- **Maintenance**: `CheckMaintenanceMode` middleware (Admin bypass + Inertia reload support).
- **Usage**: `\App\Models\Setting::get($key, $default)`.

---

## Table of Contents

1. [Architecture: Professional MVC](#-1-architecture-professional-mvc)
2. [Core Infrastructure](#-2-core-infrastructure)
3. [Routing System](#-3-routing-system)
4. [Frontend: Hybrid Strategy](#-4-frontend-hybrid-strategy)
5. [React UI Component Kit](#-5-react-ui-component-kit)
6. [Context System (Global State)](#-6-context-system)
7. [Custom React Hooks Library](#-7-custom-react-hooks-library)
8. [JavaScript Utility Suite](#-8-javascript-utility-suite)
9. [Modern Mailing System](#-9-modern-mailing-system)
10. [Auth Role](#-10-auth-role)
11. [Landing Feature](#-11-landing-feature)
12. [Tailwind CSS v4 Design System](#-12-tailwind-css-v4-design-system)
13. [Dev Environment & Scripts](#-13-dev-environment--scripts)
14. [Database & Migrations](#-14-database--migrations)
15. [Quick Reference Table](#-15-quick-reference-table)
16. [Unified Notification System](#-16-unified-notification-system)
17. [Advanced Development Patterns](#-17-advanced-development-patterns)
18. [Activity Logs System](#-18-activity-logs-system)
19. [Core Administrative Hubs](#-19-core-administrative-hubs)
20. [Traffic Analytics Console](#-20-traffic-analytics-console)

---

## 🏛️ 1. Architecture: Professional MVC

Laravel Feature Kit follows a **Professional MVC** (Model-View-Controller) architecture, organized by **Role** rather than by feature. This approach provides the best balance between clean separation of concerns and standard Laravel conventions.

### Folder Structure

The backend is organized into standard Laravel directories, with subdirectories for `Admin`, `User`, and `Auth` roles where applicable.

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/        # Admin-only logic
│   │   ├── User/         # Regular user logic
│   │   ├── Auth/         # Authentication logic
│   │   └── Landing/      # Public landing pages
│   ├── Requests/
│   │   ├── Admin/
│   │   ├── User/
│   │   └── Auth/
│   └── Middleware/       # Global & Role-based middleware
├── Services/             # 🧠 Business Logic (The brain of the app)
│   ├── Admin/
│   ├── User/
│   └── Auth/
├── Models/               # 🗄️ Single source of truth for data
└── Utils/                # Helper classes & utilities
```

### Thin Controller + Service Pattern

Controllers have **one job**: translate HTTP requests. All business logic lives in Services.

```php
// ✅ Thin controller delegating to a service
public function store(RegisterRequest $request, RegisterService $service) {
    $service->register($request->validated());
    return redirect()->intended(route('home.index'));
}
```

```php
// app/Services/Auth/RegisterService.php
namespace App\Services\Auth;

use App\Services\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterService extends Service
{
    public function register(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        return $user;
    }
}
```

### Models: Single Source of Truth

Unlike FDD where models might be duplicated or hidden in feature folders, all models in Feature Kit live in `app/Models/`. This ensures that relationships and global scopes are easy to manage.

---

## 🧱 2. Core Infrastructure

Feature Kit provides standardized base classes and middleware in standard Laravel locations.

### `Controller.php`

**Location:** `app/Http/Controllers/Controller.php`

All controllers extend this base class, which includes the `ApiResponseTrait`.

### `ApiResponseTrait`

**Location:** `app/Traits/ApiResponseTrait.php`

Standardized JSON responses for consistent API output.

### `Service.php`

**Location:** `app/Services/Service.php`

Abstract base for all services.

### Core Middleware

**Location:** `app/Http/Middleware/`

#### `HandleInertiaRequests`

Appended to the `web` middleware stack. Shares data to **every Inertia page** automatically.

#### `RoleMiddleware`

Registered as alias `'role'`. Gates routes by the authenticated user's role.

| Shared Key   | Type      | Powers                                               |
| :----------- | :-------- | :--------------------------------------------------- | ------------------------------------------------- |
| `auth.check` | `boolean` | `useIsAuthenticated()`, `useIsGuest()`               |
| `auth.user`  | `object   | null`                                                | `useUser()`, `useHasRole()`, `useHasPermission()` |
| `flash`      | `object`  | `useFlash()`, `useFlashSuccess()`, `Toast` component |
| `csrf_token` | `string`  | `useCsrfToken()`                                     |
| `app`        | `object`  | `useAppConfig()`                                     |

The `auth.user` object includes: `id`, `name`, `email`, `phone`, `email_verified_at`, `created_at`, `roles`, `permissions`. Roles/permissions are auto-included if the relationships exist on the User model.

#### `RoleMiddleware`

Registered as alias `'role'`. Gates routes by the authenticated user's role:

```php
// Single role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// Multiple roles (any match grants access)
Route::middleware(['auth', 'role:admin,moderator'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index']);
});
```

Supports three role formats:

- **String property**: `$user->role` (e.g., `"admin"`)
- **Relationship array**: `$user->roles` as collection of objects with `name` key
- **Plain array**: `$user->roles` as `["admin", "editor"]`

Unauthenticated users are redirected to login. Unauthorized users receive a `403` response.

---

## 🗺️ 3. Routing System

**Location:** `routes/web.php` and `routes/api.php`

Feature Kit uses explicit, centralized routing. This is more performant and easier to debug than auto-discovery engines.

### Route Organization

Routes are organized using `Route::group()` with role-based prefixes and middleware.

```php
// Admin Portal
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ...
});

// User Portal
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ...
});
```

### Trusted Proxies

Configured in `bootstrap/app.php` to ensure real IP resolution (Cloudflare, nginx) works out of the box for traffic analytics.

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
})
```

---

## ⚛️ 4. Frontend: Hybrid Strategy

Feature Kit uses a **dual rendering strategy** for optimal performance and interactivity.

### SEO Layer (Blade + Tailwind v4)

Used for: **Landing page, Documentation page, Features page, Auth forms.**

- **Master Layout:** `resources/views/pages/layout.blade.php` — includes header, footer, Vite assets, SEO meta tags (OG + Twitter Cards), and a loading spinner.
- **Auth Layout:** `resources/views/pages/auth/layout.blade.php` — split-screen design with brand panel and form area.
- **Partials:** Header (`layout/header.blade.php`) with responsive nav and auth-aware buttons; Footer (`layout/footer.blade.php`) with social links and sitemap.

```blade
@extends('pages.layout')

@section('title', 'Welcome to Feature Kit')

@section('content')
    <div class="bg-surface text-foreground p-8 rounded-lg shadow-sm">
        <h1 class="text-primary text-4xl font-bold">Build Faster.</h1>
        <p class="mt-4 opacity-80 text-lg">Your next-gen SaaS starts here.</p>
    </div>
@endsection
```

### SPA Layer (Inertia.js v3 + React 19)

Used for: **Admin and User Portals (dashboards, internal tools).**

- **Entry Point:** `resources/js/app.jsx` — Inertia app with automatic layout injection.
- **Inertia Root Template:** `resources/views/app.blade.php` — includes `@inertia`, `@inertiaHead`, `@viteReactRefresh`.
- **Portal Layout Convention:** Next.js App Router pattern: `resources/js/pages/(portals)/admin/layout.jsx`.
- **Page Convention:** `resources/js/pages/(portals)/admin/dashboard/page.jsx`.

#### Automatic Layout Injection

The `app.jsx` resolver checks if a page component has a `.layout` property. If not, it **automatically wraps the page in `MainLayout`**:

```javascript
// resources/js/app.jsx
resolve: async (name) => {
    const pages = import.meta.glob("./pages/**/*.jsx");
    const page = await pages[`./pages/${name}.jsx`]();

    if (page.default.layout === undefined) {
        const MainLayout = await import("./Components/Layout/MainLayout");
        page.default.layout = (p) => (
            <MainLayout.default>{p}</MainLayout.default>
        );
    }

    return page;
},
```

This means **every page gets Toast, Modal, and LoadingSpinner for free** without any configuration.

#### Custom Layout Override

To use a different layout (e.g., `AdminLayout`), set the `.layout` property:

```javascript
import AdminLayout from "@/pages/(portals)/admin/layout";

const SettingsPage = ({ settings }) => {
    return <div>...</div>;
};

SettingsPage.layout = (page) => <AdminLayout>{page}</AdminLayout>;
export default SettingsPage;
```

#### Available Layouts

| Layout        | File                               | Purpose                                              |
| :------------ | :--------------------------------- | :--------------------------------------------------- |
| `MainLayout`  | `Components/Layout/MainLayout.jsx` | Default persistent wrapper (Toast + Modal + Spinner) |
| `AdminLayout` | `pages/(portals)/admin/layout.jsx` | Admin dashboard shell with sidebar navigation        |
| `UserLayout`  | `pages/(portals)/user/layout.jsx`  | User portal shell with user-facing navigation        |

---

## 🎨 5. React UI Component Kit

All components live in `resources/js/Components/ui/` and are production-ready.

### `Toast.jsx` — Flash Notification System

Automatically reads Laravel flash messages (`flash.success`, `flash.error`, `flash.info`, `flash.warning`) from Inertia shared data and displays styled notifications.

- Auto-dismisses after 5 seconds.
- Slide-in animation with close button.
- Stacks multiple toasts vertically.

#### Flash Key Mapping & Colors

| Flash Session Key | UI Badge / Border Color                        | Icon Used                     | Purpose                                     |
| :---------------- | :--------------------------------------------- | :---------------------------- | :------------------------------------------ |
| `success`         | **Green** (`bg-green-50`, `text-green-800`)    | Checkmark Circle (Green)      | Successful operations (e.g. Profile Saved). |
| `error`           | **Red** (`bg-red-50`, `text-red-800`)          | X Circle (Red)                | Fatal exceptions, validation halts.         |
| `warning`         | **Yellow** (`bg-yellow-50`, `text-yellow-800`) | Exclamation Triangle (Yellow) | Cautionary warnings or required actions.    |
| `info`            | **Blue** (`bg-blue-50`, `text-blue-800`)       | Information Circle (Blue)     | General instructional alerts.               |

**Backend Usage:**

```php
return redirect()->back()->with('success', 'Profile updated!');
return redirect()->back()->with('error', 'Something went wrong.');
```

### `Modal.jsx` — Global Modal System

A context-driven modal controlled via `ModalContext`. Features:

- **ESC key** closes the modal.
- **Overlay click** closes by default (configurable).
- **Body scroll lock** when open.
- Fade-in + slide-up animations.

#### Modal Size Presets

| Size Prop | CSS Max Width       | Typical Use Case                               |
| :-------- | :------------------ | :--------------------------------------------- |
| `sm`      | `max-w-md` (28rem)  | Simple confirmation dialogs, quick edits.      |
| `md`      | `max-w-lg` (32rem)  | Standard forms, logins, standard promo cards.  |
| `lg`      | `max-w-2xl` (42rem) | Multi-column forms, detailed settings views.   |
| `xl`      | `max-w-4xl` (56rem) | Complex tables, document previews, dashboards. |
| `full`    | `w-full h-full`     | Immersive media viewers, full-screen wizards.  |

```javascript
import { useModal } from "@/Contexts/ModalContext";

const { openModal, closeModal } = useModal();

openModal(
    <div>
        <h2>Confirm Action</h2>
        <p>Are you sure you want to proceed?</p>
        <button
            onClick={() => {
                performAction();
                closeModal();
            }}
        >
            Confirm
        </button>
    </div>,
    { size: "lg", closeOnOverlay: false },
);
```

### `LoadingSpinner.jsx` — Global Loading Overlay

Full-screen spinner displayed during Inertia page transitions. Automatically managed by `MainLayout` via Inertia router events (`start` / `finish`).

### `Pagination.jsx` — Laravel Pagination Links

Renders Laravel's paginated response with responsive Previous/Next buttons and numbered page links. Works with Inertia `<Link>` components for SPA-style navigation.

```javascript
import Pagination from "@/Components/ui/Pagination";

<Pagination links={paginatedData} />;
```

### `SeoHead.jsx` — Dynamic SEO Meta Tags

Sets page `<title>`, `<meta description>`, and `<meta keywords>` via Inertia's `<Head>` component.

```javascript
import SeoHead from "@/Components/ui/SeoHead";

<SeoHead
    title="Dashboard"
    description="Manage your account and settings"
    keywords="dashboard, settings, profile"
/>;
```

### `BasicEditor.jsx` — Rich Text Editor (TipTap)

A toolbar-equipped rich text editor built on TipTap with:

- **Bold**, **Italic** formatting.
- **H1**, **H2**, **H3** headings with inline font-size styles.
- **Bullet List** and **Ordered List**.
- **Text Alignment**: Left, Center, Right, Justify.
- Inline styles applied directly to HTML output for email/CMS compatibility.
- Custom paragraph spacing for clean rendered output.

```javascript
import BasicEditor from "@/Components/ui/BasicEditor";

<BasicEditor value={htmlContent} onChange={(html) => setContent(html)} />;
```

### `PromoTemplates.jsx` — 5 Pre-Built Promotional Modal Templates

Ready-to-use marketing components that integrate with the ModalContext:

| Template            | Description                                                          |
| :------------------ | :------------------------------------------------------------------- |
| `ImagePromo`        | Full-width promotional image with title, description, and CTA button |
| `BannerPromo`       | Side-by-side image + text with feature checklist and CTA             |
| `CountdownPromo`    | Time-limited offer with live countdown timer                         |
| `EmailCapturePromo` | Lead generation form with email input and submit handler             |
| `GalleryPromo`      | Image carousel with prev/next navigation and counter                 |

```javascript
import { ImagePromo } from "@/Components/ui/PromoTemplates";

openModal(
    <ImagePromo
        imageUrl="/promo-banner.jpg"
        title="Summer Sale!"
        description="Get 50% off all courses"
        ctaText="Shop Now"
        ctaLink="/shop"
    />,
    { size: "lg" },
);
```

---

## 🧠 6. Context System

### `ModalContext.jsx` — Global Modal State

Provides `openModal()` and `closeModal()` functions to any component without prop-drilling.

```javascript
import { ModalProvider, useModal } from "@/Contexts/ModalContext";

// In your layout:
<ModalProvider>
    {children}
    <Modal />
</ModalProvider>;

// In any component:
const { openModal, closeModal } = useModal();

openModal(<MyContent />, {
    size: "md", // "sm" | "md" | "lg" | "xl" | "full"
    closeOnOverlay: true, // click backdrop to close?
    showCloseButton: true, // show X button?
});
```

---

## 🪝 7. Custom React Hooks Library

**Location:** `resources/js/Hooks/useInertia.js` — barrel exported via `@/Hooks`.

All hooks are built on Inertia's `usePage()` and provide clean access to shared server data.

### Authentication Hooks

| Hook                      | Returns       | Description                                                             |
| :------------------------ | :------------ | :---------------------------------------------------------------------- | -------------------------- |
| `useAuth()`               | `auth` object | Full auth data from server                                              |
| `useUser()`               | `User         | null`                                                                   | Current authenticated user |
| `useIsAuthenticated()`    | `boolean`     | Whether user is logged in                                               |
| `useIsGuest()`            | `boolean`     | Whether user is a guest                                                 |
| `useHasRole(roles)`       | `boolean`     | Check user role(s) — supports string, array, and `{name}` objects       |
| `useHasPermission(perms)` | `boolean`     | Check user permission(s) — supports string, array, and `{name}` objects |

```javascript
import { useUser, useHasRole } from "@/Hooks";

const user = useUser();
const isAdmin = useHasRole("admin");
const isAdminOrMod = useHasRole(["admin", "moderator"]);
```

### Flash Message Hooks

| Hook                   | Returns        | Description    |
| :--------------------- | :------------- | :------------- | ------------------------ |
| `useFlash()`           | `flash` object | All flash data |
| `useFlashMessage(key)` | `string        | null`          | Flash message by key     |
| `useFlashSuccess()`    | `string        | null`          | `flash.success` shortcut |
| `useFlashError()`      | `string        | null`          | `flash.error` shortcut   |
| `useFlashWarning()`    | `string        | null`          | `flash.warning` shortcut |
| `useFlashInfo()`       | `string        | null`          | `flash.info` shortcut    |

### Validation Error Hooks

| Hook                    | Returns    | Description                     |
| :---------------------- | :--------- | :------------------------------ | ----------------------------------- |
| `useErrors()`           | `object`   | All validation errors           |
| `useError(field)`       | `string    | null`                           | First error for a specific field    |
| `useHasErrors()`        | `boolean`  | Whether any errors exist        |
| `useFieldErrors(field)` | `string[]` | All errors for a specific field |
| `useFirstError(field?)` | `string    | null`                           | First error globally or for a field |

### Navigation & Config Hooks

| Hook                | Returns                                  | Description                                  |
| :------------------ | :--------------------------------------- | :------------------------------------------- |
| `useSharedProps()`  | `object`                                 | All shared Inertia props                     |
| `useRoute()`        | `{ current, component, props, version }` | Current route info                           |
| `useIsRoute(names)` | `boolean`                                | Check if current route matches given name(s) |
| `useAppConfig()`    | `object`                                 | Shared app config from server                |
| `useCsrfToken()`    | `string`                                 | CSRF token                                   |

---

## 🧰 8. JavaScript Utility Suite

**Location:** `resources/js/Utils/` — barrel exported via `@/Utils`.

```javascript
import {
    compressImage,
    slugify,
    formatDate,
    debounce,
    formatCurrency,
} from "@/Utils";
import storage from "@/Utils";
```

### 8.1 Image Compressor (`imageCompressor.js`)

Client-side image optimization before upload.

| Function                               | Description                                                                                                                |
| :------------------------------------- | :------------------------------------------------------------------------------------------------------------------------- |
| `compressImage(file, opts)`            | Compress and resize a single image. Options: `maxWidth` (1920), `maxHeight` (1080), `quality` (0.8), `type` ('image/jpeg') |
| `compressImages(files, opts)`          | Batch compress an array/FileList of images                                                                                 |
| `generateResponsiveImages(file, opts)` | Generate 3 sizes: small (640px), medium (1280px), large (1920px)                                                           |
| `getImageDimensions(file)`             | Get `{width, height}` from a File                                                                                          |
| `formatFileSize(bytes)`                | Convert bytes to human-readable format                                                                                     |

```javascript
const compressed = await compressImage(file, { maxWidth: 1200, quality: 0.7 });
const { small, medium, large } = await generateResponsiveImages(file);
```

### 8.2 Toast Utilities (`toast.js`)

Helpers for formatting Laravel validation errors.

| Function                         | Description                                             |
| :------------------------------- | :------------------------------------------------------ |
| `TOAST_TYPES`                    | Constants: `SUCCESS`, `ERROR`, `WARNING`, `INFO`        |
| `formatValidationErrors(errors)` | Flatten Inertia errors into a comma-separated string    |
| `hasErrors(errors, field?)`      | Check if errors exist (optionally for a specific field) |
| `getError(errors, field)`        | Get first error message for a field                     |

### 8.3 Storage (`storage.js`)

Type-safe localStorage wrapper with JSON auto-serialization and TTL support.

| Function                                        | Description                            |
| :---------------------------------------------- | :------------------------------------- |
| `storage.set(key, value)`                       | Store value (auto-stringifies objects) |
| `storage.get(key, default?)`                    | Retrieve value (auto-parses JSON)      |
| `storage.remove(key)`                           | Remove a key                           |
| `storage.clear()`                               | Clear all localStorage                 |
| `storage.setWithExpiry(key, value, ttlSeconds)` | Store with automatic expiration        |
| `storage.getWithExpiry(key, default?)`          | Retrieve only if not expired           |
| `storage.has(key)`                              | Check if key exists                    |
| `storage.keys()`                                | Get all storage keys                   |

```javascript
import storage from "@/Utils";

storage.set("user", { name: "John", role: "admin" });
storage.setWithExpiry("token", "abc123", 3600); // expires in 1 hour
const user = storage.get("user", {});
const token = storage.getWithExpiry("token");
```

### 8.4 String Utilities (`string.js`)

| Function                 | Description                                              |
| :----------------------- | :------------------------------------------------------- |
| `slugify(text)`          | Convert to URL-safe slug (`Hello World` → `hello-world`) |
| `truncate(text, length)` | Truncate with ellipsis (default: 100 chars)              |
| `capitalize(text)`       | Capitalize first letter of each word                     |
| `randomString(length)`   | Generate alphanumeric random string (default: 10 chars)  |

### 8.5 Date Utilities (`date.js`)

| Function                        | Description                                     |
| :------------------------------ | :---------------------------------------------- |
| `formatDate(date, locale?)`     | Format to readable date (`January 15, 2026`)    |
| `formatDateTime(date, locale?)` | Format with time (`January 15, 2026, 02:30 PM`) |
| `timeAgo(date)`                 | Relative time (`2 hours ago`, `just now`)       |

### 8.6 Number & Currency (`number.js`)

| Function                                    | Description                                                                                                 |
| :------------------------------------------ | :---------------------------------------------------------------------------------------------------------- |
| `formatNumber(value, locale?, decimals?)`   | Locale-formatted number (`1,234,567`)                                                                       |
| `formatCurrency(value, currency?, locale?)` | Formatted currency with symbol. Accepts names (`dollar`, `euro`, `taka`) or ISO codes (`USD`, `EUR`, `BDT`) |
| `formatPercent(value, decimals?)`           | Percentage string (`15%`, `15.67%`)                                                                         |
| `formatBytes(bytes, decimals?)`             | File size formatting (`1.5 KB`, `2.30 MB`)                                                                  |
| `compactNumber(value)`                      | Compact notation (`1.2K`, `1.5M`)                                                                           |

```javascript
formatCurrency(1234.56); // "$1,234.56"
formatCurrency(1234.56, "euro", "de-DE"); // "1.234,56 €"
formatCurrency(1234.56, "taka"); // "৳1,234.56"
compactNumber(1234567); // "1.2M"
```

### 8.7 Clipboard (`clipboard.js`)

| Function                        | Description                                           |
| :------------------------------ | :---------------------------------------------------- |
| `copyToClipboard(text)`         | Copy text (Clipboard API with `execCommand` fallback) |
| `copyWithToast(text, toastFn?)` | Copy and show notification                            |
| `readFromClipboard()`           | Read clipboard text (secure contexts only)            |

### 8.8 Performance (`performance.js`)

| Function                 | Description                                                                      |
| :----------------------- | :------------------------------------------------------------------------------- |
| `debounce(func, wait?)`  | Delay execution until idle (default: 300ms). For: search inputs, validation      |
| `throttle(func, limit?)` | Execute at most once per interval (default: 100ms). For: scroll, resize handlers |

```javascript
const handleSearch = debounce((query) => fetchResults(query), 300);
const handleScroll = throttle(() => updatePosition(), 100);
```

### 8.9 Validation (`validation.js`)

| Function / Export            | Description                                                         |
| :--------------------------- | :------------------------------------------------------------------ |
| `validatePhone(phone)`       | Validate Bangladeshi mobile numbers (`01[3-9]XXXXXXXX`)             |
| `validateEmail(email)`       | Basic email format validation                                       |
| `validatePassword(password)` | Strict validation: 8-15 chars, uppercase, lowercase, number, symbol |
| `passwordRequirements`       | Array of `{label, regex}` objects for live password strength UI     |

### 8.10 Web Vitals (`webVitals.js`)

Monitors Core Web Vitals (LCP, FID, CLS, FCP, TTFB) using the `web-vitals` library.

| Function              | Description                                                                                                      |
| :-------------------- | :--------------------------------------------------------------------------------------------------------------- |
| `initWebVitals(opts)` | Start monitoring. Options: `sendToServer` (POST to `/api/analytics/vitals`), `logToConsole`, `onMetric` callback |
| `getWebVitals()`      | Get a snapshot of all current vitals (Promise)                                                                   |

```javascript
// In app.jsx or main entry
import { initWebVitals } from "@/Utils/webVitals";

if (import.meta.env.PROD) {
    initWebVitals({ sendToServer: true });
} else {
    initWebVitals({ logToConsole: true });
}
```

---

## 📬 9. Modern Mailing System

Stop writing repetitive mailable classes. Feature Kit provides a **Universal Mailable Engine**.

### `GeneralMail.php`

A single queued mailable (`implements ShouldQueue`) that handles every email:

```php
namespace App\Mail;

class GeneralMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,    // Email subject line
        public string $contentView,    // Blade view for email body
        public array $data = []        // Data passed to the view
    ) {}
}
```

**Usage:**

```php
use App\Mail\GeneralMail;

Mail::to($user)->queue(new GeneralMail(
    mailSubject: 'Your Order Has Shipped',
    contentView: 'emails.orders.shipped-body',
    data: [
        'title'      => 'Order Shipped!',
        'body'       => 'Your order #1234 is on its way.',
        'actionText' => 'Track Order',
        'actionUrl'  => 'https://example.com/track/1234',
    ]
));
```

### Email Layout (`emails/layout.blade.php`)

Every email rendered via `GeneralMail` automatically wraps in a professional Markdown template:

- **Header**: Logo image + app name.
- **Content Area**: White card with rounded corners — renders the `$content_view`.
- **Footer**: Copyright, year, optional unsubscribe link, and configurable address.

### Email Templates Included

| Template        | Location                                     | Purpose                                                                  |
| :-------------- | :------------------------------------------- | :----------------------------------------------------------------------- |
| Master Layout   | `emails/layout.blade.php`                    | Wraps all GeneralMail content                                            |
| General Body    | `emails/general.blade.php`                   | Generic notification with title, body, optional CTA button               |
| Forgot Password | `emails/auth/forgot-password-body.blade.php` | Password reset email with styled button, security note, and fallback URL |

---

## 🔑 10. Auth Role

Feature Kit includes a complete authentication system organized in `app/Http/Controllers/Auth`, `app/Services/Auth`, and `app/Http/Requests/Auth`.

- **Controllers**: `LoginController`, `RegisterController`, `ForgotPasswordController`.
- **Services**: `LoginService`, `RegisterService`, `ForgotPasswordService`.
- **Requests**: `LoginRequest`, `RegisterRequest`.
- **Views**: Standard Blade views in `resources/views/pages/auth/`.
- **Routes**: Explicitly defined in `routes/web.php`.

### Controllers

| Controller                 | Methods                                           | Purpose                                    |
| :------------------------- | :------------------------------------------------ | :----------------------------------------- |
| `LoginController`          | `index()`, `login()`                              | Renders login page, handles authentication |
| `RegisterController`       | `index()`, `register()`                           | Renders registration page, creates user    |
| `ForgotPasswordController` | `index()`, `send()`, `showResetForm()`, `reset()` | Full forgot/reset password flow            |

- `LoginController`: Handles session creation and termination.
- `RegisterController`: Handles new account creation.
- `ForgotPasswordController`: Handles password reset flow.

### Services

| Service                 | Methods                                              | Purpose                                                                                             |
| :---------------------- | :--------------------------------------------------- | :-------------------------------------------------------------------------------------------------- |
| `LoginService`          | `attempt(credentials, remember)`                     | Authenticates user, regenerates session, throws `ValidationException` on failure                    |
| `RegisterService`       | `register(data)`                                     | Creates user with hashed password, auto-logs in                                                     |
| `ForgotPasswordService` | `sendResetLink(email)`, `resetPassword(credentials)` | Generates reset token, sends email via `GeneralMail`, resets password via Laravel's Password broker |

### Form Requests

| Request           | Validation Rules                                                                                                 |
| :---------------- | :--------------------------------------------------------------------------------------------------------------- |
| `LoginRequest`    | `email` (required, email), `password` (required), `remember` (boolean)                                           |
| `RegisterRequest` | `name` (max:255), `email` (unique:users), `phone` (max:20), `password` (confirmed, defaults), `terms` (accepted) |

### Models

The `User` model lives in `app/Models/User.php`. It is the central authority for authentication and authorization.

### Routes

```
GET    /auth/login             → LoginController@index         (auth.login.index)
POST   /auth/login             → LoginController@login         (auth.login.login)
GET    /auth/register          → RegisterController@index      (auth.register.index)
POST   /auth/register          → RegisterController@register   (auth.register.register)
GET    /auth/forgot-password   → ForgotPasswordController@index(auth.forgot-password.index)
POST   /auth/forgot-password   → ForgotPasswordController@send (auth.forgot-password.send)
GET    /auth/reset-password/{t}→ ForgotPasswordController@showResetForm (password.reset)
POST   /auth/reset-password    → ForgotPasswordController@reset(password.update)
```

### Auth Blade Views

- **`pages/auth/layout.blade.php`** — Split-screen auth layout: brand panel (left) + form area (right). Includes logo fallback, dot-grid background pattern, and loading spinner.
- **`pages/auth/login/page.blade.php`** — Login form with SEO meta.
- **`pages/auth/register/page.blade.php`** — Registration form.
- **`pages/auth/forgot-password/page.blade.php`** — Forgot password form.
- **`pages/auth/reset-password/page.blade.php`** — Reset password form with token.

---

## 🏠 11. Landing Feature

### Controller

`LandingController` serves three Blade pages:

| Route                | Method       | View                                          |
| :------------------- | :----------- | :-------------------------------------------- |
| `GET /`              | `index()`    | `pages.home.page` (Landing homepage)          |
| `GET /documentation` | `docs()`     | `pages.home.docs` (Interactive documentation) |
| `GET /features`      | `features()` | `pages.home.features` (Feature comparison)    |

### Landing Pages

**Home (`page.blade.php`):**

- Hero section with CTA buttons (Get Started → register, Documentation → docs).
- 6-card feature grid: Feature-Driven Design, Next.js Style Structure, Global Mail System, Utility Suite, Artisan Superpowers, Tailwind CSS v4.

**Documentation (`docs.blade.php`):**

- Sticky sidebar navigation (Installation, Architecture, Frontend Strategy, Artisan Commands).
- Sections: Introduction & Setup (prerequisites, quick install), Mastering Domains (thin controllers, brainy services with code examples), Frontend Architecture (Blade for performance, React 19 dashboard), CLI Scaffolders table.

**Features (`features.blade.php`):**

- Side-by-side comparison: Traditional Laravel (❌) vs Feature Kit Design (✅).
- Animated code preview showing folder structure.
- 3-pillar grid: Isolation, DX First, Reliability.

---



## 🎨 13. Tailwind CSS v4 Design System

**Location:** `resources/css/app.css`

Uses Tailwind v4's `@theme` directive for semantic design tokens with OKLCH color space:

### Design Tokens

```css
@theme {
    /* Primary Theme (Indigo) */
    --color-primary: oklch(0.637 0.191 278.358);
    --color-primary-foreground: white;
    --color-primary-surface: oklch(0.962 0.018 272.314);
    --color-primary-surface-foreground: oklch(0.368 0.11 277.394);

    /* Secondary Theme (Cyan) */
    --color-secondary: oklch(0.72 0.17 236.62);
    --color-secondary-foreground: white;
    --color-secondary-surface: oklch(0.977 0.013 236.62);
    --color-secondary-surface-foreground: oklch(0.46 0.09 236.62);

    /* Surface & Content */
    --color-surface: oklch(0.985 0 0);
    --color-surface-foreground: oklch(0.2 0 0);
    --color-surface-muted: oklch(0.92 0 0);
    --color-surface-muted-foreground: oklch(0.4 0 0);

    /* Status Colors */
    --color-error: oklch(0.6 0.2 25);
    --color-success: oklch(0.6 0.2 145);

    /* System */
    --color-border: oklch(0.92 0 0);
    --color-ring: oklch(0.637 0.191 278.358 / 0.5);

    /* Typography */
    --font-sans: "Instrument Sans", ui-sans-serif, system-ui, sans-serif, ...;
}
```

### White-Labeling

To rebrand the app, simply update the OKLCH values in `app.css`:

```css
--color-primary: oklch(0.65 0.2 150); /* Change indigo → green */
```

Every component using `bg-primary`, `text-primary`, etc. updates automatically.

### Content Sources

```css
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';
```

---

## 🔧 14. Dev Environment & Scripts

### Composer Scripts

| Command          | What It Does                                                                                                                   |
| :--------------- | :----------------------------------------------------------------------------------------------------------------------------- |
| `composer setup` | Full project setup: install PHP deps, copy `.env`, generate key, migrate DB, install node deps, build assets                   |
| `composer dev`   | Starts 4 processes concurrently: `php artisan serve` (server), `queue:listen` (queue), `pail` (logs), `npm run dev` (Vite HMR) |
| `composer test`  | Clears config cache and runs PHPUnit test suite                                                                                |

### NPM Scripts

| Command         | What It Does                           |
| :-------------- | :------------------------------------- |
| `npm run dev`   | Start Vite development server with HMR |
| `npm run build` | Production build                       |

### Vite Configuration

```javascript
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.jsx"],
            refresh: ["resources/**", "app/Features/**"], // HMR on feature changes
        }),
        tailwindcss(),
        react(),
    ],
    resolve: {
        alias: { "@": "/resources/js" }, // @/ import alias
    },
});
```

### Environment

- **Default DB:** SQLite (`database/database.sqlite`)
- **Default Queue:** Database driver
- **Default Mail:** Log driver (switch to SMTP in production)
- **Session:** Database-backed

---

## 🗄️ 15. Database & Migrations

### Default Migrations

| Migration            | Tables Created                                                                                                                 |
| :------------------- | :----------------------------------------------------------------------------------------------------------------------------- |
| `create_users_table` | `users` (id, name, email, phone, email_verified_at, password, remember_token, timestamps), `password_reset_tokens`, `sessions` |
| `create_cache_table` | `cache`, `cache_locks`                                                                                                         |
| `create_jobs_table`  | `jobs`, `job_batches`, `failed_jobs`                                                                                           |

### Users Table Schema

```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
name            VARCHAR(255)
email           VARCHAR(255) UNIQUE
phone           VARCHAR(255) NULLABLE
email_verified_at TIMESTAMP NULLABLE
password        VARCHAR(255)
remember_token  VARCHAR(100) NULLABLE
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

---

## 🗺️ 16. Quick Reference Table

| Layer               | Responsibility                                                     | Location                                  |
| :------------------ | :----------------------------------------------------------------- | :---------------------------------------- |
| **Route Discovery** | Automatic scanning & registration                                  | `bootstrap/app.php`                       |
| **Base Classes**    | Controller, Service, Exception foundations                         | `app/Core/`                               |
| **API Responses**   | Standardized JSON format                                           | `app/Core/Traits/ApiResponseTrait.php`    |
| **Feature Logic**   | Domain controllers + services                                      | `app/Features/*/Controllers` & `Services` |
| **Feature Models**  | Domain-specific Eloquent models                                    | `app/Features/*/Models`                   |
| **Validation**      | Domain form requests                                               | `app/Features/*/Requests`                 |
| **Email Engine**    | Universal queued mailable                                          | `app/Mail/GeneralMail.php`                |
| **Email Layout**    | Professional Markdown master template                              | `resources/views/emails/layout.blade.php` |
| **Blade Pages**     | SEO landing, auth, docs                                            | `resources/views/pages/`                  |
| **React SPA**       | Inertia portal pages                                               | `resources/js/pages/(portals)/`           |
| **React UI Kit**    | Toast, Modal, Spinner, Pagination, Editor, SeoHead, PromoTemplates | `resources/js/Components/ui/`             |
| **React Layouts**   | MainLayout, AdminLayout, UserLayout                                | `resources/js/Components/Layout/`         |
| **Global State**    | Modal context system                                               | `resources/js/Contexts/ModalContext.jsx`  |
| **Custom Hooks**    | 20+ Inertia accessor hooks                                         | `resources/js/Hooks/useInertia.js`        |
| **JS Utilities**    | 11 utility modules                                                 | `resources/js/Utils/`                     |
| **Design Tokens**   | Tailwind v4 @theme                                                 | `resources/css/app.css`                   |
| **Administrative Command** | Cleanup of traffic logs                | `app/Console/Commands/CleanupTrafficLogs.php` |

---

## ✉️ 17. Universal Mailing System

Stop writing repetitive mailable classes. One `GeneralMail` class handles every email in your app — queued by default.

```php
Mail::to($user)->queue(new GeneralMail(
    mailSubject: 'Your Order Has Shipped',
    contentView: 'emails.orders.shipped-body',
    data: [
        'title'      => 'Order Shipped!',
        'body'       => 'Your order #1234 is on its way.',
        'actionText' => 'Track Order',
        'actionUrl'  => 'https://example.com/track/1234',
    ]
));
```

#### GeneralMail Constructor Map

| Parameter     | Type     | Required | Description                                                         |
| :------------ | :------- | :------- | :------------------------------------------------------------------ |
| `mailSubject` | `string` | **Yes**  | Sets the exact email subject line.                                  |
| `contentView` | `string` | **Yes**  | The exact dot-notation Blade path for the template to render.       |
| `data`        | `array`  | No       | An array of dynamic values injected into the Blade template layout. |

---

## 🔔 18. Unified Notification System

Feature Kit uses a **Event-Driven Unified Notification System** that decouple features from the notification logic.

### `NotificationCreated` Event

A single, global event (`App\Events\NotificationCreated`) is used to trigger notifications across the entire application.

```php
use App\Events\NotificationCreated;

// Dispatch from any Service or Controller
event(new NotificationCreated(
    user: $user,
    category: 'Security',
    title: 'Password Changed',
    message: 'Your account password has been updated.'
));
```

### `CreateNotificationRecord` Listener

The listener (`App\Listeners\CreateNotificationRecord`) is automatically registered in `AppServiceProvider`. It handles background creation of notification records in the database.

### Creating New Events

To create a new event, use the standard Artisan command:

```bash
php artisan make:event {EventName}
```

---

---

## 🏗️ 19. Advanced Development Patterns

### Secure Private Storage & Modular Delivery

Feature Kit implements a secure pattern for sensitive files like profile images:

1. **Private Storage**: Files are stored in `storage/app/private/` (not publicly accessible via URL).
2. **Modular Controller**: Dedicated `ProfileImageController` classes (one for Admin, one for User) serve these images.
3. **Role-Aware Routing**: Secure routes (e.g., `/admin/profile-image`) ensure users can only access authorized assets.

### Consolidated Models

Instead of duplicating models across domains, Feature Kit uses a centralized `app/Models` directory. This ensures that models like `User`, `Notification`, and `ActivityLog` have a single source of truth for relationships and logic.

---

## 📈 20. Activity Logs System

Feature Kit provides a highly extensible, **Event-Driven Activity Logging System** that captures and stores user interactions automatically.

### The `ActivityLogged` Event & `CreateActivityLogRecord` Listener

The system uses a synchronous event-listener combination to guarantee log capture.

- **Event**: `App\Events\ActivityLogged`.
- **Listener**: `App\Listeners\CreateActivityLogRecord`.
- **Model**: `App\Models\ActivityLog`.

```php
use App\Events\ActivityLogged;

// Dispatch from any Service
event(new ActivityLogged(
    user: $auth_user,
    action: 'login',
    description: 'User logged in successfully.'
));
```

The listener captures:
- `user_id`
- The `action` keyword
- Detailed `description`
- `ip_address` and `user_agent` (automatically resolved)

### Flexible UI Badges

The Activity Logs UI uses the `getActionMeta` utility on the frontend to map keywords to visually distinct badges (Green for `create`, Blue for `update`, Red for `delete`, etc.).

---

## 🏛️ 21. Core Administrative Hubs

Feature Kit provides 4 scaffolded administration points, organized into role-based controllers and powered by thick services.

### 👥 1. User Management

Managed via `UserController` and `UserService` in the `Admin` namespace.

- **Live Pagination & Search**: Synchronized UI parameters with Inertia queries.
- **Secure Provisioning**: Isolated "Add User" modal with `StoreUserRequest`.
- **Role & Access Interceptors**: Manage `is_active` and `role` flags.
- **Automatic Audit Trails**: `UserService` fires `ActivityLogged` events for status changes.

### ⚡ 2. UI Cache Management

Exposes Artisan commands via `CacheController` and `CacheService`.

- Safely flush `cache`, `route`, `config`, `view`, or `optimize`.

### ❤️ 3. System Health Monitor

A "Live Metrics" dashboard analyzed via `HealthController` and `HealthStatusService`.

- **Checks**: PDO SQL Integrity, Caching Driver Latency, Hardware Config (memory limits), and Stack Diagnostics.

### 📊 4. Professional Dashboards

Logic is split into `AdminDashboardService` and `UserDashboardService`.

- **Admin Dashboard**: System-wide performance, user counts, and global activity.
- **User Dashboard**: Personalized welcome hub, profile completion health, and private activity feed.

### ⚙️ 5. System Settings & Branding

Managed via `SettingsController` and `SettingsService`.

- **Dynamic Branding**: Update App Name, Logo, and Favicon instantly.
- **Favicon Engine**: `FaviconUtil` for ICO conversion.
- **Maintenance Mode**: `CheckMaintenanceMode` middleware with Admin bypass and Inertia support.

Usage:
```php
\App\Models\Setting::get('maintenance_duration', '15 mins');
```

---

### Developed and Maintained by [Rifatxtra](https://rifatxtra.com).

MIT Licensed. Open for everyone to scale.

---

## 📊 22. Traffic Analytics Console

The Traffic Analytics system is a built-in, **full-stack analytics console** — a self-hosted alternative to Google Analytics.

- **Middleware**: `App\Http\Middleware\TrackTraffic`.
- **Job**: `App\Jobs\ProcessTrafficLog`.
- **Model**: `App\Models\TrafficLog`.
- **Service**: `App\Services\Admin\TrafficAnalyticsService`.
- **Controller**: `App\Http\Controllers\Admin\TrafficController`.

### Logic Flow

1. **TrackTraffic Middleware**: Intercepts requests, captures timing & IP, and dispatches the background job.
2. **ProcessTrafficLog Job**: Runs async to perform geo lookup, UA parsing, and database insertion.
3. **TrafficAnalyticsService**: Aggregates the log data into 16+ metrics for the dashboard.
4. **TrafficController**: Serves the dashboard UI, paginated logs, and real-time polling data.

### Database Schema (`traffic_logs` table)

```sql
id              BIGINT UNSIGNED AUTO_INCREMENT
user_id         BIGINT NULLABLE (FK → users)
session_id      VARCHAR(64) NULLABLE INDEX       -- sha256(IP|UA|date)
ip_address      VARCHAR(45)
uri             VARCHAR(2048)
method          VARCHAR(10)
status_code     SMALLINT DEFAULT 200 INDEX
response_time   FLOAT NULLABLE                   -- milliseconds
user_agent      TEXT NULLABLE
browser         VARCHAR(100) NULLABLE
os              VARCHAR(100) NULLABLE
device_type     VARCHAR(50) NULLABLE
referrer        TEXT NULLABLE
country_code    CHAR(2) NULLABLE INDEX
country_name    VARCHAR(100) NULLABLE
is_bot          BOOLEAN DEFAULT false
is_new_visitor  BOOLEAN DEFAULT true
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### Middleware: `TrackTraffic`

Appended to the global `web` stack in `bootstrap/app.php`. Runs on every public request:

1. Records `microtime(true)` before the request is processed.
2. Passes the request through the rest of the middleware chain.
3. Calculates `response_time` in milliseconds.
4. Skips admin/api/ajax routes (only public page views are tracked).
5. Resolves the **real visitor IP** — respects Cloudflare `CF-Connecting-IP`, nginx `X-Real-IP`, and `X-Forwarded-For` headers via Laravel's `trustProxies(at: '*')`.
6. Builds a **privacy-safe session ID** by hashing `IP | UserAgent | date` — no cookies required.
7. Dispatches `ProcessTrafficLog` to the queue for async processing.

```php
// bootstrap/app.php — required for correct IP detection behind proxies
$middleware->trustProxies(at: '*');
```

### Job: `ProcessTrafficLog`

Runs in the background queue. Performs:

- **Geo lookup** via `http://ip-api.com/json/{ip}` (free, no API key, 45 req/min) — skips private/loopback IPs gracefully.
- **New visitor detection** — checks if the IP has any prior records before today.
- **Extended UA parsing** — detects Chrome, Firefox, Safari, Edge, Brave, Opera, Samsung Browser, UC Browser; Windows 10/11, macOS, iOS, Android, Linux, Chrome OS.
- **Bot detection** — checks 20+ bot signatures including Googlebot, Bingbot, HeadlessChrome, PhantomJS.

```php
// Dispatched from middleware
ProcessTrafficLog::dispatch([
    'ip_address'    => $ip,
    'session_id'    => $sessionId,
    'status_code'   => $statusCode,
    'response_time' => $responseTime,
    // ...
]);
```

### Service: `TrafficAnalyticsService`

Contains all aggregation logic. The `getDashboardStats()` method returns 16 data sets:

| Method / Data Key          | Description                                                         |
| :------------------------- | :------------------------------------------------------------------ |
| `visits_over_time`         | Daily page views, unique IPs, and sessions over the selected period |
| `device_distribution`      | Desktop / Mobile / Tablet breakdown                                 |
| `browser_distribution`     | Top 8 browsers by visit count                                       |
| `os_distribution`          | Top 8 OS platforms by visit count                                   |
| `top_pages`                | Top 10 most-visited URIs                                            |
| `referrer_sources`         | Direct / Search / Social / Other categorized traffic                |
| `heatmap`                  | 7×24 array — hit count per day-of-week × hour-of-day               |
| `status_codes`             | 2xx / 3xx / 4xx / 5xx grouped HTTP status distribution              |
| `geo_breakdown`            | Top 15 countries by visit count                                     |
| `top_entry_pages`          | Top 5 pages by distinct session count                               |
| `response_time_trend`      | Daily avg & max response time in milliseconds                       |
| `recent_logs`              | Latest 20 visits with user relationship loaded                      |
| `summary.bounce_rate`      | % of sessions with only 1 page view                                 |
| `summary.avg_pages_session`| Average depth of each session                                       |
| `summary.new_visitors`     | Distinct IPs flagged as first-time today                            |
| `summary.avg_response_time`| Mean response time across all tracked requests                      |

The separate `getRealTimeStats(int $minutes)` method is called by the REST polling endpoint:

```php
// Returns active visitors, per-minute sparkline, active pages, countries, hit stream
$data = $this->trafficService->getRealTimeStats(minutes: 5);
```

### Controller Routes

```
GET /admin/traffic              → TrafficController@index    (dashboard)
GET /admin/traffic/logs         → TrafficController@logs     (paginated log viewer)
GET /admin/traffic/realtime     → TrafficController@realtime (JSON — REST poll)
```

### Dashboard UI: 3-Tab Console

**Tab 1 — Overview**
- KPI row: Page Views, Unique IPs, Sessions, Avg Response, 4xx Errors, 5xx Errors
- Secondary metrics: Bounce Rate, Pages/Session, New Visitors, Bot Traffic
- Visitor Trends area chart (page views + sessions)
- Device donut chart
- New vs Returning visitor donut
- Traffic Sources progress bars (Direct / Search / Social / Other)
- HTTP Status Code breakdown
- Browser usage horizontal bar chart
- Top Pages ranked list
- Recent Hits table with inline detail modal

**Tab 2 — Real-Time** *(polls every 15 seconds via `fetch`)*
- Hero: live Active Visitors count with pulsing indicator
- Req/sec, Pages Live, Active Countries KPIs
- 30-minute traffic sparkline chart
- Pages Being Viewed list
- Active Countries list with flag emojis
- Live Hit Stream table (IP, country, browser, status, timestamp)

**Tab 3 — Behavior**
- Response Time Trend line chart (avg + max per day)
- 7×24 Traffic Heatmap (intensity grid, color-coded)
- OS Distribution progress bars
- Top Countries ranked list with flag emojis and bar indicator
- Top Entry Pages (by session count)

### Real-Time Polling Strategy

To avoid the overhead of WebSockets or Server-Sent Events, the Real-Time tab uses **REST polling**:

```javascript
// In page.jsx — starts when Real-Time tab is active, clears on tab switch
useEffect(() => {
    if (activeTab === 'realtime') {
        fetchRealtime(); // immediate first load
        rtIntervalRef.current = setInterval(fetchRealtime, 15000); // then every 15s
    } else {
        clearInterval(rtIntervalRef.current);
    }
    return () => clearInterval(rtIntervalRef.current);
}, [activeTab]);
```

### Filtering

Both the dashboard and logs viewer support URL-parameter based filtering:

| Filter       | Effect                                                  |
| :----------- | :------------------------------------------------------ |
| `days`       | Date range: 1, 7, 30, 90, or 0 (All Time)              |
| `uri`        | Filter by URI path substring                            |
| `is_bot`     | Include or exclude detected bots                        |
| `device_type`| Filter logs by Desktop / Mobile / Tablet (logs only)   |
| `status_code`| Filter logs by exact HTTP status code (logs only)       |
| `country_code` | Filter logs by ISO country code (logs only)           |

### TrafficLog Model Scopes

```php
TrafficLog::realUsers()          // excludes is_bot = true
TrafficLog::newVisitors()        // is_new_visitor = true
TrafficLog::returningVisitors()  // is_new_visitor = false
TrafficLog::successful()         // status_code 200–299
TrafficLog::errors()             // status_code >= 400
```
