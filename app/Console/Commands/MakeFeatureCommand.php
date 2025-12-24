<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureCommand extends Command
{
    protected $signature = 'make:feature {name} {--api} {--force}';

    protected $description = 'Create a new feature module structure';

    public function handle(): int
    {
        $name = $this->argument('name');
        $featureName = Str::studly($name);
        $featurePath = app_path("Features/{$featureName}");

        if (File::exists($featurePath) && !$this->option('force')) {
            $this->error("Feature '{$featureName}' already exists!");
            return self::FAILURE;
        }

        // Create feature directory structure
        $this->createDirectoryStructure($featurePath, $featureName);

        $this->info("Feature '{$featureName}' created successfully!");
        $this->line("Location: app/Features/{$featureName}");
        $this->newLine();
        $this->comment("Next steps:");
        $this->line("1. Define routes in app/Features/{$featureName}/web.php");
        if ($this->option('api')) {
            $this->line("2. Define API routes in app/Features/{$featureName}/api.php");
            $this->line("3. Create React components in resources/js/Pages/{$featureName}/");
        } else {
            $this->line("2. Create React components in resources/js/Pages/{$featureName}/");
        }

        return self::SUCCESS;
    }

    protected function createDirectoryStructure(string $path, string $featureName): void
    {
        // Create directories
        File::ensureDirectoryExists("{$path}/Controllers");
        File::ensureDirectoryExists("{$path}/Models");
        File::ensureDirectoryExists("{$path}/Requests");
        File::ensureDirectoryExists("{$path}/Services");

        if ($this->option('api')) {
            File::ensureDirectoryExists("{$path}/Resources");
        }

        // Create web.php route file
        $this->createRouteFile($path, $featureName);

        // Create API route file if requested
        if ($this->option('api')) {
            $this->createApiRouteFile($path, $featureName);
            $this->createApiController($path, $featureName);
        }

        // Create example controller
        $this->createController($path, $featureName);

        // Create React page directory
        $this->createReactPage($featureName);
    }

    protected function createRouteFile(string $path, string $featureName): void
    {
        $routeName = Str::kebab($featureName);
        $controllerName = "{$featureName}Controller";

        $stub = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Features\\{$featureName}\Controllers\\{$controllerName};

Route::prefix('{$routeName}')->name('{$routeName}.')->group(function () {
    Route::get('/', [{$controllerName}::class, 'index'])->name('index');
    Route::get('/create', [{$controllerName}::class, 'create'])->name('create');
    Route::post('/', [{$controllerName}::class, 'store'])->name('store');
    Route::get('/{id}/edit', [{$controllerName}::class, 'edit'])->name('edit');
    Route::put('/{id}', [{$controllerName}::class, 'update'])->name('update');
    Route::delete('/{id}', [{$controllerName}::class, 'destroy'])->name('destroy');
});

PHP;

        File::put("{$path}/web.php", $stub);
    }

    protected function createController(string $path, string $featureName): void
    {
        $controllerName = "{$featureName}Controller";
        $routeName = Str::kebab($featureName);

        $stub = <<<PHP
<?php

namespace App\Features\\{$featureName}\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class {$controllerName} extends Controller
{
    /**
     * Display the feature index page
     */
    public function index(): Response
    {
        return Inertia::render('{$featureName}/Index', [
            // Pass data to your React component
        ]);
    }

    /**
     * Show the form for creating a new resource
     */
    public function create(): Response
    {
        return Inertia::render('{$featureName}/Create');
    }

    /**
     * Store a newly created resource
     */
    public function store(Request \$request): RedirectResponse
    {
        // Validate request
        // \$validated = \$request->validate([]);
        
        // Store data
        
        return redirect()
            ->route('{$routeName}.index')
            ->with('success', 'Created successfully!');
    }

    /**
     * Show the form for editing a resource
     */
    public function edit(string \$id): Response
    {
        return Inertia::render('{$featureName}/Edit', [
            // 'item' => YourModel::findOrFail(\$id),
        ]);
    }

    /**
     * Update the specified resource
     */
    public function update(Request \$request, string \$id): RedirectResponse
    {
        // Validate request
        // \$validated = \$request->validate([]);
        
        // Update data
        
        return back()->with('success', 'Updated successfully!');
    }

    /**
     * Remove the specified resource
     */
    public function destroy(string \$id): RedirectResponse
    {
        // Delete data
        
        return back()->with('success', 'Deleted successfully!');
    }
}

PHP;

        File::put("{$path}/Controllers/{$controllerName}.php", $stub);
    }

    protected function createReactPage(string $featureName): void
    {
        $pagePath = resource_path("js/Pages/{$featureName}");
        File::ensureDirectoryExists($pagePath);

        $stub = <<<JSX
import { Head } from '@inertiajs/react';

export default function Index() {
    return (
        <>
            <Head title="{$featureName}" />
            
            <div className="container mx-auto px-4 py-8">
                <h1 className="text-4xl font-bold mb-4">{$featureName}</h1>
                <p className="text-gray-600">Your feature page is ready!</p>
            </div>
        </>
    );
}

JSX;

        File::put("{$pagePath}/Index.jsx", $stub);
    }

    protected function createApiRouteFile(string $path, string $featureName): void
    {
        $routeName = Str::kebab($featureName);
        $controllerName = "{$featureName}ApiController";

        $stub = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Features\\{$featureName}\Controllers\\{$controllerName};

Route::prefix('{$routeName}')->name('{$routeName}.')->group(function () {
    Route::get('/', [{$controllerName}::class, 'index'])->name('index');
    Route::post('/', [{$controllerName}::class, 'store'])->name('store');
    Route::get('/{id}', [{$controllerName}::class, 'show'])->name('show');
    Route::put('/{id}', [{$controllerName}::class, 'update'])->name('update');
    Route::delete('/{id}', [{$controllerName}::class, 'destroy'])->name('destroy');
});

PHP;

        File::put("{$path}/api.php", $stub);
    }

    protected function createApiController(string $path, string $featureName): void
    {
        $controllerName = "{$featureName}ApiController";

        $stub = <<<PHP
<?php

namespace App\Features\\{$featureName}\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class {$controllerName} extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(Request \$request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'List retrieved successfully',
            'data' => [],
        ]);
    }

    /**
     * Store a newly created resource
     */
    public function store(Request \$request): JsonResponse
    {
        // Validate request
        // \$validated = \$request->validate([]);
        
        return response()->json([
            'success' => true,
            'message' => 'Created successfully',
            'data' => [],
        ], 201);
    }

    /**
     * Display the specified resource
     */
    public function show(string \$id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Resource retrieved successfully',
            'data' => [],
        ]);
    }

    /**
     * Update the specified resource
     */
    public function update(Request \$request, string \$id): JsonResponse
    {
        // Validate request
        // \$validated = \$request->validate([]);
        
        return response()->json([
            'success' => true,
            'message' => 'Updated successfully',
            'data' => [],
        ]);
    }

    /**
     * Remove the specified resource
     */
    public function destroy(string \$id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully',
        ]);
    }
}

PHP;

        File::put("{$path}/Controllers/{$controllerName}.php", $stub);
    }
}
