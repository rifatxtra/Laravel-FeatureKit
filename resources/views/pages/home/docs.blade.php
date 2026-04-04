@extends('pages.layout')

@section('title', 'Documentation - Laravel Feature Kit')
@section('description', 'Complete technical documentation for Laravel FeatureKit v2.1.1. Architecture, utilities, commands, and more.')

@section('content')
<div class="bg-base py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                <h1>
                    <span class="block text-sm font-semibold uppercase tracking-wide text-primary">Documentation</span>
                    <span class="mt-1 block text-4xl tracking-tight font-extrabold sm:text-5xl xl:text-6xl text-foreground">
                        Getting Started
                    </span>
                </h1>
                <p class="mt-3 text-base text-surface-foreground/60 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                    Everything you need to know about setting up and building with the Laravel Feature Kit. From architecture to utilities, commands, and best practices.
                </p>
            </div>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="col-span-1 space-y-4">
                <nav class="sticky top-24">
                    <h3 class="text-xs font-semibold text-primary uppercase tracking-wider">Fundamentals</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#installation" class="text-sm text-foreground hover:text-primary transition-colors block py-2 px-3 rounded-lg bg-primary/5 border border-primary/10">Installation</a></li>
                        <li><a href="#architecture" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">Architecture</a></li>
                        <li><a href="#frontend" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">Frontend Strategy</a></li>
                        <li><a href="#commands" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">Artisan Commands</a></li>
                    </ul>

                    <h3 class="text-xs font-semibold text-primary uppercase tracking-wider mt-8">Systems</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#mail" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">Mailing System</a></li>
                        <li><a href="#notifications" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">Notification System</a></li>
                        <li><a href="#utilities" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">JS Utility Suite</a></li>
                        <li><a href="#hooks" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">React Hooks</a></li>
                        <li><a href="#theming" class="text-sm text-surface-foreground/60 hover:text-primary transition-colors block py-2 px-3 rounded-lg border border-transparent">Design Tokens</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Content -->
            <div class="col-span-2 prose prose-primary max-w-none text-surface-foreground/80">
                {{-- INSTALLATION --}}
                <section id="installation">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">Introduction & Setup</h2>
                    <p class="text-lg">Welcome to the <strong>Laravel Feature Kit</strong>. This documentation covers every system, pattern, and utility in the project — from the Feature-Driven Architecture to the 11-module JavaScript utility suite.</p>

                    <h3 class="text-xl font-bold text-foreground mt-8 mb-4 tracking-tight uppercase text-xs text-primary">Prerequisites</h3>
                    <ul class="list-disc pl-5 mt-4 space-y-2 opacity-80">
                        <li>PHP 8.2+</li>
                        <li>Node.js 18+ & NPM</li>
                        <li>Composer 2.x</li>
                        <li>Database (SQLite by default, or MySQL / PostgreSQL)</li>
                    </ul>

                    <h3 class="text-xl font-bold text-foreground mt-8 mb-4">Quick Install</h3>
                    <p>The fastest way to install is via composer:</p>
                    <pre class="bg-surface p-4 rounded-xl border border-surface-foreground/10 text-primary overflow-x-auto shadow-inner mb-4"><code>composer create-project rifatxtra/laravel-featurekit my-app
cd my-app
composer setup
composer dev</code></pre>
                    <p class="text-sm opacity-60 bg-primary/5 p-4 rounded-lg border-l-4 border-primary">
                        <strong>Note:</strong> <code>composer setup</code> automatically runs <code>cp .env.example .env</code>, generates your key, migrates the database (SQLite by default), installs node dependencies, and builds assets.
                    </p>

                    <h3 class="text-xl font-bold text-foreground mt-8 mb-4">What <code>composer dev</code> Starts</h3>
                    <p>A single command launches 4 processes concurrently:</p>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">🌐 Server</span>
                            <p class="text-xs opacity-60 mt-1">php artisan serve</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">📧 Queue</span>
                            <p class="text-xs opacity-60 mt-1">php artisan queue:listen</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">📋 Logs</span>
                            <p class="text-xs opacity-60 mt-1">php artisan pail</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">⚡ Vite</span>
                            <p class="text-xs opacity-60 mt-1">npm run dev (HMR)</p>
                        </div>
                    </div>
                </section>

                {{-- ARCHITECTURE --}}
                <section id="architecture" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">Mastering "Domains"</h2>
                    <p>Unlike standard Laravel setups where logic is split across multiple top-level folders, Feature Kit uses <strong>Feature-Driven Design (FDD)</strong>. Every piece of your app—from models to routes—is a self-contained feature.</p>

                    <h3 class="text-xl font-bold text-foreground mt-10 mb-4">Feature Folder Structure</h3>
                    <pre class="bg-surface p-4 rounded-xl border border-surface-foreground/10 text-primary overflow-x-auto shadow-inner mb-4"><code>app/Features/Payment/
