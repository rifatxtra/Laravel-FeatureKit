# Laravel FeatureKit

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/React-19-61DAFB?style=for-the-badge&logo=react" alt="React 19">
  <img src="https://img.shields.io/badge/Inertia.js-2-9553E9?style=for-the-badge" alt="Inertia.js">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS 4">
</p>

A modern Laravel starter template built with **feature-based architecture**, **Inertia.js**, and **React**. Perfect for developers who want to organize their application by features, not layers.

---

## 📋 Table of Contents

- [What's Included](#whats-included)
- [Feature-Based Architecture](#feature-based-architecture)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Artisan Commands](#artisan-commands)
- [React Hooks](#react-hooks)
- [API Support](#api-support)
- [Tailwind CSS Customization](#tailwind-css-customization)
- [SEO Configuration](#seo-configuration)
- [Database Migrations](#database-migrations)
- [Authentication](#authentication)
- [Directory Structure](#directory-structure)
- [Examples](#examples)
- [Contributing](#contributing)
- [License](#license)

---

## ✨ What's Included

- ⚡ **Laravel 12** - Latest Laravel framework with modern PHP 8.2+
- ⚛️ **React 19** + **Inertia.js 2** - Build SPAs without building an API
- 🎨 **Tailwind CSS 4** - Utility-first CSS with new @theme directive
- 📦 **Feature-Based Architecture** - Organize by features, not MVC layers
- 🔍 **SEO Ready** - Meta tags, Open Graph, Twitter Cards pre-configured
- 🚀 **Vite** - Lightning-fast frontend tooling with HMR
- 🗄️ **SQLite** - Zero-config database (easily switchable)
- 🎣 **Custom React Hooks** - Pre-built hooks for auth, flash messages, errors
- 🛠️ **Artisan Commands** - Generate features, controllers, models with CLI
- 📱 **API Support** - Optional RESTful API routes per feature
- 🔐 **Shared Inertia Data** - Auto-share auth, flash, errors across all pages

---

## 📦 Feature-Based Architecture

### Why Feature-Based?

Traditional Laravel apps organize code by **technical layers** (Controllers, Models, etc.). FeatureKit organizes by **features** (Blog, Products, Orders, etc.).

**Traditional Structure:**
```
app/
├── Http/Controllers/
│   ├── BlogController.php
│   ├── ProductController.php
│   └── OrderController.php
├── Models/
│   ├── Post.php
│   ├── Product.php
│   └── Order.php
```

**FeatureKit Structure:**
```
app/Features/
├── Blog/
│   ├── Controllers/BlogController.php
│   ├── Models/Post.php
│   ├── Requests/
│   ├── Services/
│   └── web.php
├── Products/
│   ├── Controllers/ProductController.php
│   ├── Models/Product.php
│   └── web.php
└── Orders/
    ├── Controllers/OrderController.php
    ├── Models/Order.php
    └── web.php
```

### Benefits

- ✅ **Clear boundaries** - Each feature is self-contained
- ✅ **Easy navigation** - Find all Blog code in one place
- ✅ **Team collaboration** - Different teams work on different features
- ✅ **Reusability** - Extract features into packages easily
- ✅ **Reduced conflicts** - Teams don't touch same files
- ✅ **Better testing** - Test features independently

---

## 🚀 Installation

### Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite, MySQL, or PostgreSQL

### Create New Project

```bash
composer create-project rifatxtra/laravel-featurekit my-app
cd my-app
```

### Initial Setup

```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Install frontend dependencies
npm install
```

---

## ⚡ Quick Start

### Start Development Server

**Option 1: All-in-one command** (recommended)
```bash
composer dev
```
This runs: Laravel server, queue worker, logs viewer, and Vite dev server.

**Option 2: Separately**
```bash
# Terminal 1: Backend
php artisan serve

# Terminal 2: Frontend
npm run dev
```

Visit: `http://localhost:8000`

### Create Your First Feature

```bash
# Generate a complete feature
php artisan make:feature Blog

# With API support
php artisan make:feature Products --api
```

This creates:
- Feature directory structure
- Controller with example code
- Route file (web.php)
- React page component
- API routes and controller (if --api flag used)

---

## 🛠️ Artisan Commands

FeatureKit includes powerful commands to speed up development:

### `make:feature`

Create a complete feature with all necessary files.

```bash
# Basic feature
php artisan make:feature Blog

# With API support
php artisan make:feature Products --api

# Force overwrite existing
php artisan make:feature Blog --force
```

**Creates:**
- `app/Features/Blog/Controllers/BlogController.php`
- `app/Features/Blog/Models/`
- `app/Features/Blog/Requests/`
- `app/Features/Blog/Services/`
- `app/Features/Blog/web.php`
- `resources/js/Pages/Blog/Index.jsx`
- (Optional) `app/Features/Blog/api.php`
- (Optional) `app/Features/Blog/Controllers/BlogApiController.php`

### `make:feature-controller`

Add a controller to an existing feature.

```bash
php artisan make:feature-controller Blog PostController
```

### `make:feature-model`

Add a model to an existing feature.

```bash
# Without migration
php artisan make:feature-model Blog Post

# With migration
php artisan make:feature-model Blog Post --migration
```

### `make:feature-page`

Create a React page component for a feature.

```bash
php artisan make:feature-page Blog Create
```

Creates: `resources/js/Pages/Blog/Create.jsx`

### `feature:list`

List all features with statistics.

```bash
php artisan feature:list
```

Output:
```
┌──────────┬─────────────┬─────────────┬─────────────┬────────┐
│ Feature  │ Web Routes  │ API Routes  │ Controllers │ Models │
├──────────┼─────────────┼─────────────┼─────────────┼────────┤
│ Blog     │ ✓           │ ✓           │ 2           │ 1      │
│ Products │ ✓           │ ✗           │ 1           │ 1      │
└──────────┴─────────────┴─────────────┴─────────────┴────────┘
```

---

## 🎣 React Hooks

FeatureKit provides pre-built React hooks for common tasks.

### Authentication Hooks

```jsx
import { 
    useAuth, 
    useUser, 
    useIsAuthenticated,
    useHasRole,
    useHasPermission 
} from '@/hooks/useSharedProps';

function MyComponent() {
    const user = useUser();
    const isAuthenticated = useIsAuthenticated();
    const isAdmin = useHasRole('admin');
    const canEdit = useHasPermission('posts.edit');

    return (
        <div>
            {isAuthenticated && <p>Welcome, {user.name}!</p>}
            {isAdmin && <AdminPanel />}
            {canEdit && <EditButton />}
        </div>
    );
}
```

### Flash Message Hooks

Helpers to read flash data shared via Inertia (success, error, warning, info, or any custom key).

```jsx
import { 
    useFlash,
    useFlashMessage,
    useFlashSuccess,
    useFlashError,
    useFlashWarning,
    useFlashInfo,
} from '@/hooks/useSharedProps';

function MyComponent() {
    const flash = useFlash();
    const success = useFlashSuccess();
    const error = useFlashError();
    const info = useFlashMessage('custom');

    return (
        <>
            {success && <div className="alert-success">{success}</div>}
            {error && <div className="alert-error">{error}</div>}
            {info && <div className="alert-info">{info}</div>}
            {flash.warning && <div className="alert-warning">{flash.warning}</div>}
        </>
    );
}
```

#### FlashToasts component

Flash toasts are rendered via the default layout so they have access to Inertia context. See `resources/js/Layouts/AppLayout.jsx`:

```jsx
import FlashToasts from '@/Components/FlashToasts';

export default function AppLayout({ children }) {
    return (
        <>
            <FlashToasts />
            {children}
        </>
    );
}
```

### Validation Error Hooks

Helpers to inspect validation errors from Inertia responses: per-field arrays, first error, or overall state.

```jsx
import { 
    useError,
    useHasErrors,
    useFieldErrors,
    useFirstError,
} from '@/hooks/useSharedProps';

function MyForm() {
    const emailErrors = useFieldErrors('email');
    const firstError = useFirstError();
    const emailError = useError('email');
    const hasErrors = useHasErrors();

    return (
        <form>
            <input type="email" name="email" />
            {emailErrors.map((msg) => (
                <div key={msg} className="text-red-500 text-sm">{msg}</div>
            ))}

            {/* Or just the first error */}
            {emailError && <span className="text-red-500">{emailError}</span>}
            
            {hasErrors && <p className="text-sm text-red-600">{firstError}</p>}
        </form>
    );
}
```

#### Accessibility: busy state on forms

Submit buttons use `aria-busy` when `processing` is true, so screen readers announce that the action is in progress. Pair this with a loading label (e.g., “Sending...”) for clear feedback.

### Available Hooks

| Hook | Description |
|------|-------------|
| `useAuth()` | Get auth object with user and check status |
| `useUser()` | Get authenticated user directly |
| `useIsAuthenticated()` | Check if user is logged in (boolean) |
| `useIsGuest()` | Check if user is guest (boolean) |
| `useHasRole(role)` | Check if user has specific role |
| `useHasPermission(permission)` | Check if user has permission |
| `useFlash()` | Get all flash messages |
| `useErrors()` | Get all validation errors |
| `useError(field)` | Get error for specific field |
| `useHasErrors()` | Check if any errors exist |
| `useAppConfig()` | Get app configuration |
| `useCsrfToken()` | Get CSRF token |

📖 **Full Documentation:** See [HOOKS.md](HOOKS.md)

---

## 🌐 API Support

Create RESTful APIs within your features.

### Generate Feature with API

```bash
php artisan make:feature Products --api
```

This creates `app/Features/Products/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Features\Products\Controllers\ProductsApiController;

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductsApiController::class, 'index']);
    Route::post('/', [ProductsApiController::class, 'store']);
    Route::get('/{id}', [ProductsApiController::class, 'show']);
    Route::put('/{id}', [ProductsApiController::class, 'update']);
    Route::delete('/{id}', [ProductsApiController::class, 'destroy']);
});
```

### API Controller Example

```php
<?php

namespace App\Features\Products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => Product::all(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }
}
```

### API Routes

All feature API routes are automatically registered under `/api` prefix:

- `GET /api/products` - List products
- `POST /api/products` - Create product
- `GET /api/products/{id}` - Get product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

---

## 🎨 Tailwind CSS Customization

FeatureKit uses **Tailwind CSS 4** with the new `@theme` directive for customization.

### Customize Theme

Edit `resources/css/app.css` to extend Tailwind's theme:

```css
@import 'tailwindcss';

/* Configure source scanning for feature-based architecture */
@source '../../app/Features/**/*.blade.php';
@source "../**/*.blade.php";
@source "../**/*.js";
@source "../**/*.jsx";

@theme {
    /* Custom Colors */
    --color-primary-500: #3b82f6;
    --color-primary-600: #2563eb;
    --color-secondary-500: #a855f7;
    
    /* Custom Fonts */
    --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
    --font-heading: 'Poppins', ui-sans-serif, system-ui, sans-serif;
    
    /* Custom Border Radius */
    --radius-sm: 0.25rem;
    --radius-DEFAULT: 0.5rem;
    --radius-lg: 1rem;
    
    /* Custom Spacing */
    --spacing-18: 4.5rem;
    --spacing-72: 18rem;
    
    /* Custom Breakpoints */
    --breakpoint-3xl: 1920px;
}
```

### Usage in Components

```jsx
export default function MyComponent() {
    return (
        <div className="bg-primary-500 text-white rounded-lg p-6">
            <h1 className="font-heading text-3xl">Custom Styled Component</h1>
            <p className="mt-4">Using custom theme values!</p>
        </div>
    );
}
```

### Available Customizations

Tailwind CSS 4 allows you to customize:

- **Colors** - `--color-{name}-{shade}`
- **Fonts** - `--font-{family}`
- **Spacing** - `--spacing-{size}`
- **Border Radius** - `--radius-{size}`
- **Shadows** - `--shadow-{size}`
- **Breakpoints** - `--breakpoint-{name}`
- **Transitions** - `--transition-{speed}`
- **Z-Index** - `--z-index-{level}`

📖 **Learn More:** [Tailwind CSS v4 Theme Documentation](https://tailwindcss.com/docs/theme)

---

## 🔍 SEO Configuration

FeatureKit is SEO-ready out of the box.

### Global SEO Settings

Configure in `.env`:

```env
APP_NAME="Your App Name"
APP_META_DESCRIPTION="Your app description for search engines"
APP_OG_IMAGE="/og-image.png"
```

### Per-Page SEO

Override using Inertia's `<Head>` component:

```jsx
import { Head } from '@inertiajs/react';

export default function BlogPost({ post }) {
    return (
        <>
            <Head>
                <title>{post.title} - My Blog</title>
                <meta name="description" content={post.excerpt} />
                <meta property="og:title" content={post.title} />
                <meta property="og:description" content={post.excerpt} />
                <meta property="og:image" content={post.image} />
            </Head>
            
            <article>
                <h1>{post.title}</h1>
                <p>{post.content}</p>
            </article>
        </>
    );
}
```

### Included Meta Tags

- Charset and viewport
- CSRF token
- Canonical URL
- Robots directives
- Open Graph (Facebook)
- Twitter Cards
- Theme color
- Favicon support

---

## �️ Database Migrations

FeatureKit includes pre-configured migrations for common Laravel features.

### Available Migrations

#### 1. Users Table (`0001_01_01_000000_create_users_table.php`)

Creates three tables: `users`, `password_reset_tokens`, and `sessions`.

**`users` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (primary) | Unique user identifier |
| `name` | string | User's full name |
| `role` | string (nullable) | User role (e.g., admin, user, editor) |
| `profile_image` | string (nullable) | Path to user's profile image |
| `age` | tinyint (nullable) | User's age (0-255) |
| `gender` | string(20) (nullable) | User's gender |
| `email` | string (unique) | User's email address for login |
| `email_verified_at` | timestamp (nullable) | When email was verified (for Laravel's email verification) |
| `password` | string | Hashed password |
| `remember_token` | string (nullable) | Token for "remember me" functionality |
| `created_at` | timestamp | When user was created |
| `updated_at` | timestamp | When user was last updated |

**`password_reset_tokens` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `email` | string (primary) | Email requesting password reset |
| `token` | string | Unique reset token |
| `created_at` | timestamp (nullable) | When reset was requested |

**`sessions` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `id` | string (primary) | Unique session identifier |
| `user_id` | bigint (nullable, indexed) | Associated user ID |
| `ip_address` | string(45) (nullable) | IP address of session |
| `user_agent` | text (nullable) | Browser/client user agent |
| `payload` | longtext | Serialized session data |
| `last_activity` | int (indexed) | Unix timestamp of last activity |

#### 2. Cache Tables (`0001_01_01_000001_create_cache_table.php`)

**`cache` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `key` | string (primary) | Cache key identifier |
| `value` | mediumtext | Serialized cached value |
| `expiration` | int | Unix timestamp when cache expires |

**`cache_locks` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `key` | string (primary) | Lock key identifier |
| `owner` | string | Process/thread that owns the lock |
| `expiration` | int | Unix timestamp when lock expires |

#### 3. Queue/Jobs Tables (`0001_01_01_000002_create_jobs_table.php`)

**`jobs` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (primary) | Unique job identifier |
| `queue` | string (indexed) | Queue name the job belongs to |
| `payload` | longtext | Serialized job data |
| `attempts` | tinyint | Number of times job has been attempted |
| `reserved_at` | int (nullable) | When job was reserved for processing |
| `available_at` | int | When job becomes available for processing |
| `created_at` | int | When job was created |

**`job_batches` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `id` | string (primary) | Unique batch identifier |
| `name` | string | Batch name/description |
| `total_jobs` | int | Total jobs in batch |
| `pending_jobs` | int | Jobs not yet processed |
| `failed_jobs` | int | Jobs that failed |
| `failed_job_ids` | longtext | List of failed job IDs |
| `options` | mediumtext (nullable) | Batch options/metadata |
| `cancelled_at` | int (nullable) | When batch was cancelled |
| `created_at` | int | When batch was created |
| `finished_at` | int (nullable) | When batch completed |

**`failed_jobs` Table:**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (primary) | Unique failed job identifier |
| `uuid` | string (unique) | Unique UUID for job |
| `connection` | text | Queue connection used |
| `queue` | text | Queue name |
| `payload` | longtext | Job data |
| `exception` | longtext | Exception details/stack trace |
| `failed_at` | timestamp | When job failed |

### Running Migrations

```bash
# Run all migrations
php artisan migrate

# Fresh migration (drop all tables and re-migrate)
php artisan migrate:fresh

# Rollback last migration batch
php artisan migrate:rollback

# Reset all migrations
php artisan migrate:reset
```

### Custom Migrations

Create feature-specific migrations:

```bash
# Create migration for a model
php artisan make:feature-model Blog Post --migration

# Or create standalone migration
php artisan make:migration create_posts_table
```

---
## 🔐 Authentication

FeatureKit includes a complete authentication system with Login, Register, Logout, and Password Reset functionality using Inertia.js and React.

### Available Auth Routes

The Auth feature provides these routes out of the box:

| Route | Method | Purpose | Access |
|-------|--------|---------|--------|
| `/login` | GET | Display login form | Guest only |
| `/login` | POST | Handle login | Guest only |
| `/register` | GET | Display registration form | Guest only |
| `/register` | POST | Handle registration | Guest only |
| `/forgot-password` | GET | Display forgot password form | Guest only |
| `/forgot-password` | POST | Send password reset link | Guest only |
| `/reset-password/{token}` | GET | Display reset password form | Guest only |
| `/reset-password` | POST | Handle password reset | Guest only |
| `/logout` | POST | Handle logout | Authenticated only |

### Backend Controllers

#### LoginController

Handles user authentication with "remember me" functionality:

```php
<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Welcome back!');
    }
}
```

#### RegisterController

Handles new user registration:

```php
<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Account created successfully!');
    }
}
```

#### LogoutController

Handles user logout and session cleanup:

```php
<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out.');
    }
}
```

#### ForgotPasswordController

Handles password reset link requests:

```php
<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent!');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
```

#### ResetPasswordController

Handles password reset with token:

```php
<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->route('token'),
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/login')->with('success', 'Password reset successfully!');
        }

        throw ValidationException::withMessages(['email' => [trans($status)]]);
    }
}
```

### Frontend Pages

#### Login Page

Located at `resources/js/Pages/Auth/Login.jsx`:

- Email and password fields with validation
- "Remember me" checkbox for persistent sessions
- Flash message display for success/error notifications
- Links to registration and password reset
- Responsive design with Tailwind CSS

#### Register Page

Located at `resources/js/Pages/Auth/Register.jsx`:

- Full name, email, password, and password confirmation fields
- Real-time validation error display
- Terms of service acknowledgment
- Link to login page for existing users
- Auto-login after successful registration

#### Forgot Password Page

Located at `resources/js/Pages/Auth/ForgotPassword.jsx`:

- Email input field
- Sends password reset link via email
- Success/error flash messages
- Link back to login page

#### Reset Password Page

Located at `resources/js/Pages/Auth/ResetPassword.jsx`:

- Email, password, and password confirmation fields
- Token automatically included from URL
- Validates and updates password
- Redirects to login after successful reset

### Password Reset Flow

1. User clicks "Forgot password?" on login page
2. Enters email on `/forgot-password` page
3. Laravel sends reset link to email using `password_reset_tokens` table
4. User clicks link in email (e.g., `/reset-password/abc123?email=user@example.com`)
5. User enters new password on reset form
6. Password is updated and user redirected to login

### Usage in Components

Use authentication hooks to protect routes or show/hide UI elements:

```jsx
import { useUser, useIsAuthenticated } from '@/hooks/useSharedProps';
import { router } from '@inertiajs/react';

export default function MyComponent() {
    const user = useUser();
    const isAuthenticated = useIsAuthenticated();

    const handleLogout = () => {
        router.post(route('logout'));
    };

    return (
        <div>
            {isAuthenticated ? (
                <>
                    <p>Welcome, {user.name}!</p>
                    <button onClick={handleLogout}>Logout</button>
                </>
            ) : (
                <a href={route('login')}>Login</a>
            )}
        </div>
    );
}
```

### Session Security

The authentication system includes:

- **Session Regeneration**: New session ID after login to prevent session fixation
- **CSRF Protection**: Automatic CSRF token validation on all POST requests
- **Password Hashing**: BCrypt hashing via `Hash::make()`
- **Remember Me**: Secure persistent login with token rotation
- **Logout Cleanup**: Session invalidation and token regeneration on logout

### Customization

To customize authentication behavior:

1. **Validation Rules**: Edit validation in controller `store()` methods
2. **Redirect Path**: Change `route('home')` to your preferred route
3. **User Fields**: Add custom fields in registration (age, role, etc.)
4. **Styling**: Modify `resources/js/Pages/Auth/*.jsx` components
5. **Email Verification**: Enable by adding verification routes and middleware

---
## �📁 Directory Structure

```
laravel-featurekit/
├── app/
│   ├── Console/
│   │   └── Commands/          # Custom Artisan commands
│   │       ├── MakeFeatureCommand.php
│   │       ├── MakeFeatureControllerCommand.php
│   │       ├── MakeFeatureModelCommand.php
│   │       ├── MakeFeaturePageCommand.php
│   │       └── ListFeaturesCommand.php
│   ├── Features/              # Feature modules
│   │   ├── Auth/
│   │   │   ├── Controllers/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── LogoutController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   └── ResetPasswordController.php
│   │   │   └── web.php
│   │   ├── Home/
│   │   │   ├── Controllers/
│   │   │   │   └── HomeController.php
│   │   │   └── web.php
│   │   └── Test/
│   │       └── web.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Controller.php
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php
│   ├── Models/
│   │   └── User.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── FeatureRouteServiceProvider.php
├── resources/
│   ├── css/
│   │   └── app.css           # Tailwind CSS with @theme
│   ├── js/
│   │   ├── hooks/
│   │   │   └── useSharedProps.js  # Custom React hooks
│   │   ├── Components/       # Reusable components
│   │   ├── Layouts/          # Layout components
│   │   ├── Pages/            # Inertia page components
│   │   │   ├── Auth/
│   │   │   │   ├── Login.jsx
│   │   │   │   ├── Register.jsx
│   │   │   │   ├── ForgotPassword.jsx
│   │   │   │   └── ResetPassword.jsx
│   │   │   └── Welcome.jsx
│   │   ├── app.jsx           # React/Inertia entry point
│   │   └── bootstrap.js      # Bootstrap code
│   └── views/
│       └── app.blade.php     # Root template
├── database/
│   ├── migrations/
│   └── seeders/
├── config/                   # Laravel config
├── HOOKS.md                  # Hooks documentation
├── README.md                 # This file
├── composer.json
├── package.json
└── vite.config.js           # Vite configuration
```

---

## 💡 Examples

### Example 1: Simple Blog Feature

```bash
# Generate Blog feature
php artisan make:feature Blog
```

**Route** (`app/Features/Blog/web.php`):
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Features\Blog\Controllers\BlogController;

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});
```

**Controller** (`app/Features/Blog/Controllers/BlogController.php`):
```php
<?php

namespace App\Features\Blog\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Blog/Index', [
            'posts' => Post::latest()->paginate(10),
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return Inertia::render('Blog/Show', [
            'post' => $post,
        ]);
    }
}
```

**React Page** (`resources/js/Pages/Blog/Index.jsx`):
```jsx
import { Head, Link } from '@inertiajs/react';

export default function BlogIndex({ posts }) {
    return (
        <>
            <Head title="Blog" />
            
            <div className="container mx-auto px-4 py-8">
                <h1 className="text-4xl font-bold mb-8">Blog</h1>
                
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {posts.data.map((post) => (
                        <article key={post.id} className="border rounded-lg p-6">
                            <h2 className="text-2xl font-semibold mb-2">
                                <Link href={route('blog.show', post.slug)}>
                                    {post.title}
                                </Link>
                            </h2>
                            <p className="text-gray-600">{post.excerpt}</p>
                        </article>
                    ))}
                </div>
            </div>
        </>
    );
}
```

### Example 2: E-commerce with API

```bash
# Generate Products feature with API
php artisan make:feature Products --api

# Add Product model with migration
php artisan make:feature-model Products Product --migration
```

Access your API at:
- `GET /api/products` - List all products
- `POST /api/products` - Create product
- `GET /api/products/{id}` - Get single product

---

## 🤝 Contributing

Contributions are welcome! Feel free to submit issues and pull requests.

---

## 👨‍💻 Author

**Created by Md. Rashedul Islam**

- GitHub: [Laravel FeatureKit](https://github.com/rifatxtra/Laravel-FeatureKit)
- Website: [rifatxtra.com](https://rifatxtra.com/)

---

## 📄 License

Laravel FeatureKit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🌟 Show Your Support

If you find this project helpful, please give it a ⭐ on [GitHub](https://github.com/rifatxtra/Laravel-FeatureKit)!

---

**Happy Coding! 🚀**
