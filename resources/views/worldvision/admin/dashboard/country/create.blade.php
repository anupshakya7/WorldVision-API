@extends('worldvision.admin.dashboard.layout.web')
@section('title','Country Create')
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
                        <h4 class="mb-sm-0">Country</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.country.index')}}">Country</a></li>
                                <li class="breadcrumb-item active">{{ 'Create Country'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Add Country' }}</h5>
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
                            <form action="{{ route('admin.country.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="country">{{ 'Country' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="country" class="form-control"
                                                        value="{{ old('country') }}" placeholder="Title">
                                                    @if($errors->has('country'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('country') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="title">{{ 'Country Code' }} <span
                                                            style="color:red;"></span></label>
                                                    <input type="text" name="country_code" class="form-control"
                                                        value="{{ old('country_code') }}" maxlength="3"
                                                        placeholder="eg. AUS">
                                                    @if($errors->has('country_code'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('country_code') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="bounding_box">Geometry</label>
                                                <textarea class="form-control" id="geometry" name="geometry"
                                                    rows="4">{{ old('geometry') }}</textarea>
                                                <small class="form-text text-muted">Enter the geometry in JSON format.
                                                    <br>Example:"MultiPolygon (((74.91574100000005387
                                                    37.23732800000000509, 74.39221200000005751 37.17507200000007117,
                                                    74.56543000000007737 ,...)))" </small>
                                                @if($errors->has('geometry'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('geometry') }}
                                                </em>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="parent_id">Parent Region</label>
                                                <select class="form-control form-select" id="parent_id"
                                                    name="parent_id">
                                                    <option value="">None</option>
                                                    @foreach ($regions as $region)
                                                    <option value="{{ $region->id }}">{{ $region->country }}</option>
                                                    @endforeach
                                                </select>
                                            @if($errors->has('parent_id'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('parent_id') }}
                                            </em>
                                            @endif
                                            </div>

                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="latitude">Latitude</label>
                                                <input type="text" class="form-control" id="latitude" name="latitude"
                                                    value="{{ old('latitude') }}" placeholder="Latitude">
                                                @if($errors->has('latitude'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('latitude') }}
                                                </em>
                                                @endif
                                            </div>

                                            <div class="col-12" style="margin-top:30px;">
                                                <label for="longitude">Longitude</label>
                                                <input type="text" class="form-control" id="longitude" name="longitude"
                                                    value="{{ old('longitude') }}" placeholder="Longitude">
                                                @if($errors->has('longitude'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('longitude') }}
                                                </em>
                                                @endif
                                            </div>

                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="bounding_box">Bounding Box (JSON)</label>
                                                <textarea class="form-control" id="bounding_box" name="bounding_box"
                                                    rows="4">{{ old('bounding_box') }}</textarea>
                                                <small class="form-text text-muted">Enter the bounding box coordinates
                                                    in JSON format. <br>Example: {"min_latitude":
                                                    40.477399,"min_longitude": -74.259090,"max_latitude":
                                                    40.917577,"max_longitude": -73.700272}</small>
                                                @if($errors->has('bounding_box'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('bounding_box') }}
                                                </em>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.country.index')}}">
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