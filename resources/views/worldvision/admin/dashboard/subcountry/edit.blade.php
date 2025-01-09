@extends('worldvision.admin.dashboard.layout.web')
@section('title','Sub Country Update')
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
                        <h4 class="mb-sm-0">Sub Country</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.country.index')}}">Sub Country</a></li>
                                <li class="breadcrumb-item active">{{ 'Update Sub Country'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Update Sub Country' }}</h5>
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
                            <form action="{{ route('admin.sub-country.update',$subcountry) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="countrycode">Country <span
                                                        style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="countrycode"
                                                        name="countrycode">
                                                        <option value="">None</option>
                                                        @foreach ($countries as $country)
                                                        <option value="{{ $country->country_code }}" {{$subcountry->countrycode == $country->country_code ? 'selected':''}}>{{ $country->country }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('countrycode'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('countrycode') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="geoname">{{ 'Geo Name' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="geoname" class="form-control"
                                                        value="{{ old('geoname',$subcountry->geoname) }}"
                                                        placeholder="Geo Name">
                                                    @if($errors->has('geoname'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('geoname') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <label for="geometry">Geometry</label>
                                                <textarea class="form-control" id="geometry" name="geometry"
                                                    rows="4">{{ old('geometry',$subcountry->geometry) }}</textarea>
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
                                                <div class="form-group">
                                                    <label for="title">{{ 'Geo Code' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="geocode" class="form-control"
                                                        value="{{ old('geocode',$subcountry->geocode) }}"
                                                        placeholder="eg. AFG.1_1">
                                                    @if($errors->has('geocode'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('geocode') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.sub-country.index')}}">
                                        <i class="ri-arrow-left-line"></i> Back to list
                                    </a>
                                    <button class="btn btn-success float-end" type="submit" id="uploadButton">
                                        <i class="ri-save-line"></i> Update
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