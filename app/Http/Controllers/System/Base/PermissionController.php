<?php

namespace App\Http\Controllers\System\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function index()
    {
        return view('system.base.permission.index');
    }

    public function lists()
    {
        return $this->paginate(
            Permission::query()->paginate()
        );
    }

    public function create()
    {
        return view('system.base.permission.create');
    }

    public function store(Request $request)
    {
        Permission::create([
            'name'       => $request->get('name'),
            'guard_name' => config('auth.guards.SystemUser.name'),
        ]);

        return $this->succeed();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $permission = Permission::find($id);

        return view('system.base.permission.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission       = Permission::find($id);
        $permission->name = $request->get('name');
        $permission->save();

        return $this->succeed();
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();

        return $this->succeed();
    }
}
