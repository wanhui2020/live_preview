<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class PermissionGenerateCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Permission::count() > 10) {
            return;
        }

        $guardName = config('auth.guards.SystemUser.name');
        foreach (config('permission_name') as $groupName => $group) {
            foreach ($group as $item) {
                Permission::findOrCreate("{$groupName}/{$item}", $guardName);
            }
        }
    }
}
