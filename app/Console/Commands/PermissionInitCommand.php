<?php

namespace App\Console\Commands;

use App\Models\SystemUser;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionInitCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:init';

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
        $role = Role::where([
            'name'       => '超级用户',
            'guard_name' => config('auth.guards.SystemUser.name'),
        ])->first();

        if ($role) {
            return;
        }

        if (!$role) {
            $role = Role::create([
                'name'       => '超级用户',
                'guard_name' => config('auth.guards.SystemUser.name'),
            ]);
        }

        $permissions = Permission::select('id')->pluck('id')->toArray();
        $role->syncPermissions($permissions);

        $user = SystemUser::find(1);
        $user->syncRoles([$role]);
    }

}
