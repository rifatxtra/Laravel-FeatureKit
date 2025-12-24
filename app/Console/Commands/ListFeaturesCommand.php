<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListFeaturesCommand extends Command
{
    protected $signature = 'feature:list';

    protected $description = 'List all features in the application';

    public function handle(): int
    {
        $featuresPath = app_path('Features');

        if (!File::exists($featuresPath)) {
            $this->warn('No features found.');
            return self::SUCCESS;
        }

        $features = File::directories($featuresPath);

        if (empty($features)) {
            $this->warn('No features found.');
            return self::SUCCESS;
        }

        $this->info('Available Features:');
        $this->newLine();

        $tableData = [];

        foreach ($features as $featurePath) {
            $featureName = basename($featurePath);
            $hasRoutes = File::exists("{$featurePath}/web.php");
            $hasApiRoutes = File::exists("{$featurePath}/api.php");
            $controllersCount = count(File::glob("{$featurePath}/Controllers/*.php") ?: []);
            $modelsCount = count(File::glob("{$featurePath}/Models/*.php") ?: []);

            $tableData[] = [
                $featureName,
                $hasRoutes ? '✓' : '✗',
                $hasApiRoutes ? '✓' : '✗',
                $controllersCount,
                $modelsCount,
            ];
        }

        $this->table(
            ['Feature', 'Web Routes', 'API Routes', 'Controllers', 'Models'],
            $tableData
        );

        return self::SUCCESS;
    }
}
