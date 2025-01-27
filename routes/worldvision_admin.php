<?php

use App\Http\Controllers\WorldVision\Admin\Authorize\PermissionController;
use App\Http\Controllers\WorldVision\Admin\Authorize\RoleController;
use App\Http\Controllers\WorldVision\Admin\CategoryColorController;
use App\Http\Controllers\WorldVision\Admin\CountryController;
use App\Http\Controllers\WorldVision\Admin\CountryDataController;
use App\Http\Controllers\WorldVision\Admin\IndexController;
use App\Http\Controllers\WorldVision\Admin\SourceController;
use App\Http\Controllers\WorldVision\Admin\IndicatorController;
use App\Http\Controllers\WorldVision\Admin\ProjectController;
use App\Http\Controllers\WorldVision\Admin\SubCountryController;
use App\Http\Controllers\WorldVision\Admin\SubCountryDataController;
use App\Http\Controllers\WorldVision\Admin\UserController;
use App\Http\Controllers\WorldVision\Auth\LoginController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth','check_company'])->group(function () {
    //Dashboard
    Route::get('/', [IndexController::class,'dashboard'])->name('home');

    //User Management
    //User
    Route::resource('users',UserController::class);
    Route::get('users/{user}/roles',[UserController::class,'userRoles'])->name('users.roles');
    Route::post('users/{user}/roles',[UserController::class,'assignRole'])->name('users.roles.assign');
    Route::delete('users/{user}/roles/{role}',[UserController::class,'removeRole'])->name('users.roles.remove');
    Route::post('users/{user}/permissions',[UserController::class,'givePermission'])->name('users.permissions.assign');
    Route::post('users/{user}/permissions/{permission}',[UserController::class,'revokePermission'])->name('users.permissions.remove');

    //Role
    Route::resource('roles',RoleController::class);
    Route::post('roles/{role}/permissions',[RoleController::class,'assignPermission'])->name('roles.permissions');
    Route::delete('roles/{role}/permissions/{permission}',[RoleController::class,'removePermission'])->name('roles.permissions.remove');

    //Permission
    Route::resource('permissions',PermissionController::class);
    Route::post('permissions/{permission}/roles',[PermissionController::class,'assignRole'])->name('permissions.roles');
    Route::delete('permissions/{permission}/roles/{role}',[PermissionController::class,'removeRole'])->name('permissions.roles.remove');


    //Category Colors
    Route::resource('category-color',CategoryColorController::class);

    //Country Management
    //Country
    Route::resource('country', CountryController::class);

    //Country Data
    Route::resource('country-data',CountryDataController::class);
    Route::prefix('country-data')->name('country-data.')->group(function(){
        //Export
        Route::get('generate/csv',[CountryDataController::class,'generateCSV'])->name('generate.csv');

        //Import
        Route::get('bulk/insert',[CountryDataController::class,'bulk'])->name('bulk');
        Route::post('bulk/insert',[CountryDataController::class,'bulkInsert'])->name('bulk.insert');
    });

    //Sub Country Management
    //Sub Country
    Route::resource('sub-country', SubCountryController::class);

    //Sub Country Data
    Route::resource('sub-country-data',SubCountryDataController::class);
    Route::prefix('sub-country-data')->name('sub-country-data.')->group(function(){
        //Export
        Route::get('generate/csv',[SubCountryDataController::class,'generateCSV'])->name('generate.csv');

        //Import
        Route::get('bulk/insert',[SubCountryDataController::class,'bulk'])->name('bulk');
        Route::post('bulk/insert',[SubCountryDataController::class,'bulkInsert'])->name('bulk.insert');
    });

    //Indicator
    Route::resource('indicator', IndicatorController::class);

    //Source
    Route::resource('source',SourceController::class);

    //Project
    Route::resource('project',ProjectController::class);
});
