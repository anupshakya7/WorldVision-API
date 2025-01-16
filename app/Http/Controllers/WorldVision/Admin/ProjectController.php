<?php

namespace App\Http\Controllers\WorldVision\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\Admin\Indicator;
use App\Models\Admin\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::with(['region','country','subcountry','domain','indicator','user'])->distinct()->paginate(10);
        $projects = PaginationHelper::addSerialNo($projects);

        return view('worldvision.admin.dashboard.project.index',compact('projects'));
    }

    public function create(){
        $regions = Country::select('id','country')->where('level',0)->get();
        $domains = Indicator::select('id','variablename')->filterIndicator()->whereNot('variablename','Overall Score')->where('level',0)->get();

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
        $indicatorQuery = DB::table('indicators')->select(['id','variablename as title'])->where('company_id',1);

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

    public function store(Request $request){
        $validatedData = $request->validate([
            'year'=>'required|integer|digits:4',
            'region_id'=>'required|exists:countries,id',
            'countrycode'=>'required|string',
            'geocode'=>'required|string',
            'latitude'=>'required|numeric|between:-90,90',
            'longitude'=>'required|numeric|between:-180,180',
            'project_title'=>'required|string',
            'project_overview'=>'nullable|string',
            'link'=>'nullable|url',
            'indicator_id'=>'required|exists:indicators,id',
            'subindicator_id'=>'required|exists:indicators,id',
        ]);
        

        //Adding Created By User Id
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['company_id'] = Auth::user()->company_id;

        //Create a new country
        $project = Project::create($validatedData);

        if($project){
            return redirect()->route('admin.project.index')->with('success', 'Project created successfully!!!');
        }else{
            return redirect()->back()->with('error', 'Fail to Create');
        }
       
    }

    public function show(Project $project){
        $project = $project->with(['region','country','subcountry','domain','indicator','user'])->first();
        
        if($project){
            return view('worldvision.admin.dashboard.project.view',compact('project'));
        }else{
            return redirect()->back()->with('error', 'Data Not Found');
        }
    }

    public function edit(Project $project){
        $regions = Country::select('id','country')->where('level',0)->get();
        $domains = Indicator::select('id','variablename')->filterIndicator()->whereNot('variablename','Overall Score')->where('level',0)->get();
    
        if($project){
            return view('worldvision.admin.dashboard.project.edit',compact('project','regions','domains'));
        }else{
            return redirect()->back()->with('error', 'Data Not Found');
        }
    }
}
