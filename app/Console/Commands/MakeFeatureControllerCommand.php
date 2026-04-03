<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureControllerCommand extends Command
{
    protected $signature = 'make:feature:controller {feature : e.g. Auth or Dashboard/Admin} {name : e.g. LoginController}';
    protected $description = 'Create a controller inside a feature';

    public function handle(): void
    {
        $feature = $this->argument('feature');
        $name    = Str::studly($this->argument('name'));
        $name    = Str::endsWith($name, 'Controller') ? $name : $name . 'Controller';

        $parts      = array_map(fn($p) => Str::studly($p), explode('/', $feature));
        $namespace  = 'App\\Features\\' . implode('\\', $parts) . '\\Controllers';
        $path       = app_path('Features/' . implode('/', $parts) . "/Controllers/{$name}.php");

        if (File::exists($path)) {
            $this->error("Controller [{$name}] already exists!");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->stub($namespace, $name));

        $this->info("✅ Controller [{$name}] created at app/Features/{$feature}/Controllers/{$name}.php");
    }

    private function stub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use App\Core\BaseController;

class {$name} extends BaseController
{
    //
}
PHP;
    }
}