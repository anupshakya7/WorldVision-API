<?php

namespace App\Http\Controllers\ATI\API;

use App\Helpers\DomainPercentage;
use App\Http\Controllers\Controller;
use App\Models\Acled\Acled;
use App\Models\Admin\Country;
use App\Models\Admin\CountryData;
use App\Models\Admin\CountryDomainData;
use App\Models\Admin\Indicator;
use App\Models\Admin\Source;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllAPIController extends Controller
{
    //Upcoming Election and Historical Democratic Disruptions
    public function mapAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'political_type' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        if($request->filled('political_type')){
            $type = $request->political_type;
            
            if($type == 'election'){
                $map = Country::select(['countries.country','countries.country_code','country_data.year'])->leftJoin('country_data','countries.country_code','=','country_data.countrycode')->where('countries.ati',1)->where('country_data.political_context',0)->orderBy('country_data.year','asc')->distinct()->get();
            }elseif($type=='disruption'){
                $map = Country::select(['countries.country','countries.country_code','country_data.country_score as score'])->leftJoin('country_data','countries.country_code','=','country_data.countrycode')->where('countries.ati',1)->where('country_data.political_context',1)->orderBy('countries.country','asc')->distinct()->get();
            }
            
            if(isset($map)){
                return response()->json([
                    'success'=>true,
                    'map'=>$map
                ]);
            }else{
                return response()->json([
                    'success'=>false,
                    'message'=>'Please enter correct political type'
                ]);
            }
        }
    }

    //Domain Result and Voice Of People API
    public function domainVoiceAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'required|string|max:3',
            'year'=>'nullable'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }
        $year = $request->year ? $request->year : Carbon::now()->year; 

        if($request->filled('countrycode')){
            //Domain Data
            $countryScore = CountryData::where('countrycode',$request->countrycode)->where('indicator_id',87)->where('year',$year)->pluck('banded')->first();
            $domains = Indicator::where('level',0)->where('company_id',2)->whereNotIn('variablename',['Overall Score','ATI Governance'])->get();
            $domainResult = [];
            $domainTrendResult10Year = [];
            $domainMainResult = [];

            foreach($domains as $domain){
                for($i=(Carbon::now()->year-11);$i<Carbon::now()->year-1;$i++){
                    $score= CountryData::where('indicator_id',$domain->id)->where('countrycode',$request->countrycode)->where('year',$i)->pluck('banded')->first();
                    $domainTrendResult10Year[$i] = $score ? $score:0;
                }
                $domainResult = CountryData::select('countrycode','year','banded as score')->where('indicator_id',$domain->id)->where('countrycode',$request->countrycode)->where('year',$year)->latest()->first();
                $firstYearResult = CountryData::select('countrycode','year','banded as score')->where('indicator_id',$domain->id)->where('countrycode',$request->countrycode)->where('year',2013)->latest()->first();

                //Domain Percentage Calculation
                $domain_percentage_change = DomainPercentage::domainCalculation($firstYearResult->score,$domainResult->score);
                
                //Domain Type according to Domain Percentage
                $domainType = $this->domainType($domain_percentage_change);

                $domainMainResult[$domain->variablename] = [
                    'countrycode'=>isset($domainResult->countrycode)?$domainResult->countrycode:null,
                    'year'=>isset($domainResult->year)?$domainResult->year:null,
                    'score'=>isset($domainResult->score)?$domainResult->score:null,
                    // 'domain_result'=>isset($domainResult->domain_result)?$domainResult->domain_result:null,
                    'trend_result'=>isset($domainType)?$domainType:null,
                    'trend_percentage'=>isset($domain_percentage_change)?$domain_percentage_change:null,
                    'trend_10_year'=>$domainTrendResult10Year
                ];
            }
            //Domain Data

            //Voice of People Data
            $voiceOfPeoples = ["The Judicial System"=>106,"Politics"=>105,"Elections"=>94];
            $voiceOfPeopleResult = [];

            foreach($voiceOfPeoples as $key=>$voiceOfPeople){
                $indicatorScore = CountryData::where('political_context',2)->where('indicator_id',$voiceOfPeople)->where('countrycode',$request->countrycode)->where('year',$year)->pluck('banded')->first();
                $voiceOfPeopleResult[$key] = [
                    "countrycode"=>$request->countrycode,
                    "year"=>$year,
                    "score"=>$indicatorScore >0  ? ($indicatorScore/10)*100:0
                ];
            }

            return response()->json([
                'success'=>true,
                'overallscore'=>$countryScore,
                'domain_result'=>$domainMainResult,
                'voice_of_people'=>$voiceOfPeopleResult
            ]);
        }
        
    }

    //Check Domain Type according to Percentage
    public function domainType($domainScore){
        if($domainScore > 5){
            return 'Improving';
        }
        if($domainScore < -5){
            return 'Deteriorating';
        }

        if($domainScore >= -5 && $domainScore <= 5){
            return 'Stable';
        }
    }

    //Radar Chart Domain and Indicator
    public function radarTrendChartDomainIndicator(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'nullable|string|max:3',
            'domain_id' => 'required|integer',
            'year'=>'nullable',
            'graphType'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        //Graph Type
        $type = $request->graphType;
        
        //Year
        $year = $request->year;

        $indicator = Indicator::query();
        $indicatorData = CountryData::query();
        $indicatorData->where('political_context',2);

        if($request->filled('countrycode')){
            $indicatorData->where('countrycode',$request->countrycode);
        }
        
        $indicatorResult =[];
        if($type == 'radar'){
            $indicators = $indicator->where('domain_id',$request->domain_id)->get();

            foreach($indicators as $indicator){
                $indicatorQuery = clone $indicatorData;
                $indicatorQuery->where('year',$year);

                $indicatorCount = $indicatorQuery->where('indicator_id',$indicator->id)->count();
                $indicatorCount = $indicatorCount ? $indicatorCount : 1;

                $indicatorScore = $indicatorQuery->where('indicator_id',$indicator->id)->sum('banded');
                $indicatorResult[$indicator->variablename] = $indicatorScore / $indicatorCount;
            }
        }elseif($type == 'trend'){
            $indicators = $indicator->where('id',$request->domain_id)->where('level',1)->first();
            $indicatorYearResult=[];

            // return $indicatorData->count();

            if($indicators){
                for($i=2013;$i<=Carbon::now()->year-2;$i++){
                    $scoreQuery = clone $indicatorData;
                    // $indicatorScore = $scoreQuery->where('indicator_id',$request->domain_id)->where('year',$i)->pluck('country_score')->first();
                    // $indicatorCount = $scoreQuery->where('indicator_id',$request->domain_id)->where('year',$i)->count();
                    if($request->filled('countrycode')){
                        $indicatorScore = $scoreQuery->where('indicator_id',$request->domain_id)->where('countrycode',$request->countrycode)->where('year',$i)->pluck('banded')->first();
                    }else{
                        $indicatorBanded =  $scoreQuery->where('indicator_id',$request->domain_id)->where('year',$i)->sum('banded');
                        $indicatorCount =  $scoreQuery->where('indicator_id',$request->domain_id)->where('year',$i)->count();
                        
                        $indicatorScore = $indicatorBanded / $indicatorCount;
                    }

                    $indicatorYearResult[$i] = $indicatorScore ? ($indicatorScore/10)*100:0;
                }

                $source = Source::select('source')->where('indicator_id',$request->domain_id)->pluck('source')->first();

                $indicatorResult = [
                    'id'=>$indicators->id,
                    'title'=>$indicators->variablename,
                    'source'=>$source,
                    'description'=>$indicators->vardescription,
                    'trend_score'=>$indicatorYearResult
                ];   
            }
        }


        if(count($indicatorResult)>0){
            return response()->json([
                'success'=>true,
                'data'=>$indicatorResult
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>"Data Not Found"
            ]);
        }
    }

    //Domain Score and Governance Vs Enabling Graph
    public function domainGovernanceCompare(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'nullable|string|max:3',
            'year'=>'nullable',
            'graphType'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        //Graph Type
        $type = $request->graphType;

        $indicator = Indicator::query();
        $domainData = CountryData::query();
        $year = $request->year;
        $domainResult =[];

        if($request->filled('countrycode')){
            $domainData->where('countrycode',$request->countrycode);
        }
        
        if($type == 'domain'){
            $domains = $indicator->where('level',0)->whereNot('variablename','ATI Governance')->where('company_id',2)->get();

            $domain10YearResult = [];

            foreach($domains as $domain){
                for($i=(Carbon::now()->year-11);$i<=Carbon::now()->year-1;$i++){
                    if($domain->variablename){
                        $domainQuery = clone $domainData;
                        $domainScore = $domainQuery->where('indicator_id',$domain->id)->where('year',$i)->pluck('banded')->first();

                        $domainScore = $domainScore ? $domainScore:0;
                        $domain10YearResult[$i] = $domainScore;
                    }
                }

                $domainResult[$domain->variablename]=$domain10YearResult;
            }
        }elseif($type == 'governance'){
            $countries = Country::select(['country','country_code'])->where('ati',1)->get();
            $domains = Indicator::select('id','variablename')->where('level',0)->where('company_id',2)->whereIn('variablename',['ATI Governance','Enabling Environment'])->get();

            $domainScore = CountryData::query();

            foreach($countries as $country){
                $domainEachScore =[];

                foreach($domains as $domain){
                    $domainQuery = clone $domainScore;
                    $domainEnablingScore = $domainQuery->where('indicator_id',$domain->id)->where('countrycode',$country->country_code)->where('year',$year)->pluck('banded')->first();

                    $domainEnablingScore = $domainEnablingScore ? $domainEnablingScore : 0;
                    $domainEachScore[$domain->variablename] = $domainEnablingScore;
                }

                $domainResult[$country->country_code] = $domainEachScore;
            }
        }

        if(count($domainResult)>0){
            return response()->json([
                'success'=>true,
                'data'=>$domainResult
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Data Not Found'
            ]);
        }
    }

    //Risk Outlook
    public function riskOutlookAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'countrycode' => 'required|string|max:3',
            'year'=>'nullable',
            'search_text'=>'nullable',
            'per_page'=>'nullable'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }
    
        //Year
        $year = $request->year ? $request->year:Carbon::now()->year-1;

        //Type
        $type = $request->type;

        //Per Page
        $per_page = $request->per_page?$request->per_page:10;

        $indicators = Indicator::query();

        $indicators->select('indicators.id','indicators.variablename','country_data.countrycode','country_data.year','country_data.banded as score')->leftJoin('country_data','indicators.id','=','country_data.indicator_id')->where('indicators.level',1)->where('indicators.company_id',2)->where('countrycode',$request->countrycode)->where('country_data.year',$year);

        if($request->filled('search_text')){
            $indicators->where('indicators.variablename',$request->search_text);
        }
        
        $result = $indicators->orderBy('indicators.variablename','ASC')->distinct()->paginate($per_page);

        foreach($result as $item){
            $firstItem = CountryData::where('indicator_id',$item->id)->where('countrycode',$request->countrycode)->where('year',2013)->pluck('banded')->first();
            $indicatorData = CountryData::query();
            for($i=(Carbon::now()->year-11);$i<=Carbon::now()->year-1;$i++){
                $scoreQuery = clone $indicatorData;
                $indicatorScore = $scoreQuery->where('indicator_id',$item->id)->where('countrycode',$request->countrycode)->where('year',$i)->pluck('banded')->first();

                $indicatorYearResult[$i] = $indicatorScore ? ($indicatorScore/10)*100:0;
            }
            $allIndicatorBanded = $indicatorData->where('indicator_id',$item->id)->where('countrycode',$request->countrycode)->sum('banded');
            $allIndicatorCount = $indicatorData->where('indicator_id',$item->id)->where('countrycode',$request->countrycode)->count();
            $threshold =  $allIndicatorBanded / $allIndicatorCount;

            $item['percentage_change'] = DomainPercentage::domainCalculation($firstItem,$item->score);
            $item['sparkline'] = $indicatorYearResult;
            $item['threshold'] = $threshold;
        }

        if(count($result) > 0){
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

    //Acled API
    //Early Warning Factors
    public function acledMapEarlyWarnFactor(Request $request){
        $validatedData = Validator::make($request->all(),[
            'event_type'=>'nullable|string'
        ]);

        if($validatedData->fails()){
            return response()->json($validatedData->errors(),404);
        }

        $latestyear =  Acled::orderBy('event_date','DESC')->first('event_date');
        $endYear = $latestyear->event_date;
        $oneYearEarly =  Carbon::parse($latestyear->event_date)->subYear()->format('Y-m-d');

        $mapAcledQuery = DB::connection('mysql2')->table('aclied')->select(['event_date','event_type','fatalities','latitude','longitude','notes']);

        if($request->filled('event_type')){
            $mapAcledQuery->where('event_type',$request->event_type);
        }

        $cacheKey = 'map_data_'.md5($request->event_type.$oneYearEarly.$endYear);

        $result = cache()->remember($cacheKey,60*60*24,function() use($mapAcledQuery,$oneYearEarly,$endYear){
            return $mapAcledQuery->whereBetween('event_date',[$oneYearEarly,$endYear])->get();
        });

        return response()->json([
            'success'=>true,
            'count'=>count($result),
            'data'=>$result
        ]);
    }

    // Weekly Chart for Events and Fatalities
    public function chartEventsFatalities(Request $request){
        $validatedData = Validator::make($request->all(),[
            'type'=>'required|string'
        ]);

        if($validatedData->fails()){
            return response()->json($validatedData->errors(),404);
        }

        $type = $request->type;

        //Starting Date
        $latestData = Acled::select('event_date')->orderBy('event_date','DESC')->first();
        
        //Start Date and End Date
        $startdate = Carbon::parse($latestData->event_date)->subYear();
        $enddate = Carbon::parse($latestData->event_date);

        //Weekly Date
        $weeklyDates = [];

        //Adding 7 days at a time
        while($startdate < $enddate){
            $nextEndDate = $startdate->copy()->addWeek();

            //Add to the weekly dates array
            $weeklyDates[] = [
                'startdate'=>$startdate->format('Y-m-d'),
                'enddate'=>$nextEndDate->format('Y-m-d'),
            ];
            
            //Start Date to Pervious End Date
            $startdate = $nextEndDate;
        }

        $chartDataQuery = Acled::query();
        $result = [];

        if($type == 'event'){
            foreach($weeklyDates as $weekDate){
                $chartCloneData = clone $chartDataQuery;
                $result[$weekDate['startdate'].'-'.$weekDate['enddate']] = $chartCloneData->whereBetween('event_date',[$weekDate['startdate'],$weekDate['enddate']])->get();
            }
        }

        return $result;
    }

}
