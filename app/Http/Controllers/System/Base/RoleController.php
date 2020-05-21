<?php

namespace App\Http\Controllers\System\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        return view('system.base.role.index');
    }

    public function lists()
    {
        return $this->paginate(
            Role::query()->paginate()
        );
    }

    public function create()
    {
        return view('system.base.role.create');
    }

    public function store(Request $request)
    {
        Role::create([
            'name' => $request->get('name'),
        ]);

        return $this->succeed();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $role = Role::find($id);

        return view('system.base.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role       = Role::find($id);
        $role->name = $request->get('name');
        $role->save();

        return $this->succeed();
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();

        return $this->succeed();
    }

    public function showAssignPermissionForm($id)
    {
        $role        = Role::find($id);
        $permissions = Permission::orderBy('name')->get();
        return view('system.base.role.assign_permission',
            compact('role', 'permissions'));
    }

    public function storeAssignRolePermission(Request $request)
    {
        $permissions = [];
        foreach ($request->all() as $name => $value) {
            if (substr($name, 0, 11) === 'permission[') {
                $permissions[] = $value;
            }
        }

        $role = Role::find($request->get('role_id'));
        $role->syncPermissions($permissions);

        return $this->succeed();
    }


}
