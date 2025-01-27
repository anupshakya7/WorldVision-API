<?php

namespace App\Http\Controllers\WorldVision\Admin\Authorize;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::whereNotIn('name',['admin'])->paginate(10);
        
        return view('worldvision.admin.dashboard.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('worldvision.admin.dashboard.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
        ]);

        //Create a new Role
        $roles = Role::create($validatedData);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        return view('worldvision.admin.dashboard.roles.view', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('worldvision.admin.dashboard.roles.edit', compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
        ]);

        //Create a new country
        $role = $role->update($validatedData);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //Assign Permission
    public function assignPermission(Request $request,Role $role){
        if($role->hasPermissionTo($request->permission)){
            return redirect()->back()->with('error','Permission exists.');
        }

        $role->givePermissionTo($request->permission);
        return redirect()->back()->with('success','Permission added.');
    }

    //Remove Permission
    public function removePermission(Role $role, Permission $permission){
        if($role->hasPermissionTo($permission)){
            $role->revokePermissionTo($permission);
            return redirect()->back()->with('success','Permission removed.');
        }

        return redirect()->back()->with('error','Permission not exists');
    }
}
