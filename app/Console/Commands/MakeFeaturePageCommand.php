<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFeaturePageCommand extends Command
{
    protected $signature = 'make:feature-page {feature} {name}';

    protected $description = 'Create a new React page component for a feature';

    public function handle(): int
    {
        $feature = Str::studly($this->argument('feature'));
        $name = Str::studly($this->argument('name'));

        $pagePath = resource_path("js/Pages/{$feature}");
        File::ensureDirectoryExists($pagePath);

        $componentPath = "{$pagePath}/{$name}.jsx";

        if (File::exists($componentPath)) {
            $this->error("Page component '{$name}' already exists in '{$feature}'!");
            return self::FAILURE;
        }

        $stub = <<<JSX
import { Head } from '@inertiajs/react';

export default function {$name}() {
    return (
        <>
            <Head title="{$name}" />
            
            <div className="container mx-auto px-4 py-8">
                <h1 className="text-4xl font-bold mb-4">{$name}</h1>
                <p className="text-gray-600">Feature: {$feature}</p>
            </div>
        </>
    );
}

JSX;

        File::put($componentPath, $stub);

        $this->info("Page component created successfully!");
        $this->line("Location: resources/js/Pages/{$feature}/{$name}.jsx");

        return self::SUCCESS;
    }
}
