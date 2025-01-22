<?php

use App\Http\Controllers\ATI\Admin\CountryData\VoiceOfPeopleController;
use App\Http\Controllers\ATI\API\AllAPIController;
use App\Http\Controllers\ATI\API\MapAPIController;
use App\Http\Controllers\WorldVision\Admin\ProjectController;
use App\Http\Controllers\WorldVision\API\AllAPIController as APIAllAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//World Vision
//Get Country and SubCountry
Route::get('/get-country-sub-country',[ProjectController::class,'filterCountrySubCountry'])->name('getCountrySubCountry');

//Get Indicator
Route::get('/get-indicator',[ProjectController::class,'filterIndicator'])->name('getIndicator');

//Check User
Route::post('/check-user',[APIAllAPIController::class,'checkUser']);

//Parent Data -> Country Data
Route::get('parent-data',[APIAllAPIController::class,'parentData']);

//Map Postman -> Test API
Route::get('/map-data',[APIAllAPIController::class,'mapData']);

//Indicator Score -> Main Indicator Score
Route::get('/indicator-score',[APIAllAPIController::class,'indicatorScore']);

//Country and Sub Country Score For Table -> CountryList
Route::get('/country-subcountry-score',[APIAllAPIController::class,'countryScore']);

//Train Graph -> Train Graph Optimize
Route::get('/train-graph',[APIAllAPIController::class,'trainGraph']);

//Summary API
Route::get('/summary',[APIAllAPIController::class,'summary']);

//Download Data
Route::get('/download-data',[APIAllAPIController::class,'downloadData']);

//Project Piechart
Route::get('/project-piechart',[APIAllAPIController::class,'projectPieChart']);

//ATI API
//Check Voice of People
Route::get('/check-voice-people',[VoiceOfPeopleController::class,'checkCountryYearWise'])->name('check.voice.people');

//Map
Route::get('map',[AllAPIController::class,'mapAPI']);

//Domain Result and Voice Of People API
Route::get('domain-voice',[AllAPIController::class,'domainVoiceAPI']);

//Radar Chart Domain and Indicator Compare Africa and Country and Indicator Trend Chart
Route::get('radar-trend-chart',[AllAPIController::class,'radarTrendChartDomainIndicator']);

//Domain Score and Governance Vs Enabling Graph
Route::get('domain-governance-compare',[AllAPIController::class,'domainGovernanceCompare']);

//Risk Outlook
Route::get('risk-outlook',[AllAPIController::class,'riskOutlookAPI']);