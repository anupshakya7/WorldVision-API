@extends('ati.admin.dashboard.layout.web')
@section('title','Source Create')
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
                        <h4 class="mb-sm-0">Source</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.ati-source.index')}}">Source</a></li>
                                <li class="breadcrumb-item active">{{ 'Create Source'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Add Source' }}</h5>
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
                            <form action="{{ route('admin.ati.ati-source.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                <label for="indicator_id">Indicator <span
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
                                                    <label for="source">{{ 'Source' }} <span
                                                        style="color:red;">*</span></label>
                                                    <input type="text" name="source" class="form-control"
                                                        value="{{ old('source') }}" placeholder="Source">
                                                    @if($errors->has('source'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('source') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="col-12" >
                                                <div class="form-group">
                                                    <label for="link">{{ 'Link' }}</label>
                                                    <input type="text" name="link" class="form-control"
                                                        value="{{ old('link') }}" placeholder="Link">
                                                    @if($errors->has('link'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('link') }}
                                                    </em>
                                                    @endif
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="margin-top:30px;">
                                        <a class="btn btn-info" href="{{route('admin.ati.ati-source.index')}}">
                                            <i class="ri-arrow-left-line"></i> Back to list
                                        </a>
                                        <button class="btn btn-success float-end" type="submit" id="uploadButton">
                                            <i class="ri-save-line"></i> Save
                                        </button>
                                    </div>
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