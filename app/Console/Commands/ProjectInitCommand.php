<?php

namespace App\Console\Commands;

use App\Models\PlatformEdition;
use App\Models\SystemConfig;
use Artisan;
use Illuminate\Console\Command;

class ProjectInitCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init current project [Clear database test data]';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure you want to delete user data?[y/N]:')) {
            return;
        }
        $this->clearData();
        $this->updateConfig();
    }

    protected function clearData()
    {
        (new ClearDatabaseCommand())->work();
        Artisan::call('clear:test-data');
    }

    protected function updateConfig()
    {
        PlatformEdition::whereIn('id', [1, 2])->update(['status' => 1]);
        SystemConfig::where('id', 1)->update([
            'name'   => config('app.name'),
            'domain' => config('app.url'),
        ]);
    }

}
