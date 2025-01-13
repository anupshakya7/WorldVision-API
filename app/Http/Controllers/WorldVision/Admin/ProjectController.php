<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\Indicator;
use App\Models\Admin\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::distinct()->paginate(10);
        $projects = PaginationHelper::addSerialNo($projects);

        return view('worldvision.admin.dashboard.project.index',compact('projects'));
    }

    public function create(){
        $regions = Country::select('id','country')->where('level',0)->get();
        $domains = Indicator::select('id','variablename')->filterIndicator()->where('level',0)->get();

        return view('worldvision.admin.dashboard.project.create',compact('regions','domains'));
    }

    //Filter Country and Sub Country
    public function filterCountrySubCountry(Request $request){
        $validator = Validator::make($request->all(),[
            'region'=>'required|integer',
            'country'=>'nullable|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        //For Table and Select Fields
        if($request->filled('country')){
            $table = 'sub_countries';
            $select = ['geocode as id','geoname as title']; 
        }else{
            $table = 'countries';
            $select = ['country_code as id','country as title'];
        }

        //DB Query
        $countriesQuery = DB::table($table)->select($select);

        //Filter
        if($request->filled(['region','country'])){
            $countriesQuery->where('countrycode',$request->country); 
        }elseif($request->filled('region')){
            $countriesQuery->where('parent_id',$request->region);
        }

        //Get Data
        $result = $countriesQuery->get();

        if($result){
            return response()->json([
                'success'=>true,
                'data'=>$result
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Data Not Found'
            ]);
        }
        
    }

    //Filter Indicator
    public function filterIndicator(Request $request){
        $validator = Validator::make($request->all(),[
            'domain'=>'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        //DB Query
        $indicatorQuery = DB::table('indicators')->select(['id','variablename as title'])->where('company_id',2);

        //Filter
        if($request->filled('domain')){
            $indicatorQuery->where('domain_id',$request->domain); 
        }

        //Get Data
        $result = $indicatorQuery->get();

        if($result){
            return response()->json([
                'success'=>true,
                'data'=>$result
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Data Not Found'
            ]);
        }
        
    }
}
