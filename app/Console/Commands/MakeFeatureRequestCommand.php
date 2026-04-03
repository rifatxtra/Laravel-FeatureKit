<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureRequestCommand extends Command
{
    protected $signature = 'make:feature:request {feature : e.g. Auth or Dashboard/Admin} {name : e.g. LoginRequest}';
    protected $description = 'Create a form request inside a feature';

    public function handle(): void
    {
        $feature = $this->argument('feature');
        $name    = Str::studly($this->argument('name'));
        $name    = Str::endsWith($name, 'Request') ? $name : $name . 'Request';

        $parts     = array_map(fn($p) => Str::studly($p), explode('/', $feature));
        $namespace = 'App\\Features\\' . implode('\\', $parts) . '\\Requests';
        $path      = app_path('Features/' . implode('/', $parts) . "/Requests/{$name}.php");

        if (File::exists($path)) {
            $this->error("Request [{$name}] already exists!");
            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->stub($namespace, $name));

        $this->info("✅ Request [{$name}] created at app/Features/{$feature}/Requests/{$name}.php");
    }

    private function stub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Foundation\Http\FormRequest;

class {$name} extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
PHP;
    }
}