# Agent Workflow — Code Generation Decision Engine

> **Prerequisites:** Read `.agent/agent.md` first for the complete knowledge base (architecture, all utilities, hooks, components, tokens, and conventions).
> This file provides the **step-by-step workflow** for generating code correctly.

---

## 🧠 Step 1 — Identify Feature Type

```
Is it role-specific? (admin only, user only, etc.)
  YES → Role-based feature: app/Features/{Feature}/{Role}/
  NO  → Simple feature:     app/Features/{Feature}/
```

---

## 🗂️ Step 2 — Determine Page Type

```
Is it a public page? (no login required, needs SEO)
  YES → Blade  → resources/views/pages/{feature}/{page}/page.blade.php
                  Extend: @extends('pages.layout') or @extends('pages.auth.layout')

  NO  → React  → resources/js/pages/(portals)/{role}/{page}/page.jsx
                  Render: Inertia::render('(portals)/{role}/{page}/page', [...])
                  Auto-wrapped in MainLayout (Toast + Modal + Spinner)
```

---

## 📁 Step 3 — Map All File Paths

### Backend (PHP)

```
Simple feature:
  Controller → app/Features/{Feature}/Controllers/{Name}Controller.php
  Service    → app/Features/{Feature}/Services/{Name}Service.php
  Request    → app/Features/{Feature}/Requests/{Name}Request.php
  Model      → app/Features/{Feature}/Models/{Name}.php
  Observer   → app/Features/{Feature}/Observers/{Name}Observer.php
  Event      → app/Features/{Feature}/Events/{Name}.php
  Exception  → app/Features/{Feature}/Exceptions/{Name}Exception.php
  Web routes → app/Features/{Feature}/routes/web.php
  API routes → app/Features/{Feature}/routes/api.php

Role-based feature:
  Controller → app/Features/{Feature}/{Role}/Controllers/{Name}Controller.php
  Service    → app/Features/{Feature}/{Role}/Services/{Name}Service.php
  (same pattern for all other types)
  Web routes → app/Features/{Feature}/{Role}/routes/web.php
  API routes → app/Features/{Feature}/{Role}/routes/api.php
```

### Namespaces

```php
// Simple
namespace App\Features\{Feature}\Controllers;
namespace App\Features\{Feature}\Services;
namespace App\Features\{Feature}\Models;
namespace App\Features\{Feature}\Requests;
namespace App\Features\{Feature}\Observers;
namespace App\Features\{Feature}\Events;
namespace App\Features\{Feature}\Exceptions;

// Role-based
namespace App\Features\{Feature}\{Role}\Controllers;
namespace App\Features\{Feature}\{Role}\Services;
// etc.
```

### Frontend

```
Public Blade page:
  Page    → resources/views/pages/{feature}/{page}/page.blade.php
  Layout  → resources/views/pages/layout.blade.php (shared)

React portal page:
  Page    → resources/js/pages/(portals)/{role}/{page}/page.jsx
  Nested  → resources/js/pages/(portals)/{role}/{section}/{page}/page.jsx
```

---

## 🖥️ Step 4 — Controller Template

```php
<?php

namespace App\Features\{Feature}\Controllers;

use App\Core\BaseController;
use App\Features\{Feature}\Services\{Name}Service;
use App\Features\{Feature}\Requests\{Name}Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class {Name}Controller extends BaseController
{
    public function __construct(private readonly {Name}Service $service) {}

    // Blade page (public/SEO)
    public function index()
    {
        return view('pages.{feature}.{page}.page', [
            'data' => $this->service->getData(),
        ]);
    }

    // Inertia page (portal/SPA)
    public function dashboard(): Response
    {
        return Inertia::render('(portals)/{role}/{page}/page', [
            'data' => $this->service->getData(),
        ]);
    }

    // Form submission (Blade)
    public function store({Name}Request $request): RedirectResponse
    {
        $this->service->handle($request->validated());
        return redirect()->route('{feature}.index')->with('success', 'Created!');
    }

    // Form submission (Inertia — same pattern, redirect triggers Toast)
    public function update({Name}Request $request): RedirectResponse
    {
        $this->service->update($request->validated());
        return redirect()->back()->with('success', 'Updated!');
    }

    // API endpoint (for mobile, webhooks, 3rd party)
    public function apiIndex(): JsonResponse
    {
        return $this->success($this->service->getData());
    }
}
```

---

## 🧠 Step 5 — Service Template

```php
<?php

namespace App\Features\{Feature}\Services;

use App\Core\BaseService;
use App\Features\{Feature}\Models\{Model};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class {Name}Service extends BaseService
{
    // RULES:
    // - Accept plain arrays or model instances (no Request/Response objects)
    // - Return plain data (arrays, models, collections)
    // - No redirect, no view, no Inertia::render

    public function getData(): array
    {
        return {Model}::latest()->paginate(15)->toArray();
    }

    public function handle(array $data): {Model}
    {
        return DB::transaction(fn() => {Model}::create($data));
    }

    public function update(array $data): bool
    {
        // Heavy logic, calculations, external APIs go here
        return true;
    }
}
```

---

## 📝 Step 6 — Route Templates

```php
<?php
// Simple feature: app/Features/{Feature}/routes/web.php
use App\Features\{Feature}\Controllers\{Name}Controller;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/{feature}', [{Name}Controller::class, 'index'])->name('{feature}.index');

// Auth-protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/{feature}/create', [{Name}Controller::class, 'create'])->name('{feature}.create');
    Route::post('/{feature}', [{Name}Controller::class, 'store'])->name('{feature}.store');
});
```

