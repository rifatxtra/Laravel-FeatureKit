<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeatureModelCommand extends Command
{
    protected $signature = 'make:feature-model {feature} {name} {--migration}';

    protected $description = 'Create a new model in a feature';

    public function handle(): int
    {
        $feature = Str::studly($this->argument('feature'));
        $name = Str::studly($this->argument('name'));

        $featurePath = app_path("Features/{$feature}");
        
        if (!File::exists($featurePath)) {
            $this->error("Feature '{$feature}' does not exist!");
            $this->comment("Create it first: php artisan make:feature {$feature}");
            return self::FAILURE;
        }

        $modelPath = "{$featurePath}/Models/{$name}.php";

        if (File::exists($modelPath)) {
            $this->error("Model '{$name}' already exists in feature '{$feature}'!");
            return self::FAILURE;
        }

        File::ensureDirectoryExists("{$featurePath}/Models");

        $stub = <<<PHP
<?php

namespace App\Features\\{$feature}\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected \$fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected \$casts = [];
}

PHP;

        File::put($modelPath, $stub);

        $this->info("Model created successfully!");
        $this->line("Location: app/Features/{$feature}/Models/{$name}.php");

        if ($this->option('migration')) {
            $table = Str::snake(Str::pluralStudly($name));
            $this->call('make:migration', [
                'name' => "create_{$table}_table",
            ]);
        }

        return self::SUCCESS;
    }
}
