<?php

use App\Http\Controllers\ATI\Admin\Authorize\PermissionController;
use App\Http\Controllers\ATI\Admin\Authorize\RoleController;
use App\Http\Controllers\ATI\Admin\CountryController;
use App\Http\Controllers\ATI\Admin\CountryData\DisruptionController;
use App\Http\Controllers\ATI\Admin\CountryData\DomainScoreController;
use App\Http\Controllers\ATI\Admin\CountryData\ElectionController;
use App\Http\Controllers\ATI\Admin\CountryData\IndicatorScoreController;
use App\Http\Controllers\ATI\Admin\CountryData\VoiceOfPeopleController;
use App\Http\Controllers\ATI\Admin\CountryDataController;
use App\Http\Controllers\ATI\Admin\IndexController;
use App\Http\Controllers\ATI\Admin\IndicatorController;
use App\Http\Controllers\ATI\Admin\SourceController;
use App\Http\Controllers\ATI\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check_company'])->group(function () {
    Route::get('/', [IndexController::class, 'dashboard'])->name('home');

    //User Management
    //User
    Route::resource('users', UserController::class);
    Route::get('users/{user}/roles', [UserController::class, 'userRoles'])->name('users.roles');
    Route::post('users/{user}/roles', [UserController::class, 'assignRole'])->name('users.roles.assign');
    Route::delete('users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.roles.remove');
    Route::post('users/{user}/permissions', [UserController::class, 'givePermission'])->name('users.permissions.assign');
    Route::post('users/{user}/permissions/{permission}', [UserController::class, 'revokePermission'])->name('users.permissions.remove');

    //Role
    Route::resource('roles', RoleController::class);
    Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermission'])->name('roles.permissions');
    Route::delete('roles/{role}/permissions/{permission}', [RoleController::class, 'removePermission'])->name('roles.permissions.remove');

    //Permission
    Route::resource('permissions', PermissionController::class);
    Route::post('permissions/{permission}/roles', [PermissionController::class, 'assignRole'])->name('permissions.roles');
    Route::delete('permissions/{permission}/roles/{role}', [PermissionController::class, 'removeRole'])->name('permissions.roles.remove');

    //Country Management
    //Country
    Route::resource('country', CountryController::class);
    
    //Country Data
    Route::prefix('country-data')->group(function () {
        //Upcoming Elections
        Route::resource('elections', ElectionController::class);

        //Historical Disruptions
        Route::resource('disruptions', DisruptionController::class);

        //Domain Score
        Route::resource('domain-score', DomainScoreController::class);

        //Indicator Score
        Route::resource('indicator-score', IndicatorScoreController::class);
        
        //Voice Of People
        Route::resource('voice-people', VoiceOfPeopleController::class);
        
        //Bulk Insert Country Data
        Route::get('import-data/{slug}',[CountryDataController::class,'bulk'])->name('country-data.bulkInsert');
        Route::post('import-data',[CountryDataController::class,'bulkInsert'])->name('country-data.bulkInsert.submit');
    });



    // Route::prefix('country-data')->name('country-data.')->group(function(){
    //     //Export
    //     Route::get('generate/csv',[CountryDataController::class,'generateCSV'])->name('generate.csv');

    //     //Import
    //     Route::get('bulk/insert',[CountryDataController::class,'bulk'])->name('bulk');
    //     Route::post('bulk/insert',[CountryDataController::class,'bulkInsert'])->name('bulk.insert');
    // });

    //Indicator
    Route::resource('indicator', IndicatorController::class);

    //Source
    Route::resource('ati-source',SourceController::class);
    
});
