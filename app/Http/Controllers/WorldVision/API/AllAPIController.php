<?php

namespace App\Http\Controllers\WorldVision\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\CategoryColor;
use App\Models\Admin\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllAPIController extends Controller
{
     //Map Data
    public function mapData(Request $request){
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

     //Country Subcountry Score -> Incomplete
     public function countryScore(Request $request){
          $validator = Validator::make($request->all(),[
			'region_id'=>'nullable|integer',
			'country_id'=>'nullable|string',
			'sub_country_id'=>'nullable|string',
			'year'=>'nullable|integer',
			'indicator_id'=>'nullable|integer',
			'sub_indicator_id'=>'nullable|integer',
			'order'=>'nullable|string'
		]);
		
		if($validator->fails()){
			return response()->json(['errors'=>$validator->errors()]);
		}
		
		//Set Year if Choose
		$year = $request->year ?? 2023;
		
		//Parent Country if there is any
		$parentQuery = DB::table('country_region')->select('id','title');
		
		if($request->filled('indicator_id')){
			$mapsQuery = DB::table('country_region as cr')->leftjoin('indicator_country_region as icr', function($join) use($year){
				$join->on('cr.id','=','icr.country_region_id')->where('icr.year','=',$year);
			});
			if($request->filled(['region_id','country_id','indicator_id'])){
				$mapsQuery->join('indicators as i','icr.indicator_id','=','i.id');
			}
			
			$mapsQuery->select('cr.id','cr.title','icr.value as country_score');
			
			if($request->filled('region_id')){
				//Child Country List if there is Any
				$childCountryLists = CountryRegion::query();
				$childCountryLists->select('id','title');
				if($request->filled(['region_id','country_id','sub_country_id','indicator_id','sub_indicator_id'])){
					$mapsQuery->where('icr.indicator_id',$request->sub_indicator_id)->where('icr.country_region_id',$request->sub_country_id);
					$parent = $parentQuery->where('id',$request->country_id)->get();
				}elseif($request->filled(['region_id','country_id','indicator_id','sub_indicator_id'])){
					$childIndicators = [];
					$childLists = $childCountryLists->where('parent_id','=',$request->country_id)->get();
					$level = 2;
					$indicator_value=$request->sub_indicator_id;
					$parent = $parentQuery->where('id',$request->country_id)->get();
				}elseif($request->filled(['region_id','indicator_id','sub_indicator_id'])){
					$childIndicators = [];
					$childLists = $childCountryLists->where('parent_id','=',$request->region_id)->get();
					$level = 1;
					$indicator_value=$request->sub_indicator_id;
					$parent = $parentQuery->where('id',$request->region_id)->get();
				}
				elseif($request->filled(['region_id','country_id','sub_country_id','indicator_id'])){
					$mapsQuery->where('icr.indicator_id',$request->indicator_id)->where('icr.country_region_id',$request->sub_country_id);
					$parent = $parentQuery->where('id',$request->country_id)->get();
				}
				elseif($request->filled(['region_id','country_id','indicator_id'])){
					$childIndicators = [];
					$childLists = $childCountryLists->where('parent_id','=',$request->country_id)->get();
					$level = 2;
					$indicator_value=$request->indicator_id;
					$parent = $parentQuery->where('id',$request->country_id)->get();
				}
				elseif($request->filled(['region_id','indicator_id'])){
					$childIndicators = [];
					$childLists = $childCountryLists->where('parent_id','=',$request->region_id)->get();
					$level = 1;
					$indicator_value=$request->indicator_id;
					$parent = $parentQuery->where('id',$request->region_id)->get();
				}else{
					$mapQuery->where('icr.year','=',$year);
				}
				
				if(!empty($childLists)){
					foreach($childLists as $childList){
						$currentQuery = clone $mapsQuery;
						$childIndicator = $currentQuery->where('cr.level','=',$level)->where('icr.indicator_id',$indicator_value)->where('icr.country_region_id',$childList->id)->where('icr.year','=',$year)->first();
						
						if(!empty($childIndicator)){
							$childIndicators[] = $childIndicator;
						}
					}
				}
			}else{
				$mapsQuery->where('cr.level','=',1)->where('icr.year','=',$year);
				if($request->filled(['indicator_id','sub_indicator_id'])){
					$mapsQuery->where('icr.indicator_id',$request->sub_indicator_id);
					$parent = null;
				}
				elseif($request->filled('indicator_id')){
					
					$mapsQuery->where('icr.indicator_id',$request->indicator_id);
					$parent = null;
				}
			}
		}else{
            $mapsQuery = DB::table('country_region as cr')
            ->leftJoin('country_user as cu', function ($join) use ($year) {
            $join->on('cr.id', '=', 'cu.country_id')
                 ->where('cu.data_year', '=', $year); // Apply year filter here
            })
            ->select('cr.id', 'cr.title', 'cu.country_score');

			
			if($request->filled(['region_id','country_id','sub_country_id'])){
				$mapsQuery->where('cr.level','=',2)->where('cr.parent_id','=',$request->country_id)->where('cr.id','=',$request->sub_country_id);
				$parent = $parentQuery->where('id',$request->country_id)->get();
			}
			elseif($request->filled(['region_id','country_id'])){
				$mapsQuery->where('cr.level','=',2)->where('cr.parent_id','=',$request->country_id);
				$parent = $parentQuery->where('id',$request->country_id)->get();
			}
			elseif($request->filled('region_id')){
				$mapsQuery->where('cr.level','=',1)->where('cr.parent_id','=',$request->region_id);
				$parent = $parentQuery->where('id',$request->region_id)->get();
			}else{
				$mapsQuery->where('cr.level','=',1);
				$parent = null;
			}
		}	
		
		//Order Lists
		$order = $request->order ?? "ASC";
				
		//Result For Country Score Lists
		if(isset($childIndicators)){
			if($order == "ASC"){
				usort($childIndicators, function($a,$b){
					return strcmp($a->country_score,$b->country_score);
				});
				$results = $childIndicators;
			}else{
				usort($childIndicators, function($a,$b){
					return strcmp($b->country_score,$a->country_score);
				});
				$results = $childIndicators;
			}
		}
		else{
			if($request->filled('indicator_id')){
				$results = $mapsQuery->orderBy('icr.value',$order)->distinct()->get();
			}else{
				$results = $mapsQuery->orderBy('cu.country_score',$order)->groupBy('cr.id')->get();
			}
		}
		
		/**foreach ($results as $result) {
			$result->geometry = json_decode($result->geometry);
		}**/
		
		return response()->json([
			'status'=>true,
			'count'=>count($results),
			'parent'=>$parent,
			'data'=>$results
		]);	
     }
}
