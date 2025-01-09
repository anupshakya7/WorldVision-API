@extends('ati.admin.dashboard.layout.web')
@php
    if($slug == 'elections'){
        $title = 'Upcoming Election';
        $homeroute = route('admin.ati.elections.index');
        // $sampledoc = ;
    }elseif($slug == 'disruptions'){
        $title = 'Historical Disruption';
        $homeroute = route('admin.ati.disruptions.index');
        // $sampledoc =;
    }elseif($slug == 'domain-score'){
        $title = 'Domain Score';
        $homeroute = route('admin.ati.domain-score.index');
        // $sampledoc =;
    }elseif($slug == 'indicator-score'){
        $title = 'Indicator Score';
        $homeroute = route('admin.ati.indicator-score.index');
        // $sampledoc =;
    }
@endphp
@section('title',  $title.' Bulk Import')
@section('content')
<style>
    .error {
        color: red;
    }

    .hidden {
        display: none;
    }

    .has-error .invalid-feedback {
        display: block;
        font-size: 16px;
    }

    .has-error .form-control {
        border: 1px solid red;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" rel="stylesheet">
<!-- Import table plugin specific stylesheet -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/ui/trumbowyg.table.min.css">
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{$title}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item">{{ 'Country Data'}}</li>
                                <li class="breadcrumb-item"><a href="{{$homeroute}}">{{$title}}</a></li>
                                <li class="breadcrumb-item active">{{ 'Import '.$title}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-info">
                                <p class="mb-0">
                                    <span class="fw-semibold">NOTES:</span><br>
                                    <span id="note">*** Please insert csv file same as sample format for successful entry  *****
                                    </span>
                                </p>
                                  <a href="#" class="btn btn-primary ms-2 my-3" id="sample-btn" style="pointer-events:none;color:grey" download="">
                                <i class="ri-download-line"></i> Download Sample
                            </a>
                                
                            </div>
			            </div>
			        </div>
			    </div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-header border-bottom-dashed">
							<h5 class="card-title mb-0">Import {{$title}}</h5>
						</div>

						<div class="card-body">
						    
							<form action="{{route('admin.ati.country-data.bulkInsert.submit')}}" method="POST" enctype="multipart/form-data">
								@csrf
                                <div class="row">
                                    <div class="col-12">
                                        <label for="type">Type</label>
                                        <select class="form-control form-select" id="type"
                                            name="type">
                                            <option value="">None</option>
                                            <option value="election">Upcoming Election</option>
                                            <option value="disruption">Historical Disruption</option>
                                            <option value="domain-score">Domain Score</option>
                                            <option value="indicator-score">Indicator Score</option>
                                            <option value="voice-people">Voice Of People</option>
                                        </select>
                                        @if($errors->has('type'))
                                        <em class="invalid-feedback">
                                            {{ $errors->first('type') }}
                                        </em>
                                        @endif
                                    </div>
									<div class="col-12" style="margin-top:12px;">
									    <label for="myfile">Select a file: <span
                                            style="color:red;">*</span></label>
                                        <input type="file" class="form-control" name="csv_file">
									</div>
								</div>					
		
								<div class="col-12 text-end" style="margin-top:15px;">
									<button class="btn btn-success" type="submit" id="uploadButton">
										<i class="ri-save-line"></i> Import
									</button>  
								</div>                        
						</form>
					</div>				
				</div>
			</div>
		</div>
        </div>
    </div>

    @endsection
    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
    <!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/trumbowyg.table.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('#body-desc').trumbowyg({btns: [
			['viewHTML'],
			['formatting'],
			['strong', 'em', 'del'],
			['superscript', 'subscript'],
			['link'],
			['image'], // Our fresh created dropdown
			['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
			['unorderedList', 'orderedList'],
			['horizontalRule'],
			['removeformat'],
			['fullscreen'],
			['table'], 
			['tableCellBackgroundColor', 'tableBorderColor']
			]});

            $('#type').change(function(){
                let type = $('#type').val();
                let sampleBtn = $('#sample-btn');

                if(type!==""){
                    if(type == "election"){
                        sampleBtn.attr('href','{{asset("sample/ATI/election.csv")}}');
                    }

                    if(type == "disruption"){
                        sampleBtn.attr('href','{{asset("sample/ATI/disruption.csv")}}');
                    }

                    if(type == "domain-score"){
                        sampleBtn.attr('href','{{asset("sample/ATI/domain-score.csv")}}');
                    }

                    if(type == "indicator-score"){
                        sampleBtn.attr('href','{{asset("sample/ATI/indicator-score.csv")}}');
                    }

                    if(type == "voice-people"){
                        sampleBtn.attr('href','{{asset("sample/ATI/voice-people.csv")}}');
                    }

                    sampleBtn.css({'pointer-events': 'auto','color': '#fff'});
                }else{
                    sampleBtn.attr('href','#');
                    sampleBtn.css({'pointer-events':'none','color':'grey'});
                }
            });
        });

      var _url = "settings";
      @if(Session::has("message"))
        toastr.success("{{session('message')}}")
      @endif

    </script>
    @endsection