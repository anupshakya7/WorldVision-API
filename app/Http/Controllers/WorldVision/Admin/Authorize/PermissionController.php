<?php

namespace App\Http\Controllers\WorldVision\Admin\Authorize;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::paginate(10);

        return view('worldvision.admin.dashboard.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('worldvision.admin.dashboard.permissions.create');
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
        $permissions = Permission::create($validatedData);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::find($id);

        return view('worldvision.admin.dashboard.permissions.view', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $roles = Role::whereNot('name','admin')->get();
        return view('worldvision.admin.dashboard.permissions.edit', compact('permission','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
        ]);

        //Create a new country
        $permission = $permission->update($validatedData);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully!!!');
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

       //Assign Role
       public function assignRole(Request $request,Permission $permission){
        if($permission->hasRole($request->role)){
            return redirect()->back()->with('error','Role exists.');
        }

        $permission->assignRole($request->role);
        return redirect()->back()->with('success','Role added.');
    }

    //Remove Permission
    public function removeRole(Permission $permission, Role $role){
        if($permission->hasRole($role)){
            $permission->removeRole($role);
            return redirect()->back()->with('success','Role removed.');
        }

        return redirect()->back()->with('error','Role not exists');
    }
}
