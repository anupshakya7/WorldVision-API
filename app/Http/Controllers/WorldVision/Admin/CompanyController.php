<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::paginate(10);
        $companiesMain = PaginationHelper::addSerialNo($companies);

        return view('worldvision.admin.dashboard.company.index', compact('companiesMain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('worldvision.admin.dashboard.company.create');
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
            'name' => 'required|string|max:255',
            'logo' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048'
        ]);

        if($request->hasFile('logo') && $request->file('logo')->isValid()){
            $logoPic = $request->file('logo');
            $logoPicName = Str::uuid().'.'.$logoPic->getClientOriginalExtension();
            
            //Store the file in Logo Directory
            $logoPicPath = $logoPic->storeAs('img/logo',$logoPicName,'public');
        }

        $validatedData['logo'] = $logoPicPath ?? null;

        //Create a new country
        $company = Company::create($validatedData);

        return redirect()->route('admin.company.index')->with('success', 'Company created successfully!!!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::find($id);

        return view('worldvision.admin.dashboard.company.view', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('worldvision.admin.dashboard.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048'
        ]);

        if($request->hasFile('logo') && $request->file('logo')->isValid()){
            Storage::delete('public/'.$company->logo);
            
            $logoPic = $request->file('logo');
            $logoPicName = Str::uuid().'.'.$logoPic->getClientOriginalExtension();

            //Store the file in profile directory
            $logoPicPath = $logoPic->storeAs('img/logo',$logoPicName,'public');
        }

        $validatedData['logo'] = $logoPicPath ?? null;

        //Create a new country
        $company = $company->update($validatedData);

        return redirect()->route('admin.company.index')->with('success', 'Company updated successfully!!!');
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
}
