<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureExceptionCommand extends Command
{
    protected $signature = 'make:feature:exception {feature : e.g. Auth or Dashboard/Admin} {name : e.g. InvalidCredentialException}';
    protected $description = 'Create an exception inside a feature';

    public function handle(): void
    {
        $feature = $this->argument('feature');
        $name    = Str::studly($this->argument('name'));
        $name    = Str::endsWith($name, 'Exception') ? $name : $name . 'Exception';

        $parts     = array_map(fn($p) => Str::studly($p), explode('/', $feature));
        $namespace = 'App\\Features\\' . implode('\\', $parts) . '\\Exceptions';
        $path      = app_path('Features/' . implode('/', $parts) . "/Exceptions/{$name}.php");

        if (File::exists($path)) {
            $this->error("Exception [{$name}] already exists!");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->stub($namespace, $name));

        $this->info("✅ Exception [{$name}] created at app/Features/{$feature}/Exceptions/{$name}.php");
    }

    private function stub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use App\Core\Exceptions\BaseException;

class {$name} extends BaseException
{
    //
}
PHP;
    }
}