<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureControllerCommand extends Command
{
    protected $signature = 'make:feature-controller {feature} {name}';

    protected $description = 'Create a new controller in a feature';

    public function handle(): int
    {
        $feature = Str::studly($this->argument('feature'));
        $name = Str::studly($this->argument('name'));
        $controllerName = Str::endsWith($name, 'Controller') ? $name : "{$name}Controller";

        $featurePath = app_path("Features/{$feature}");
        
        if (!File::exists($featurePath)) {
            $this->error("Feature '{$feature}' does not exist!");
            $this->comment("Create it first: php artisan make:feature {$feature}");
            return self::FAILURE;
        }

        $controllerPath = "{$featurePath}/Controllers/{$controllerName}.php";

        if (File::exists($controllerPath)) {
            $this->error("Controller '{$controllerName}' already exists in feature '{$feature}'!");
            return self::FAILURE;
        }

        File::ensureDirectoryExists("{$featurePath}/Controllers");

        $stub = <<<PHP
<?php

namespace App\Features\\{$feature}\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class {$controllerName} extends Controller
{
    /**
     * Handle the incoming request
     */
    public function __invoke(Request \$request): Response
    {
        return Inertia::render('{$feature}/Page', [
            // Your data here
        ]);
    }
}

PHP;

        File::put($controllerPath, $stub);

        $this->info("Controller created successfully!");
        $this->line("Location: app/Features/{$feature}/Controllers/{$controllerName}.php");

        return self::SUCCESS;
    }
}
