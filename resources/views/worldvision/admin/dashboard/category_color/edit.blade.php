@extends('worldvision.admin.dashboard.layout.web')
@section('title','Category Color Update')
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
                        <h4 class="mb-sm-0">Category Color</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.category-color.index')}}">Category Color</a></li>
                                <li class="breadcrumb-item active">{{ 'Update Category Color'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Update Category Color' }}</h5>
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
                            <form action="{{ route('admin.category-color.update',$categoryColor) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="country_leg_col">{{ 'Country Color' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="country_leg_col" class="form-control"
                                                        value="{{ old('country_leg_col',$categoryColor->country_leg_col) }}" placeholder="Country Color">
                                                    @if($errors->has('country_leg_col'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('country_leg_col') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="subcountry_leg_col">{{ 'Sub Country Color' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="subcountry_leg_col" class="form-control"
                                                        value="{{ old('subcountry_leg_col',$categoryColor->subcountry_leg_col) }}" placeholder="Sub Country Color">
                                                    @if($errors->has('subcountry_leg_col'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('subcountry_leg_col') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="category">{{ 'Category' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="category" class="form-control"
                                                        value="{{ old('category',$categoryColor->category) }}" placeholder="Category">
                                                    @if($errors->has('category'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('category') }}
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
                                                    <label for="country_col_order">{{ 'Country Color Order' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="country_col_order" class="form-control"
                                                        value="{{ old('country_col_order',$categoryColor->country_col_order) }}" placeholder="Country Color Order">
                                                    @if($errors->has('country_col_order'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('country_col_order') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="subcountry_col_order">{{ 'Sub Country Color Order' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="subcountry_col_order" class="form-control"
                                                        value="{{ old('subcountry_col_order',$categoryColor->subcountry_col_order) }}" placeholder="Sub Country Color Order">
                                                    @if($errors->has('subcountry_col_order'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('subcountry_col_order') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.category-color.index')}}">
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