├── Controllers/       # Thin HTTP layer
├── Services/          # Business logic ("the brain")
├── Models/            # Eloquent models
├── Requests/          # Form validation
├── Observers/         # Model lifecycle hooks
├── Events/            # Domain events
├── Exceptions/        # Feature-specific errors
└── routes/
    ├── web.php        # Auto-discovered!
    └── api.php        # Auto-discovered!</code></pre>

                    <h3 class="text-xl font-bold text-foreground mt-10 mb-4">The Logic Stack</h3>
                    <div class="space-y-6">
                        <div class="p-6 bg-surface rounded-2xl border border-primary/5 shadow-sm">
                            <span class="font-bold text-primary block mb-1">1. Thin Controllers</span>
                            <p class="text-sm opacity-90 leading-relaxed mb-4">Controllers have one job: translate HTTP requests. They should never contain business logic, DB queries, or email sending.</p>
                            <pre class="bg-base p-3 rounded text-[10px] opacity-70"><code>public function store(RegisterRequest $request, RegisterService $service) {
    $service->register($request->validated());
    return redirect()->intended(route('home.index'));
}</code></pre>
                        </div>
                        <div class="p-6 bg-surface rounded-2xl border border-primary/5 shadow-sm">
                            <span class="font-bold text-primary block mb-1">2. Brainy Services</span>
                            <p class="text-sm opacity-90 leading-relaxed mb-4">The Service class handles DB transactions, hashing, mailing, API calls, and calculations.</p>
                            <pre class="bg-base p-3 rounded text-[10px] opacity-70"><code>class RegisterService extends BaseService {
    public function register(array $data): User {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        Auth::login($user);
        return $user;
    }
}</code></pre>
                        </div>
                        <div class="p-6 bg-surface rounded-2xl border border-primary/5 shadow-sm">
                            <span class="font-bold text-primary block mb-1">3. Unified API Responses</span>
                            <p class="text-sm opacity-90 leading-relaxed mb-4">Every controller inherits <code>ApiResponseTrait</code> via <code>BaseController</code> for consistent JSON output.</p>
                            <pre class="bg-base p-3 rounded text-[10px] opacity-70"><code>$this->success($data, 'Fetched successfully');
// → { "success": true, "message": "...", "data": [...] } (200)

$this->error('Something went wrong', $errors, 400);
// → { "success": false, "message": "...", "errors": [...] } (400)