```php
<?php
// Role-based feature: app/Features/{Feature}/{Role}/routes/web.php
// Auto-prefix: /{role}, name prefix: {role}.
use App\Features\{Feature}\{Role}\Controllers\{Name}Controller;
use Illuminate\Support\Facades\Route;

// Use 'role:xxx' middleware to gate by user role
Route::middleware(['auth', 'role:{role}'])->group(function () {
    Route::get('/dashboard', [{Name}Controller::class, 'index'])->name('dashboard');
    // Accessible at: /{role}/dashboard → name: {role}.dashboard
});

// Multiple roles allowed:
// Route::middleware(['auth', 'role:admin,moderator'])->group(...);
```

---

## ⚛️ Step 7 — React Page Template

```jsx
// resources/js/pages/(portals)/{role}/{page}/page.jsx
// MainLayout auto-injected — Toast, Modal, Spinner available automatically

import { useForm, Link } from "@inertiajs/react";
import AdminLayout from "@/pages/(portals)/admin/layout";
import SeoHead from "@/Components/ui/SeoHead";
import Pagination from "@/Components/ui/Pagination";
import { useUser, useHasRole } from "@/Hooks";

export default function {Name}Page({ data }) {
    const user = useUser();
    const isAdmin = useHasRole("admin");

    const { data: formData, setData, post, processing, errors } = useForm({
        name: "",
        email: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("{role}.{feature}.store"));
    };

    return (
        <div className="p-6">
            <SeoHead title="{Page Title}" description="{Description}" />

            <h1 className="text-2xl font-bold text-foreground">{/* Title */}</h1>

            {/* Form with Inertia */}
            <form onSubmit={handleSubmit}>
                <input
                    value={formData.name}
                    onChange={(e) => setData("name", e.target.value)}
                    className="border border-border rounded-lg px-4 py-2"
                />
                {errors.name && <p className="text-error text-sm">{errors.name}</p>}

                <button
                    type="submit"
                    disabled={processing}
                    className="bg-primary text-primary-foreground px-6 py-2 rounded-lg"
                >
                    {processing ? "Saving..." : "Save"}
                </button>
            </form>

            {/* Pagination */}
            <Pagination links={data} />
        </div>
    );
}

{Name}Page.layout = (page) => <AdminLayout>{page}</AdminLayout>;

export default {Name}Page;
```

---

## ✉️ Step 8 — Email Template

When you need to send an email, create a body view:

```blade
{{-- resources/views/emails/{feature}/{name}-body.blade.php --}}
<div style="font-family: 'Inter', system-ui, sans-serif;">
    <h2 style="color: #111827; font-size: 22px; font-weight: 700;">
        {{ $title }}
    </h2>
    <p style="color: #4b5563; font-size: 16px; line-height: 1.6;">
        {{ $body }}
    </p>

    @if(isset($actionUrl))
    <div style="text-align: center; margin: 32px 0;">
        <x-mail::button :url="$actionUrl" color="primary">
            {{ $actionText ?? 'Click Here' }}
        </x-mail::button>
    </div>
    @endif
</div>
```

Then send via:
```php
Mail::to($user)->queue(new GeneralMail(
    mailSubject: 'Subject',
    contentView: 'emails.{feature}.{name}-body',
    data: ['title' => '...', 'body' => '...', 'actionUrl' => '...', 'actionText' => '...']
));
```

---

## 📋 Decision Tree

```
User request received
        │
        ▼
Is it a NEW FEATURE?
  YES → Run: php artisan make:feature {Name}
        or:  php artisan make:feature {Name} --roles=Admin,User
        │
        ▼
  Need specific file? Use: make:feature:{type} {Feature} {Name}
        │
        ▼
Is the page public or portal?
  Public  → Blade view + Feature Controller + view()
  Portal  → React page + Feature Controller + Inertia::render()
        │
        ▼
Need to send email?
  YES → Create body template in resources/views/emails/
        Use GeneralMail (NEVER create new Mailable)
        │
        ▼
Need client-side logic?
  Check existing @/Utils and @/Hooks FIRST
  Only create new if no existing tool covers the need
        │
        ▼
Need a modal?
  Use useModal() from @/Contexts/ModalContext
  Need promo? Use PromoTemplates
        │
        ▼
Need form?
  Blade: standard HTML form + redirect with flash
  React: useForm() from @inertiajs/react
        │
        ▼
No routes in bootstrap/app.php needed ✅
Toast notifications automatic via flash messages ✅
Layout injection automatic via MainLayout ✅
```

---

## 🔍 Pre-Generation Checklist

Before generating ANY code, verify:

- [ ] Namespace matches file path exactly
- [ ] Controller extends `BaseController`
- [ ] Service extends `BaseService`
- [ ] Exception extends `BaseException`
- [ ] Business logic is in Service (NOT controller)
- [ ] Inertia render path matches actual file in `resources/js/pages/`
- [ ] Layout override use `import AdminLayout from "@/pages/(portals)/admin/layout"`
- [ ] Blade view path uses dot notation matching `resources/views/`
- [ ] Route is in the feature's `routes/` folder (not root)
- [ ] Using existing hooks/utilities (checked `@/Hooks` and `@/Utils`)
- [ ] Using semantic Tailwind tokens (not arbitrary colors)
- [ ] Using `GeneralMail` for emails (not new Mailable classes)
- [ ] Role-based features use correct role subdirectory
- [ ] Flash messages used for toast (not custom state)
- [ ] Role-gated routes use `middleware('role:xxx')` (not manual checks in controllers)
- [ ] New middleware placed in `app/Core/Middleware/` (not `app/Http/`)
