@extends('worldvision.admin.dashboard.layout.web')
@section('title','Project Update')
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
                        <h4 class="mb-sm-0">Project</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.project.index')}}">Project</a></li>
                                <li class="breadcrumb-item active">{{ 'Update Project'}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <h5 class="card-title mb-0">{{ 'Update Project' }}</h5>
                        </div>

                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <form action="{{ route('admin.project.update',$project) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="country">{{ 'Project' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="project_title" class="form-control"
                                                    value="{{ old('project_title',$project->project_title) }}" placeholder="Project">
                                                    @if($errors->has('project_title'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('project_title') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="bounding_box">Project Overview</label>
                                                <textarea class="form-control" id="project_overview" name="project_overview"
                                                    rows="6" placeholder="Project Overview">{{ old('project_overview',$project->project_overview) }}</textarea>
                                                @if($errors->has('project_overview'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('project_overview') }}
                                                </em>
                                                @endif
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="country">{{ 'Link' }}</label>
                                                    <input type="text" name="link" class="form-control"
                                                    value="{{ old('link',$project->link) }}" placeholder="Link">
                                                    @if($errors->has('link'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('link') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="indicator_id">{{ 'Domain' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="indicator_id"
                                                    name="indicator_id">
                                                        <option value="">None</option>
                                                        @foreach ($domains as $domain)
                                                        <option value="{{ $domain->id }}" {{$domain->id == $project->indicator_id ? 'selected':''}}>{{ $domain->variablename }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('indicator_id'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('indicator_id') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="subindicator_id">{{ 'Indicator' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="subindicator_id"
                                                    name="subindicator_id" disabled>
                                                        <option value="">None</option>
                                                    </select>
                                                    @if($errors->has('subindicator_id'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('subindicator_id') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="country">{{ 'Region' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="region_id"
                                                    name="region_id">
                                                        <option value="">None</option>
                                                        @foreach ($regions as $region)
                                                        <option value="{{ $region->id }}" {{$region->id == $project->region_id ? 'selected':''}}>{{ $region->country }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('region_id'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('region_id') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group" style="margin-top:20px;">
                                                    <label for="countrycode">{{ 'Country' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="countrycode"
                                                    name="countrycode" disabled>
                                                        <option value="">None</option>
                                                    </select>
                                                    @if($errors->has('countrycode'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('countrycode') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group" style="margin-top:20px;">
                                                    <label for="country">{{ 'Sub Country' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="geocode"
                                                    name="geocode" disabled>
                                                        <option value="">None</option>
                                                    </select>
                                                    @if($errors->has('geocode'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('geocode') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="latitude">Latitude<span
                                                    style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="latitude" name="latitude"
                                                    value="{{ old('latitude',$project->latitude) }}" placeholder="Latitude">
                                                @if($errors->has('latitude'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('latitude') }}
                                                </em>
                                                @endif
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="longitude">Longitude<span
                                                    style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="longitude" name="longitude"
                                                    value="{{ old('longitude',$project->longitude) }}" placeholder="Longitude">
                                                @if($errors->has('longitude'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('longitude') }}
                                                </em>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                @php
                                                    $currentYear = Carbon\Carbon::now()->year-1;
                                                @endphp
                                                <div class="form-group" style="margin-top:20px;">
                                                    <label for="year">{{ 'Year' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="year"
                                                    name="year">
                                                        <option value="">None</option>
                                                        @for($i=$currentYear;$i>$currentYear-7;$i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    @if($errors->has('year'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('year') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.project.index')}}">
                                        <i class="ri-arrow-left-line"></i> Back to list
                                    </a>
                                    <button class="btn btn-success float-end" type="submit" id="uploadButton">
                                        <i class="ri-save-line"></i> Save
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
			//$('#sifaris').trumbowyg();
        });

      var _url = "settings";
      @if(Session::has("message"))
        toastr.success("{{session('message')}}")
      @endif

    </script>
    <script>
        $(document).ready(function(){
            let regionValue = $('#region_id').val();
            let countryValue = $('#countrycode').val();
            let domainValue = $('#indicator_id').val();

            if(regionValue){
                country(regionValue);
            }

            if(domainValue){
                indicator(domainValue);
            }

            //Filter Country According to Region
            $('#region_id').change(function(){
                let region = $('#region_id').val();
                country(region);
            });
            
            //Filter SubCountry According to Country
            $('#countrycode').change(function(){
                let region = $('#region_id').val();
                let country = $('#countrycode').val();
                
                subcountry(region,country);
            });

            //Filter Indicator According to Domain
            $('#indicator_id').change(function(){
                let domain = $('#indicator_id').val();
                indicator(domain);
            });


            function country(region){
                let selectedCountry ='<?=$project->countrycode?>';
                if(region){
                    $.ajax({
                    url:'{{route('getCountrySubCountry')}}',
                    method:'GET',
                    data:{
                        region:region
                    },
                    success:function(response){
                        if(response.success){
                            $('#countrycode').html('');
                            $('#countrycode').html('<option value="">None</option>');
                            $('#countrycode').removeAttr('disabled');
                            console.log(response.data);
                            $.each(response.data,function(index,country){
                                //Check country
                                let isSelected = country.id == selectedCountry ? true:false;
                                //Add <option>
                                    $('#countrycode').append(
                                        $('<option></option')
                                            .attr('value',country.id)
                                            .text(country.title)
                                            .attr('selected',isSelected)
                                    )
                            });
                        }else{
                            $('#countrycode').html('');
                            $('#countrycode').html('<option value="">None</option>');
                            $('#countrycode').attr('disabled',true);
                            $('#geocode').html('');
                            $('#geocode').html('<option value="">None</option>');
                            $('#geocode').attr('disabled',true);
                        }
                    }
                });
                }else{
                    $('#countrycode').html('');
                    $('#countrycode').html('<option value="">None</option>');
                    $('#countrycode').attr('disabled',true);
                    $('#geocode').html('');
                    $('#geocode').html('<option value="">None</option>');
                    $('#geocode').attr('disabled',true);
                }
            }

            function subcountry(region,country){
                let selectedSubCountry ='<?=$project->geocode?>';

                if(region && country){
                    $.ajax({
                    url:'{{route('getCountrySubCountry')}}',
                    method:'GET',
                    data:{
                        region:region,
                        country:country
                    },
                    success:function(response){
                        if(response.success){
                            $('#geocode').html('');
                            $('#geocode').html('<option value="">None</option>');
                            $('#geocode').removeAttr('disabled');
                            $.each(response.data,function(index,country){
                                //Check sub country
                                let isSelected = country.id == selectedSubCountry ? true:false;

                                //Add <option>
                                    $('#geocode').append(
                                        $('<option></option')
                                            .attr('value',country.id)
                                            .text(country.title)
                                            .attr('selected',isSelected)
                                    )
                            });
                        }else{
                            $('#geocode').html('');
                            $('#geocode').html('<option value="">None</option>');
                            $('#geocode').attr('disabled',true);
                        }
                    }
                });
                }else{
                    $('#geocode').html('');
                    $('#geocode').html('<option value="">None</option>');
                    $('#geocode').attr('disabled',true);
                }
            }

            function indicator(domain){
                let selectedIndicator ='<?=$project->subindicator_id?>';
                if(domain){
                    $.ajax({
                    url:'{{route('getIndicator')}}',
                    method:'GET',
                    data:{
                        domain:domain
                    },
                    success:function(response){
                        if(response.success){
                            $('#subindicator_id').html('');
                            $('#subindicator_id').html('<option value="">None</option>');
                            $('#subindicator_id').removeAttr('disabled');
                            $.each(response.data,function(index,indicator){
                                 //Check sub country
                                 let isSelected = indicator.id == selectedIndicator ? true:false;

                                //Add <option>
                                    $('#subindicator_id').append(
                                        $('<option></option')
                                            .attr('value',indicator.id)
                                            .text(indicator.title)
                                            .attr('selected',isSelected)
                                    )
                            });
                        }else{
                            $('#subindicator_id').html('');
                            $('#subindicator_id').html('<option value="">None</option>');
                            $('#subindicator_id').attr('disabled',true);
                        }
                    }
                });
                }else{
                    $('#subindicator_id').html('');
                    $('#subindicator_id').html('<option value="">None</option>');
                    $('#subindicator_id').attr('disabled',true);
                }
            }
        });
    </script>
    @endsection