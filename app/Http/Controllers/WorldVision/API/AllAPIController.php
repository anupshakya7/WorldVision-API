<?php

namespace App\Http\Controllers\WorldVision\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\CategoryColor;
use App\Models\Admin\Country;
use App\Models\Admin\Indicator;
use App\Models\Admin\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllAPIController extends Controller
{
     // //Parent Data
     public function parentData(Request $request){
          $validator = Validator::make($request->all(), [
               'region_id' => 'nullable|integer',
               'country_id' => 'nullable',
               'sub_country_id' => 'nullable'
          ]);

          if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()]);
          }

          if($request->filled('sub_country_id') || $request->filled('country_id')){
               $table = 'sub_countries';
               $select = ['id','geoname as title','geocode as geo_code'];
          }else{
               $table = 'countries';
               $select = ['id','country as title','country_code as geo_code'];
          }

          $countryQuery = DB::table($table)->select($select);
          $parentQuery = Country::select('id','country','country_code');

          if($request->filled(['region_id','country_id','sub_country_id'])){
               $parent = $parentQuery->where('country_code',$request->country_id)->first();
               $country = $countryQuery->where('countrycode',$request->country_id)->where('geocode',$request->sub_country_id)->get();
          }elseif($request->filled(['region_id','country_id'])){
               $parent = $parentQuery->where('country_code',$request->country_id)->first();
               $country = $countryQuery->where('countrycode',$request->country_id)->get();
          }elseif($request->filled(['region_id'])){
               $parent = $parentQuery->where('id',$request->region_id)->first();
               $country = $countryQuery->where('level',1)->where('parent_id',$request->region_id)->get();
          }else{
               $parent = null;
               $country = $countryQuery->where('level',0)->get();
          }


          if(!empty($parent)){
			return response()->json([
				'success' => true,
				'parent'=>$parent,
				'data' => $country
			], 200);
		}else{
			$countries = Country::where('level',1)->get(['id','country_code','country as title','parent_id']);
			return response()->json([
				'success' => true,
				'parent'=>$parent,
				'data' => $country,
				'countries'=>$countries
			], 200);
		}
     }

     //Map Data
    public function mapData(Request $request){
          $validator = Validator::make($request->all(), [
               'region_id' => 'nullable|integer',
               'country_id' => 'nullable|string',
               'sub_country_id' => 'nullable|string',
               'indicator_id' => 'required|integer',
               'sub_indicator_id' => 'nullable|integer',
               'year' => 'nullable|integer',
               'order' => 'nullable|string|min:3',
               'project'=>'nullable|integer'
          ]);
     
          if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()]);
          }

          $projects = [];
          if($request->filled('project')){
               if($request->filled('sub_country_id')){
                    $selectCountry = DB::raw('(SELECT geoname FROM sub_countries as country WHERE country.geocode=geocode LIMIT 1) as title');
               }else{
                    $selectCountry = DB::raw('(SELECT country FROM countries as country WHERE country.country_code=countrycode LIMIT 1) as title');
               }

             

               $project = Project::query();
               $project->select($selectCountry,'year','latitude','longitude','project_title','project_overview','link');
               
               if($request->indicator_id != 1 && !$request->filled('sub_indicator_id')){
                    $project->where('indicator_id',$request->indicator_id);
               }

               if($request->filled(['region_id','country_id','sub_country_id','indicator_id','sub_indicator_id'])){
                    $project->where('geocode',$request->sub_country_id)->where('subindicator_id',$request->sub_indicator_id);
               }elseif($request->filled(['region_id','country_id','indicator_id','sub_indicator_id'])){
                    $project->where('countrycode',$request->country_id)->where('subindicator_id',$request->sub_indicator_id);
               }elseif($request->filled(['region_id', 'indicator_id', 'sub_indicator_id'])){
                    $project->where('region_id',$request->region_id)->where('subindicator_id',$request->sub_indicator_id);
               }elseif($request->filled(['region_id','country_id','sub_country_id','indicator_id'])){
                    $project->where('geocode',$request->sub_country_id);
               }elseif($request->filled(['region_id', 'country_id', 'indicator_id'])){
                    $project->where('countrycode',$request->country_id);
               }elseif($request->filled(['region_id','indicator_id'])){
                    $project->where('region_id',$request->region_id);
               }elseif($request->filled(['indicator_id','sub_indicator_id'])){
                    $project->where('subindicator_id',$request->sub_indicator_id);
               }
               $projects = $project->distinct()->get();
          }
          
          //Set Year if not provided
          $year = $request->year ?? 2023;

          if($request->filled('sub_country_id') || $request->filled('country_id')){
               $table = 'sub_countries';
               $dataTable = 'sub_country_data';
               $joinFirstColumn ='c.geocode';
               $joinSecondColumn = 'cd.geocode';
               $select = ['c.id','c.countrycode','c.geoname as title','c.geocode as geo_code','c.geometry','cd.year as data_year','cd.raw as country_score','cd.admin_col as country_color','cd.statements as statements'];
          }else{
               $table = 'countries';
               $dataTable = 'country_data';
               $joinFirstColumn ='c.country_code';
               $joinSecondColumn = 'cd.countrycode';
               $select = ['c.id','c.parent_id','c.level','c.country as title','c.country_code as geo_code','c.latitude','c.longitude','c.geometry','cd.year as data_year','cd.country_score','cd.country_col as country_color','cd.country_cat as country_category'];
          }

          //Map Data
          $mapQuery = DB::table($table." as c")->join($dataTable.' as cd',$joinFirstColumn,'=',$joinSecondColumn)->select($select);

          //Parent Data
          $parentQuery = DB::table('countries as c');

          if($request->filled(['region_id','country_id','sub_country_id','indicator_id','sub_indicator_id'])){
               $mapQuery->where('c.geocode',$request->sub_country_id)->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->select('c.id','c.level','c.country as title','c.country_code as geo_code','c.latitude','c.longitude')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id','country_id','indicator_id','sub_indicator_id'])){
               $mapQuery->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->select('c.id','c.level','c.country as title','c.country_code as geo_code','c.latitude','c.longitude')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id', 'indicator_id', 'sub_indicator_id'])){
               $mapQuery->where('c.parent_id',$request->region_id)->where('c.level',1)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->select('c.id','c.level','c.country as title','c.country_code as geo_code','c.latitude','c.longitude')->where('c.id',$request->region_id)->first();
          }elseif($request->filled(['region_id','country_id','sub_country_id','indicator_id'])){
               $mapQuery->where('c.geocode',$request->sub_country_id)->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->select('c.id','c.level','c.country as title','c.country_code as geo_code','c.latitude','c.longitude')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id', 'country_id', 'indicator_id'])){
               $mapQuery->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->select('c.id','c.level','c.country as title','c.country_code as geo_code','c.latitude','c.longitude')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id','indicator_id'])){
               $mapQuery->where('c.parent_id',$request->region_id)->where('c.level',1)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->select('c.id','c.level','c.country as title','c.country_code as geo_code','c.latitude','c.longitude')->where('c.id',$request->region_id)->first();
          }elseif($request->filled(['indicator_id','sub_indicator_id'])){
               $mapQuery->where('c.level',1)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = null;
          }elseif($request->filled('indicator_id')){
               $mapQuery->where('c.level',1)->where('cd.indicator_id',$request->indicator_id);
               $parent = null;
          }

          $map = $mapQuery->where('cd.year',$year)->get();

          //Color Data
          if($request->filled('sub_country_id') || $request->filled('country_id')){
               $colors = CategoryColor::select('country_leg_col','subcountry_leg_col')->where('subcountry_leg_col',$map[0]->country_color)->first();
               $mainColor = $colors->country_leg_col;
               
               $category_color = CategoryColor::select('subcountry_col_order as level','subcountry_leg_col as color')->where('country_leg_col',$mainColor)->get();     
          }else{
               $category_color = CategoryColor::select('country_col_order as level','country_leg_col as color')->distinct()->get();
          }

          return response()->json([
               'success'=>true,
               'parent'=>$parent,
               'color'=>$category_color,
               'project'=>$projects,
               'count'=>count($map),
               'data'=>$map
          ]);
    }

    //Indicator Data
     public function indicatorScore(Request $request){
          $validator = Validator::make($request->all(),[
               'region_id'=>'nullable|integer',
               'country_id'=>'nullable|string',
               'sub_country_id'=>'nullable|string',
               'indicator_id'=>'required|integer',
               'sub_indicator_id'=>'nullable|integer',
               'year'=>'nullable|integer'
          ]);
          
          if($validator->fails()){
               return response()->json(['errors'=>$validator->errors()]);
          }
          
          //Set Year if Choose
          $year = $request->year ?? 2023;
          
          $parentQuery = DB::table('countries')->select('id','country as title','parent_id');
          
          if($request->filled('sub_country_id')){
               $table = 'sub_countries';
               $dataTable = 'sub_country_data';
               $joinFirstColumn ='c.geocode';
               $joinSecondColumn = 'cd.geocode';
               $select = ['c.id','c.countrycode','c.geoname as title','c.geocode as geo_code','cd.year as data_year','cd.raw as country_score','cd.statements as statements'];
               $scoreField = 'cd.raw';
          }else{
               $table = 'countries';
               $dataTable = 'country_data';
               $joinFirstColumn ='c.country_code';
               $joinSecondColumn = 'cd.countrycode';
               $select = ['c.id','c.country as title','c.country_code as geo_code','cd.year as data_year','cd.country_score'];
               $scoreField = 'cd.country_score';
          }

          $indicatorQuery = DB::table($table." as c")->join($dataTable.' as cd',$joinFirstColumn,'=',$joinSecondColumn)->select($select)->where('cd.year',$year);

          if($request->filled(['region_id','country_id','sub_country_id'])){
               $indicatorQuery->where('c.countrycode','=',$request->country_id)->where('c.geocode','=',$request->sub_country_id);
               $parent = $parentQuery->where('country_code',$request->country_id)->first();
               
          }
          elseif($request->filled(['region_id','country_id'])){
               $indicatorQuery->where('c.country_code','=',$request->country_id);
               $parent = $parentQuery->where('country_code',$request->country_id)->first();
          }
          elseif($request->filled('region_id')){
               $indicatorQuery->where('c.parent_id','=',$request->region_id);
               $parent = $parentQuery->where('id',$request->region_id)->first();
          }else{
               $parent = $parentQuery->where('id',$request->region_id)->first();
          }

          //Indicator Lists
          if($request->filled('indicator_id')){
               if($request->indicator_id == 1){
                    $indicators = Indicator::select('id','variablename as title')->where('level',0)->where('company_id',1)->where('id','!=',1)->get();
               }else{
                    $indicators = Indicator::select('id','variablename as title')->where('level',1)->where('domain_id',$request->indicator_id)->get();
               }
          }else{
               $indicators = Indicator::select('id','variablename as title')->where('level',0)->get();
          }
                
          $indicatorsScore = [];
          
          foreach($indicators as $indicator){
               $currentQuery = clone $indicatorQuery;
               $indicatorSum = $currentQuery->where('cd.indicator_id',$indicator->id)->sum($scoreField);
               
               $indicatorCount = $currentQuery->where('cd.indicator_id',$indicator->id)->count();
               
          if ($indicatorCount > 0) {
               $indicatorScore = $indicatorSum / $indicatorCount;

               // Normalize score to range from 0 to 5
               $normalizedScore = min(max($indicatorScore, 0), 5); // Ensures score is between 0 and 5
          } else {
               $normalizedScore = 0;
          }

          $indicatorsScore[] = [
               'id'=>$indicator->id,
               'name'=>$indicator->title,
               'score'=>number_format($normalizedScore,2)
          ];
          }
          
          return response()->json([
               'success'=>true,
               'parent'=>$parent,
               'data'=>$indicatorsScore
          ]);
     }

     //Country Subcountry Score
     public function countryScore(Request $request){
          $validator = Validator::make($request->all(),[
			'region_id'=>'nullable|integer',
			'country_id'=>'nullable|string',
			'sub_country_id'=>'nullable|string',
			'year'=>'nullable|integer',
			'indicator_id'=>'required|integer',
			'sub_indicator_id'=>'nullable|integer',
			'order'=>'nullable|string'
		]);
		
		if($validator->fails()){
			return response()->json(['errors'=>$validator->errors()]);
		}
		
		//Set Year if Choose
		$year = $request->year ?? 2023;
		
          if($request->filled('sub_country_id') || $request->filled('country_id')){
               $table = 'sub_countries';
               $dataTable = 'sub_country_data';
               $joinFirstColumn ='c.geocode';
               $joinSecondColumn = 'cd.geocode';
               $select = ['c.id','c.countrycode','c.geoname as title','c.geocode as geo_code','cd.raw as country_score'];
          }else{
               $table = 'countries';
               $dataTable = 'country_data';
               $joinFirstColumn ='c.country_code';
               $joinSecondColumn = 'cd.countrycode';
               $select = ['c.id','c.country as title','c.country_code as geo_code','cd.country_score','c.parent_id'];
          }

		//Parent Country if there is any
		$parentQuery = DB::table('countries as c')->select('id','country as title');
          $countryScore = DB::table($table.' as c')->join($dataTable.' as cd',$joinFirstColumn,'=',$joinSecondColumn)->select($select);

          if($request->filled(['region_id','country_id','sub_country_id','indicator_id','sub_indicator_id'])){
               $countryScore->where('c.geocode',$request->sub_country_id)->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id','country_id','indicator_id','sub_indicator_id'])){
               $countryScore->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id', 'indicator_id', 'sub_indicator_id'])){
               $countryScore->where('c.parent_id',$request->region_id)->where('c.level',1)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->where('c.id',$request->region_id)->first();
          }elseif($request->filled(['region_id','country_id','sub_country_id','indicator_id'])){
               $countryScore->where('c.geocode',$request->sub_country_id)->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id', 'country_id', 'indicator_id'])){
               $countryScore->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id','indicator_id'])){
               $countryScore->where('c.parent_id',$request->region_id)->where('c.level',1)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->where('c.id',$request->region_id)->first();
          }elseif($request->filled(['indicator_id','sub_indicator_id'])){
               $countryScore->where('c.level',1)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = null;
          }elseif($request->filled('indicator_id')){
               $countryScore->where('c.level',1)->where('cd.indicator_id',$request->indicator_id);
               $parent = null;
          }
		
          $results = $countryScore->where('cd.year',$year)->get();

		return response()->json([
			'status'=>true,
			'count'=>count($results),
			'parent'=>$parent,
			'data'=>$results
		]);	
     }

     //Train Graph 
     public function trainGraph(Request $request){
          $validator = Validator::make($request->all(),[
			'region_id'=>'nullable|integer',
			'country_id'=>'nullable|string',
			'sub_country_id'=>'nullable|string',
			'indicator_id'=>'required|integer',
			'sub_indicator_id'=>'nullable|integer',
               'from'=>'required|integer',
               'to'=>'required|integer',
		]);
		
		if($validator->fails()){
			return response()->json(['errors'=>$validator->errors()]);
		}
		
		$year = $request->year ?? 2023;
          $from = $request->from;
          $to = $request->to;
		
          if($request->filled('sub_country_id') || $request->filled('country_id')){
               $table = 'sub_countries';
               $dataTable = 'sub_country_data';
               $joinFirstColumn ='c.geocode';
               $joinSecondColumn = 'cd.geocode';
               $scoreField = 'cd.raw';
               $select = ['c.id','c.countrycode','c.geoname as title','c.geocode as geo_code','cd.raw as country_score'];
          }else{
               $table = 'countries';
               $dataTable = 'country_data';
               $joinFirstColumn ='c.country_code';
               $joinSecondColumn = 'cd.countrycode';
               $scoreField = 'cd.country_score';
               $select = ['c.id','c.country as title','c.country_code as geo_code','cd.country_score','cd.year'];
          }

		//Parent Country if there is any
          $countryQuery = DB::table($table.' as c')->join($dataTable.' as cd',$joinFirstColumn,'=',$joinSecondColumn)->select($select);

          if($request->filled(['region_id','country_id','sub_country_id','indicator_id','sub_indicator_id'])){
               $countryQuery->where('cd.indicator_id',$request->sub_indicator_id)->where('c.countrycode',$request->country_id)->where('c.geocode',$request->sub_country_id);
          }elseif($request->filled(['region_id','country_id','indicator_id','sub_indicator_id'])){
               $countryQuery->where('cd.indicator_id',$request->sub_indicator_id)->where('c.countrycode',$request->country_id);
          }elseif($request->filled(['region_id', 'indicator_id', 'sub_indicator_id'])){
               $countryQuery->where('cd.indicator_id',$request->sub_indicator_id)->where('c.parent_id',$request->region_id);
          }elseif($request->filled(['region_id','country_id','sub_country_id','indicator_id'])){
               $countryQuery->where('cd.indicator_id',$request->indicator_id)->where('c.countrycode',$request->country_id)->where('c.geocode',$request->sub_country_id);
          }elseif($request->filled(['region_id', 'country_id', 'indicator_id'])){
               $countryQuery->where('cd.indicator_id',$request->indicator_id)->where('c.countrycode',$request->country_id);
          }elseif($request->filled(['region_id','indicator_id'])){
               $countryQuery->where('cd.indicator_id',$request->indicator_id)->where('c.parent_id',$request->region_id);
          }elseif($request->filled(['indicator_id','sub_indicator_id'])){
               $countryQuery->where('cd.indicator_id',$request->sub_indicator_id);
          }elseif($request->filled('indicator_id')){
               $countryQuery->where('cd.indicator_id',$request->indicator_id);
          }
		
          // $results = $countryQuery->get();
          $results = [];

          for($i=$from;$i<=$to;$i++){
               $countryCloneData = clone $countryQuery;
               $resultCount = $countryCloneData->where('cd.year',$i)->count();
               $resultCount = $resultCount === 0 ? 1 : $resultCount;
               $resultScore = $countryCloneData->where('cd.year',$i)->sum($scoreField);
               $result = $resultScore/$resultCount;

               $results[$i] = $result;
          }

		return response()->json([
			'success'=>true,
			'data'=>$results
		]);
	}

     //Summary API 
     public function summary(Request $request){
          $validator = Validator::make($request->all(), [
               'region_id' => 'nullable|integer',
               'country_id' => 'nullable',
               'sub_country_id' => 'nullable',
               'indicator_id' => 'required|integer',
               'sub_indicator_id' => 'nullable|integer',
               'year' => 'nullable|integer',
               'order' => 'nullable|string|min:3'
          ]);
     
          if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()]);
          }

          //Set Year if not provided
          $year = $request->year ?? 2023;

          if($request->filled('sub_country_id') || $request->filled('country_id')){
               $table = 'sub_countries';
               $dataTable = 'sub_country_data';
               $joinFirstColumn ='c.geocode';
               $joinSecondColumn = 'cd.geocode';
               $select = ['c.id','c.countrycode','c.geoname as title','c.geocode as geo_code','cd.year as data_year','cd.raw as country_score','cd.statements as statements'];
          }else{
               $table = 'countries';
               $dataTable = 'country_data';
               $joinFirstColumn ='c.country_code';
               $joinSecondColumn = 'cd.countrycode';
               $select = ['c.id','c.parent_id','c.country as title','c.country_code as geo_code','cd.year as data_year','cd.country_score'];
          }

          //Map Data
          $summaryQuery = DB::table($table." as c")->join($dataTable.' as cd',$joinFirstColumn,'=',$joinSecondColumn)->select($select);

          //Parent Data
          $parentQuery = DB::table('countries as c');

          if($request->filled(['region_id','country_id','sub_country_id','indicator_id','sub_indicator_id'])){
               $summaryQuery->where('c.geocode',$request->sub_country_id)->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->select('c.id','c.country as title','c.country_code as geo_code')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id','country_id','indicator_id','sub_indicator_id'])){
               $summaryQuery->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->select('c.id','c.country as title','c.country_code as geo_code')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id', 'indicator_id', 'sub_indicator_id'])){
               $summaryQuery->where('c.parent_id',$request->region_id)->where('c.level',1)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = $parentQuery->select('c.id','c.country as title','c.country_code as geo_code')->where('c.id',$request->region_id)->first();
          }elseif($request->filled(['region_id','country_id','sub_country_id','indicator_id'])){
               $summaryQuery->where('c.geocode',$request->sub_country_id)->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->select('c.id','c.country as title','c.country_code as geo_code')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id', 'country_id', 'indicator_id'])){
               $summaryQuery->where('c.countrycode',$request->country_id)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->select('c.id','c.country as title','c.country_code as geo_code')->where('c.country_code',$request->country_id)->first();
          }elseif($request->filled(['region_id','indicator_id'])){
               $summaryQuery->where('c.parent_id',$request->region_id)->where('c.level',1)->where('cd.indicator_id',$request->indicator_id);
               $parent = $parentQuery->select('c.id','c.country as title','c.country_code as geo_code')->where('c.id',$request->region_id)->first();
          }elseif($request->filled(['indicator_id','sub_indicator_id'])){
               $summaryQuery->where('c.level',1)->where('cd.indicator_id',$request->sub_indicator_id);
               $parent = null;
          }elseif($request->filled('indicator_id')){
               $summaryQuery->where('c.level',1)->where('cd.indicator_id',$request->indicator_id);
               $parent = null;
          }

          $map = $summaryQuery->where('cd.year',$year)->get();

          return response()->json([
               'success'=>true,
               'parent'=>$parent,
               'count'=>count($map),
               'data'=>$map
          ]); 
     }

     //Download Data API
     public function downloadData(Request $request){
          $validator = Validator::make($request->all(), [
               'region_id' => 'nullable|integer',
               'country_id' => 'nullable',
               'sub_country_id' => 'nullable',
               'year' => 'nullable|integer'
          ]);
     
          if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()]);
          }

          //Set Year if not provided
          $year = $request->year ?? 2023;

          if($request->filled('sub_country_id') || $request->filled('country_id')){
               $table = 'sub_countries';
               $dataTable = 'sub_country_data';
               $joinFirstColumn ='c.geocode';
               $joinSecondColumn = 'cd.geocode';
               $select = [DB::raw('(SELECT country FROM countries AS parent WHERE parent.country_code = c.countrycode) AS parent_country'),'c.geoname as country','c.geocode as geo_code','i.domain','i.variablename as indicator','cd.raw as value','cd.year as data_year','cd.statements as statements'];
          }else{
               $table = 'countries';
               $dataTable = 'country_data';
               $joinFirstColumn ='c.country_code';
               $joinSecondColumn = 'cd.countrycode';
               $select = [DB::raw('(SELECT country FROM countries AS parent WHERE parent.id = c.parent_id) AS parent_country'),'c.country','c.country_code as geo_code','i.domain','i.variablename as indicator','cd.country_score as value','cd.year as data_year'];
          }

          //Map Data
          $downloadQuery = DB::table($table." as c")->join($dataTable.' as cd',$joinFirstColumn,'=',$joinSecondColumn)->join('indicators as i','cd.indicator_id','=','i.id')->select($select);


          if($request->filled(['region_id','country_id','sub_country_id'])){
               $downloadQuery->where('c.geocode',$request->sub_country_id)->where('c.countrycode',$request->country_id);
          }elseif($request->filled(['region_id','country_id'])){
               $downloadQuery->where('c.countrycode',$request->country_id);
          }elseif($request->filled(['region_id'])){
               $downloadQuery->where('c.parent_id',$request->region_id);
          }

          $downloadData = $downloadQuery->where('cd.year',$year)->get();

          return response()->json([
               'success'=>true,
               'count'=>count($downloadData),
               'data'=>$downloadData,
          ]);
     }

     //Project Pie Chart
     public function projectPieChart(Request $request){
          $validator = Validator::make($request->all(), [
               'region_id' => 'nullable|integer',
               'country_id' => 'nullable|string',
               'sub_country_id' => 'nullable|string',
               'indicator_id' => 'required|integer',
               'sub_indicator_id' => 'nullable|integer',
               'year' => 'nullable|integer',
               'order' => 'nullable|string|min:3',
               'project'=>'nullable|integer'
          ]);
     
          if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()]);
          }

          //Indicators
          $indicatorQuery = Indicator::query();
          $indicatorQuery->select('id','variablename as title');
          if($request->filled('indicator_id')){
              if($request->indicator_id == 1){
                    $indicatorQuery->where('level',0)->whereNot('variablename','Overall Score')->where('company_id',1);
                    $selectField = 'indicator_id';
               } else{
                    $indicatorQuery->where('level',1)->where('domain_id',$request->indicator_id)->where('company_id',1);
                    $selectField = 'subindicator_id';
               }
          }

          $indicators = $indicatorQuery->get();

          $indicatorProjects = [];
          //Projects
          $projectsQuery = Project::query();
          
          if($request->filled(['region_id','country_id','sub_country_id'])){
               $projectsQuery->where('geocode',$request->sub_country_id);
          }elseif($request->filled(['region_id','country_id'])){
               $projectsQuery->where('countrycode',$request->country_id);
          }elseif($request->filled(['region_id'])){
               $projectsQuery->where('region_id',$request->region_id);
          }

          foreach($indicators as $indicator){
               $projectsClone = clone $projectsQuery;
               $selectedProject = $projectsClone->where($selectField,$indicator->id)->distinct()->count();
               
               $indicatorProjects[$indicator->title] = $selectedProject;
          }
          return $indicatorProjects;
     }    
}
