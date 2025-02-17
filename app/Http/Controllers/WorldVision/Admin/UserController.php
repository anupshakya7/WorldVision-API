<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('company')->paginate(10);
        return view('worldvision.admin.dashboard.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('worldvision.admin.dashboard.users.create',compact('companies'));
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
            'email' => 'required|email|unique:users,email',
            'company_id' => 'required|integer|exists:companies,id'
        ]);

        $validatedData['password'] = Hash::make('password');

        //Create a new Role
        $user = User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('worldvision.admin.dashboard.users.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('company')->find($id);
        $companies = Company::all();

        return view('worldvision.admin.dashboard.users.edit',compact('user','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'company_id' => 'required|integer|exists:companies,id'
        ]);

        //Create a new country
        $user = $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!!!');
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

    public function userRoles(User $user){
        $roles = Role::all();
        return view('worldvision.admin.dashboard.users.role',compact('user','roles'));
    }

    public function assignRole(Request $request,User $user){
        $request->validate([
            'role'=>'required|exists:roles,name'
        ]);

        $role = Role::findByName($request->role);

        if($user->hasRole($role)){
            return redirect()->back()->with('error','Role Exists.');
        }

        $user->assignRole($role);
        return redirect()->back()->with('success','Role Assigned.');
    }

    public function removeRole(User $user,Role $role){
        if($user->hasRole($role)){
            $user->removeRole($role);
            return redirect()->back()->with('success','Role removed.');
        }

        return redirect()->back()->with('error','Role not exists.');
    }
}
