@extends('worldvision.admin.dashboard.layout.web')
@section('title','Indicator Update')
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
                        <h4 class="mb-sm-0">Indicator</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.indicator.index')}}">Indicator</a>
                                </li>
                                <li class="breadcrumb-item active">{{ 'Update Indicator'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Update Indicator' }}</h5>
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
                            <form action="{{ route('admin.indicator.update',$indicator) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="title">{{ 'Domain' }} <span
                                                            style="color:red;">*</span></label>
                                                    <select class="form-control form-select" id="title"
                                                    name="domain_id">
                                                        <option value="">None</option>
                                                        @foreach ($domains as $domain)
                                                        <option value="{{ $domain->id }}" {{$domain->id == $indicator->domain_id ? 'selected':''}}>{{ $domain->variablename }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('domain_id'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('domain_id') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="variablename_long">{{ 'Variable Name Long' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="variablename_long" class="form-control"
                                                        value="{{ old('variablename_long',$indicator->variablename_long) }}"
                                                        placeholder="Variable Name Long">
                                                    @if($errors->has('variablename_long'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('variablename_long') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="variablename">Variable Name <span
                                                        style="color:red;">*</span></label>
                                                    <input type="text" name="variablename" class="form-control"
                                                        value="{{ old('variablename',$indicator->variablename) }}"
                                                        placeholder="Variable Name">
                                                    @if($errors->has('variablename'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('variablename') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="vardescription">Variable Description <span
                                                        style="color:red;">*</span></label>
                                                    <textarea class="form-control" id="vardescription"
                                                        name="vardescription"
                                                        rows="4">{{ old('vardescription',$indicator->vardescription) }}</textarea>
                                                    @if($errors->has('vardescription'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('vardescription') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="varunits">Variable Units</label>
                                                    <textarea class="form-control" id="varunits" name="varunits"
                                                        rows="3">{{ old('varunits',$indicator->varunits) }}</textarea>
                                                    @if($errors->has('varunits'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('varunits') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="sourcelinks">Source Links</label>
                                                    <input type="text" name="sourcelinks" class="form-control"
                                                        value="{{ old('sourcelinks',$indicator->sourcelinks) }}"
                                                        placeholder="Source Links">
                                                    @if($errors->has('sourcelinks'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('sourcelinks') }}
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
                                                    <label for="is_more_better">Is More Better</label>
                                                    <input type="text" name="is_more_better" class="form-control"
                                                        value="{{ old('is_more_better',$indicator->is_more_better) }}"
                                                        placeholder="Is More Better">
                                                    @if($errors->has('is_more_better'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('is_more_better') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top: 20px;">
                                                <div class="form-group">
                                                    <label for="transformation">Transformation</label>
                                                    <input type="text" name="transformation" class="form-control"
                                                        value="{{ old('transformation',$indicator->transformation) }}"
                                                        placeholder="Transformation">
                                                    @if($errors->has('transformation'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('transformation') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top: 20px;">
                                                <div class="form-group">
                                                    <label for="lower">Lower</label>
                                                    <input type="text" name="lower" class="form-control"
                                                        value="{{ old('lower',$indicator->lower) }}"
                                                        placeholder="Lower">
                                                    @if($errors->has('lower'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('lower') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top: 20px;">
                                                <div class="form-group">
                                                    <label for="upper">Upper</label>
                                                    <input type="text" name="upper" class="form-control"
                                                        value="{{ old('upper',$indicator->upper) }}"
                                                        placeholder="Upper">
                                                    @if($errors->has('upper'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('upper') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="subnational">Subnational</label>
                                                    <input type="text" name="subnational" class="form-control"
                                                        value="{{ old('subnational',$indicator->subnational) }}"
                                                        placeholder="Subnational">
                                                    @if($errors->has('subnational'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('subnational') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="national">National</label>
                                                    <input type="text" name="national" class="form-control"
                                                        value="{{ old('national',$indicator->national) }}"
                                                        placeholder="National">
                                                    @if($errors->has('national'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('national') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top: 20px;">
                                                <div class="form-group">
                                                    <label for="imputation">Imputation</label>
                                                    <input type="text" name="imputation" class="form-control"
                                                        value="{{ old('imputation',$indicator->imputation) }}"
                                                        placeholder="Imputation">
                                                    @if($errors->has('imputation'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('imputation') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.indicator.index')}}">
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