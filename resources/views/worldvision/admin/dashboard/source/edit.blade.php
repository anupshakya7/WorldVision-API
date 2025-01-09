@extends('worldvision.admin.dashboard.layout.web')
@section('title','Source Update')
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
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.source.index')}}">Source</a></li>
                                <li class="breadcrumb-item active">{{ 'Update Source'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Update Source' }}</h5>
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
                            <form action="{{ route('admin.source.update',$source) }}" method="POST">
                                @csrf
                                @method('PUT')
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
                                                    <option value="{{ $indicator->id }}" {{$source->indicator_id == $indicator->id ? 'selected':''}}>{{ $indicator->variablename }}</option>
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
                                                        value="{{ old('source',$source->source) }}" placeholder="Source">
                                                    @if($errors->has('source'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('source') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" id="description" name="description"
                                                        rows="4">{{ old('description',$source->description) }}</textarea>
                                                    @if($errors->has('description'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('description') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="url">{{ 'Url' }}</label>
                                                    <input type="text" name="url" class="form-control"
                                                        value="{{ old('url',$source->url) }}" placeholder="Url">
                                                    @if($errors->has('url'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('url') }}
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
                                                    <label for="data_level">{{ 'Data Level' }}</label>
                                                    <input type="text" name="data_level" class="form-control"
                                                        value="{{ old('data_level',$source->data_level) }}" placeholder="Data Level">
                                                    @if($errors->has('data_level'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('data_level') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="impid">{{ 'Impid' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="impid" class="form-control"
                                                        value="{{ old('impid',$source->impid) }}" placeholder="Impid">
                                                    @if($errors->has('impid'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('impid') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="col-12" >
                                                <div class="form-group">
                                                    <label for="units">{{ 'Units' }}</label>
                                                    <input type="text" name="units" class="form-control"
                                                        value="{{ old('units',$source->units) }}" placeholder="Units">
                                                    @if($errors->has('units'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('units') }}
                                                    </em>
                                                    @endif
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="col-12" >
                                                <div class="form-group">
                                                    <label for="link">{{ 'Link' }}</label>
                                                    <input type="text" name="link" class="form-control"
                                                        value="{{ old('link',$source->link) }}" placeholder="Link">
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
                                        <a class="btn btn-info" href="{{route('admin.source.index')}}">
                                            <i class="ri-arrow-left-line"></i> Back to list
                                        </a>
                                        <button class="btn btn-success float-end" type="submit" id="uploadButton">
                                            <i class="ri-save-line"></i> Update
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