$this->created($data);  // 201
$this->noContent();      // 204</code></pre>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-foreground mt-10 mb-4">Auto Route Discovery</h3>
                    <p>Routes inside <code>app/Features/*/routes/web.php</code> are scanned and registered automatically at boot. No manual <code>Route::group()</code> needed. For role-based features (e.g., <code>Dashboard/Admin</code>), routes automatically receive URL and name prefixes based on the role folder name.</p>
                </section>

                {{-- FRONTEND --}}
                <section id="frontend" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">Frontend Architecture</h2>
                    <p>We use a <strong>Hybrid Strategy</strong> — Blade for SEO-critical pages, React 19 + Inertia.js v2 for SPA portals.</p>

                    <div class="mt-8 space-y-6">
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 bg-primary/20 p-3 rounded-xl text-primary h-fit">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-foreground text-lg mb-1">Blade + Tailwind v4 (SEO Layer)</h4>
                                <p class="text-sm opacity-80 leading-relaxed">Marketing pages, docs, and authentication views utilize Blade templating with Tailwind v4. Full SEO meta tags (OG + Twitter Cards), responsive header/footer partials, and loading spinner built-in.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 bg-primary/20 p-3 rounded-xl text-primary h-fit">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-foreground text-lg mb-1">React 19 + Inertia.js v2 (SPA Layer)</h4>
                                <p class="text-sm opacity-80 leading-relaxed">Internal portals enjoy a full SPA experience. We use folder-based routing similar to Next.js App Router (<code>pages/(portals)/admin/dashboard/page.jsx</code>). Every page is automatically wrapped in <code>MainLayout</code> with Toast, Modal, and LoadingSpinner — zero configuration needed.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 bg-primary/20 p-3 rounded-xl text-primary h-fit">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-foreground text-lg mb-1">3 Pre-Built Layouts</h4>
                                <p class="text-sm opacity-80 leading-relaxed"><code>MainLayout</code> (default persistent wrapper), <code>AdminLayout</code> (sidebar admin shell), <code>UserLayout</code> (user portal shell). Pages override the default by setting <code>Page.layout = (page) => &lt;AdminLayout&gt;{page}&lt;/AdminLayout&gt;</code>.</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-foreground mt-10 mb-4">React UI Component Kit (7 Components)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">Toast</span>
                            <p class="text-xs opacity-60 mt-1">Auto-reads Laravel flash messages, 4 types, auto-dismiss, stacking</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">Modal</span>
                            <p class="text-xs opacity-60 mt-1">Context-driven, ESC close, 5 sizes, body scroll lock, animations</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">Pagination</span>
                            <p class="text-xs opacity-60 mt-1">Laravel paginator integration with Inertia Link navigation</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">SeoHead</span>
                            <p class="text-xs opacity-60 mt-1">Dynamic title, description, and keywords via Inertia Head</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">BasicEditor</span>
                            <p class="text-xs opacity-60 mt-1">TipTap rich text: bold, italic, H1-H3, lists, alignment, inline styles</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">PromoTemplates</span>
                            <p class="text-xs opacity-60 mt-1">5 variants: ImagePromo, BannerPromo, CountdownPromo, EmailCapture, Gallery</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">LoadingSpinner</span>
                            <p class="text-xs opacity-60 mt-1">Full-screen overlay during Inertia page transitions</p>
                        </div>
                    </div>
                </section>

                {{-- MAIL --}}
                <section id="mail" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">Universal Mailing System</h2>
                    <p>Stop writing repetitive mailable classes. One <code>GeneralMail</code> class handles every email in your app — queued by default.</p>

                    <pre class="bg-surface p-4 rounded-xl border border-surface-foreground/10 text-primary overflow-x-auto shadow-inner my-6"><code>Mail::to($user)->queue(new GeneralMail(
    mailSubject: 'Your Order Has Shipped',
    contentView: 'emails.orders.shipped-body',
    data: [
        'title'      => 'Order Shipped!',
        'body'       => 'Your order #1234 is on its way.',
        'actionText' => 'Track Order',
        'actionUrl'  => 'https://example.com/track/1234',
    ]
));</code></pre>
                </section>

                {{-- NOTIFICATIONS --}}
                <section id="notifications" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">Unified Notification System</h2>
                    <p>FeatureKit uses an <strong>Event-Driven Architecture</strong> to handle notifications. This keeps your business logic clean and allows for asynchronous processing.</p>

                    <h3 class="text-xl font-bold text-foreground mt-8 mb-4">The <code>NotificationCreated</code> Event</h3>
                    <p>Trigger notifications from any Service or Controller using a single shared event:</p>
                    <pre class="bg-surface p-4 rounded-xl border border-surface-foreground/10 text-primary overflow-x-auto shadow-inner my-6"><code>use App\Features\Notification\Events\NotificationCreated;

event(new NotificationCreated(
    user: $auth_user,
    category: 'Account',
    title: 'Profile Updated',
    message: 'Your profile has been successfully updated.'
));</code></pre>

                    <h3 class="text-xl font-bold text-foreground mt-8 mb-4 tracking-tight uppercase text-xs text-primary">Key Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">⚡ Queue-Backed</span>
                            <p class="text-xs opacity-60 mt-1">The listener implements ShouldQueue, ensuring high performance by offloading DB writes to the background.</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">🛡️ Role-Aware</span>
                            <p class="text-xs opacity-60 mt-1">Automatically routes records to Admin or User notification tables based on the recipient's role.</p>
                        </div>
                    </div>
                </section>

                {{-- ADVANCED PATTERNS --}}
                <section id="advanced-patterns" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">Advanced Patterns</h2>
                    
                    <h3 class="text-lg font-bold text-foreground mt-8 mb-4">1. Independent User Models</h3>
                    <p class="text-sm opacity-80 leading-relaxed">For large-scale apps, FeatureKit promotes <strong>Feature-Specific User Models</strong> (e.g., <code>App\Features\Profile\Admin\Models\User</code>). This prevents the core User model from becoming overloaded and allows for clean, domain-specific logic.</p>

                    <h3 class="text-lg font-bold text-foreground mt-8 mb-4">2. Private Storage & Secure Delivery</h3>
                    <p class="text-sm opacity-80 leading-relaxed mb-4">Sensitive assets like profile images are stored in <code>storage/app/private/</code>. Access is gated through modular feature controllers and role-protected routes.</p>
                    <pre class="bg-surface p-4 rounded-xl border border-surface-foreground/10 text-primary overflow-x-auto shadow-inner"><code>// Route defined inside Feature web.php
Route::get('/admin/profile-image', [ProfileImageController::class, 'show']);</code></pre>
                </section>

                {{-- COMMANDS --}}
                <section id="commands" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">CLI Scaffolding (7 Commands)</h2>
                    <p class="mb-8">We automated the "grunt work" so you don't have to manually create files for every new feature.</p>

                    <div class="bg-surface rounded-2xl border border-surface-foreground/5 shadow-sm overflow-hidden">
                        <div class="bg-primary/10 px-6 py-4 border-b border-primary/20">
                            <h3 class="text-primary font-bold">Available Scaffolders</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-center justify-between border-b border-surface-foreground/5 pb-4">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature {Name}</code>
                                    <p class="text-xs mt-1 opacity-60">Full domain scaffold: Controllers, Services, Models, Requests, Observers, Events, Exceptions, Routes.</p>
                                </div>
                                <span class="bg-primary text-white text-[10px] px-2 py-0.5 rounded-full font-bold">POPULAR</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-surface-foreground/5 pb-4">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature {Name} --roles=Admin,User</code>
                                    <p class="text-xs mt-1 opacity-60">Role-based feature with separate sub-folders per role, auto-prefixed routes.</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-b border-surface-foreground/5 pb-4">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature:controller {Feat} {Name}</code>
                                    <p class="text-xs mt-1 opacity-60">Controller extending BaseController in the specified feature.</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-b border-surface-foreground/5 pb-4">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature:service {Feat} {Name}</code>
                                    <p class="text-xs mt-1 opacity-60">Service extending BaseService in the specified feature.</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-b border-surface-foreground/5 pb-4">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature:request {Feat} {Name}</code>
                                    <p class="text-xs mt-1 opacity-60">FormRequest with authorize() and rules() in the specified feature.</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-b border-surface-foreground/5 pb-4">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature:event {Feat} {Name}</code>
                                    <p class="text-xs mt-1 opacity-60">Event class with Dispatchable and SerializesModels traits.</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-b border-surface-foreground/5 pb-4">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature:exception {Feat} {Name}</code>
                                    <p class="text-xs mt-1 opacity-60">Exception extending BaseException in the specified feature.</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <code class="text-primary font-bold text-sm bg-primary/5 px-2 py-1 rounded">make:feature:observer {Feat} {Name}</code>
                                    <p class="text-xs mt-1 opacity-60">Observer with creating, created, updating, updated, deleting, deleted hooks.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- UTILITIES --}}
                <section id="utilities" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">JavaScript Utility Suite (11 Modules)</h2>
                    <p class="mb-8">All utilities are barrel-exported via <code>@/Utils</code>. Import what you need — tree-shaking removes the rest.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">🖼️ imageCompressor</span>
                            <p class="text-xs opacity-60 mt-1">compressImage, compressImages, generateResponsiveImages (3 sizes), getImageDimensions, formatFileSize</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">🔔 toast</span>
                            <p class="text-xs opacity-60 mt-1">TOAST_TYPES, formatValidationErrors, hasErrors, getError</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">💾 storage</span>
                            <p class="text-xs opacity-60 mt-1">set, get, remove, clear, setWithExpiry, getWithExpiry, has, keys — with JSON auto-serialization</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">📋 clipboard</span>
                            <p class="text-xs opacity-60 mt-1">copyToClipboard (with execCommand fallback), copyWithToast, readFromClipboard</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">📅 date</span>
                            <p class="text-xs opacity-60 mt-1">formatDate, formatDateTime, timeAgo — locale-aware with Intl API</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">💰 number</span>
                            <p class="text-xs opacity-60 mt-1">formatNumber, formatCurrency (15 currencies by name), formatPercent, formatBytes, compactNumber</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">✂️ string</span>
                            <p class="text-xs opacity-60 mt-1">slugify, truncate, capitalize, randomString</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">✅ validation</span>
                            <p class="text-xs opacity-60 mt-1">validatePhone, validateEmail, validatePassword (strict), passwordRequirements array</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">⚡ performance</span>
                            <p class="text-xs opacity-60 mt-1">debounce (search, validation), throttle (scroll, resize)</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">📊 webVitals</span>
                            <p class="text-xs opacity-60 mt-1">initWebVitals (LCP, FID, CLS, FCP, TTFB), getWebVitals — with console logging and server reporting</p>
                        </div>
                    </div>

                    <pre class="bg-surface p-4 rounded-xl border border-surface-foreground/10 text-primary overflow-x-auto shadow-inner mt-6"><code>import { compressImage, slugify, formatDate, debounce, formatCurrency } from '@/Utils';
import storage from '@/Utils';

const compressed = await compressImage(file, { maxWidth: 1200, quality: 0.7 });
const slug = slugify('Hello World');       // "hello-world"
const price = formatCurrency(99.99, 'taka'); // "৳99.99"
storage.setWithExpiry('token', 'abc', 3600); // expires in 1hr</code></pre>
                </section>

                {{-- HOOKS --}}
                <section id="hooks" class="mt-16 border-t border-surface-foreground/10 pt-10">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">React Hooks Library (20+ Hooks)</h2>
                    <p class="mb-8">All hooks wrap Inertia's <code>usePage()</code> for clean, typed access to server-shared data. Import from <code>@/Hooks</code>.</p>

                    <div class="space-y-4">
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">Authentication</span>
                            <p class="text-xs opacity-60 mt-1">useAuth, useUser, useIsAuthenticated, useIsGuest, useHasRole, useHasPermission</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">Flash Messages</span>
                            <p class="text-xs opacity-60 mt-1">useFlash, useFlashMessage, useFlashSuccess, useFlashError, useFlashWarning, useFlashInfo</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">Validation Errors</span>
                            <p class="text-xs opacity-60 mt-1">useErrors, useError, useHasErrors, useFieldErrors, useFirstError</p>
                        </div>
                        <div class="p-4 bg-surface rounded-xl border border-surface-foreground/5">
                            <span class="text-sm font-bold text-primary">Navigation & Config</span>
                            <p class="text-xs opacity-60 mt-1">useSharedProps, useRoute, useIsRoute, useAppConfig, useCsrfToken</p>
                        </div>
                    </div>
                </section>

                {{-- THEMING --}}
                <section id="theming" class="mt-16 border-t border-surface-foreground/10 pt-10 pb-20">
                    <h2 class="text-3xl font-extrabold text-foreground mb-6">Tailwind v4 Design Tokens</h2>
                    <p class="mb-8">Semantic color tokens using OKLCH color space in <code>resources/css/app.css</code>. Change the primary brand color and every component updates automatically.</p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="w-full aspect-square rounded-xl bg-primary mb-2"></div>
                            <span class="text-xs font-bold text-foreground">primary</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full aspect-square rounded-xl bg-secondary mb-2"></div>
                            <span class="text-xs font-bold text-foreground">secondary</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full aspect-square rounded-xl bg-surface border border-surface-foreground/10 mb-2"></div>
                            <span class="text-xs font-bold text-foreground">surface</span>
                        </div>
                        <div class="text-center">
                            <div class="w-full aspect-square rounded-xl bg-error mb-2"></div>
                            <span class="text-xs font-bold text-foreground">error</span>
                        </div>
                    </div>

                    <pre class="bg-surface p-4 rounded-xl border border-surface-foreground/10 text-primary overflow-x-auto shadow-inner mt-6"><code>/* White-label your app: change one line */
--color-primary: oklch(0.65 0.20 150); /* indigo → green */
/* Every bg-primary, text-primary, border-primary updates instantly */</code></pre>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
