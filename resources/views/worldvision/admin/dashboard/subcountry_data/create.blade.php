@extends('worldvision.admin.dashboard.layout.web')
@section('title','Sub Country Data Create')
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
                        <h4 class="mb-sm-0">Sub Country Data</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.country-data.index')}}">Country Data</a></li>
                                <li class="breadcrumb-item active">{{ 'Create Sub Country Data'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Add Sub Country Data' }}</h5>
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
                            <form action="{{ route('admin.sub-country-data.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="indicator_id">{{ 'Indicator' }} <span
                                                            style="color:red;">*</span></label>
                                                        <select class="form-control form-select" id="indicator_id"
                                                        name="indicator_id">
                                                            <option value="">None</option>
                                                            @foreach ($indicators as $indicator)
                                                            <option value="{{ $indicator->id }}">{{ $indicator->variablename }}</option>
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
                                                    <label for="geocode">{{ 'Sub Country' }} <span
                                                            style="color:red;">*</span></label>
                                                        <select class="form-control form-select" id="geocode"
                                                        name="geocode">
                                                            <option value="">None</option>
                                                            @foreach ($subcountries as $subcountry)
                                                            <option value="{{ $subcountry->geocode }}">{{ $subcountry->geoname }}</option>
                                                            @endforeach
                                                        </select>
                                                    @if($errors->has('geocode'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('geocode') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="source_id">{{ 'Source' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="source_id"
                                                    name="source_id">
                                                        <option value="">None</option>
                                                        @foreach ($sources as $source)
                                                        <option value="{{ $source->id }}">{{ $source->source }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('source_id'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('source_id') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="statements">Statement <span
                                                    style="color:red;">*</span></label>
                                                <textarea class="form-control" id="statements" name="statements"
                                                    rows="6">{{ old('statements') }}</textarea>
                                                @if($errors->has('statements'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('statements') }}
                                                </em>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="year">{{ 'Year' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="year"
                                                    name="year">
                                                        <option value="">None</option>
                                                        @for ($i=now()->year;$i>=2000;$i--)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                    @if($errors->has('year'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('year') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="raw">{{ 'Raw' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="raw" class="form-control"
                                                            value="{{ old('raw') }}" placeholder="Raw">
                                                    @if($errors->has('raw'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('raw') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="banded">{{ 'Banded' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="banded" class="form-control"
                                                            value="{{ old('banded') }}" placeholder="Banded">
                                                    @if($errors->has('banded'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('banded') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="in_country_rank">{{ 'In Country Rank' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="in_country_rank" class="form-control"
                                                            value="{{ old('in_country_rank') }}" placeholder="In Country Rank">
                                                    @if($errors->has('in_country_rank'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('in_country_rank') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="admin_cat">{{ 'Admin Category' }}</label>
                                                    <input type="text" name="admin_cat" id="admin_cat" class="form-control"
                                                            value="{{ old('admin_cat') }}" placeholder="Admin Category"
                                                    @if($errors->has('admin_cat'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('admin_cat') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="admin_col">{{ 'Admin Color' }} <span
                                                            style="color:red;">*</span></label>
                                                            <select id="admin_col" class="form-control form-select" name="admin_col">
                                                                <option value="">None</option>
                                                                @foreach($countries_colour as $country_colour)
                                                                    <option value="{{$country_colour->subcountry_leg_col}}" data-category="{{$country_colour->category}}" style="background-color: {{$country_colour->subcountry_leg_col}}; color: white;">{{$country_colour->subcountry_leg_col}} ({{$country_colour->category}})</option>
                                                                @endforeach
                                                            </select>
                                                    @if($errors->has('admin_col'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('admin_col') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.sub-country-data.index')}}">
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
    @endsection