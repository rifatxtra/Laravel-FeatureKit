<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureEventCommand extends Command
{
    protected $signature = 'make:feature:event {feature : e.g. Auth or Dashboard/Admin} {name : e.g. UserLoggedIn}';
    protected $description = 'Create an event inside a feature';

    public function handle(): void
    {
        $feature = $this->argument('feature');
        $name    = Str::studly($this->argument('name'));

        $parts     = array_map(fn($p) => Str::studly($p), explode('/', $feature));
        $namespace = 'App\\Features\\' . implode('\\', $parts) . '\\Events';
        $path      = app_path('Features/' . implode('/', $parts) . "/Events/{$name}.php");

        if (File::exists($path)) {
            $this->error("Event [{$name}] already exists!");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->stub($namespace, $name));

        $this->info("✅ Event [{$name}] created at app/Features/{$feature}/Events/{$name}.php");
    }

    private function stub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class {$name}
{
    use Dispatchable, SerializesModels;

    public function __construct()
    {
        //
    }
}
PHP;
    }
}