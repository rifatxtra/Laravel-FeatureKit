<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureObserverCommand extends Command
{
    protected $signature = 'make:feature:observer {feature : e.g. Auth or Dashboard/Admin} {name : e.g. UserObserver}';
    protected $description = 'Create an observer inside a feature';

    public function handle(): void
    {
        $feature = $this->argument('feature');
        $name    = Str::studly($this->argument('name'));
        $name    = Str::endsWith($name, 'Observer') ? $name : $name . 'Observer';

        $parts     = array_map(fn($p) => Str::studly($p), explode('/', $feature));
        $namespace = 'App\\Features\\' . implode('\\', $parts) . '\\Observers';
        $path      = app_path('Features/' . implode('/', $parts) . "/Observers/{$name}.php");

        if (File::exists($path)) {
            $this->error("Observer [{$name}] already exists!");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->stub($namespace, $name));

        $this->info("✅ Observer [{$name}] created at app/Features/{$feature}/Observers/{$name}.php");
    }

    private function stub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Model;

class {$name}
{
    public function creating(Model \$model): void {}
    public function created(Model \$model): void {}
    public function updating(Model \$model): void {}
    public function updated(Model \$model): void {}
    public function deleting(Model \$model): void {}
    public function deleted(Model \$model): void {}
}
PHP;
    }
}