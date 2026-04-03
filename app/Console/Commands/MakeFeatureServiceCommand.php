<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureServiceCommand extends Command
{
    protected $signature = 'make:feature:service {feature : e.g. Auth or Dashboard/Admin} {name : e.g. LoginService}';
    protected $description = 'Create a service inside a feature';

    public function handle(): void
    {
        $feature = $this->argument('feature');
        $name    = Str::studly($this->argument('name'));
        $name    = Str::endsWith($name, 'Service') ? $name : $name . 'Service';

        $parts     = array_map(fn($p) => Str::studly($p), explode('/', $feature));
        $namespace = 'App\\Features\\' . implode('\\', $parts) . '\\Services';
        $path      = app_path('Features/' . implode('/', $parts) . "/Services/{$name}.php");

        if (File::exists($path)) {
            $this->error("Service [{$name}] already exists!");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->stub($namespace, $name));

        $this->info("✅ Service [{$name}] created at app/Features/{$feature}/Services/{$name}.php");
    }

    private function stub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use App\Core\BaseService;

class {$name} extends BaseService
{
    //
}
PHP;
    }
}