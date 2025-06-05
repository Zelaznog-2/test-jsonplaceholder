<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;


class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-cache-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the application cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            Cache::flush();
            $this->info('Application cache cleared successfully.');
        } catch (\Exception $e) {
            $this->error('Failed to clear application cache: ' . $e->getMessage());
        }
    }
}
