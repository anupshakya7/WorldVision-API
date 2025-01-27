@extends('ati.admin.dashboard.layout.web')
@section('title','Domain Score Create')
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
                        <h4 class="mb-sm-0">Domain Score</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item">{{ 'Country Data'}}</li>
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.domain-score.index')}}">Domain Score</a></li>
                                <li class="breadcrumb-item active">{{ 'Create Domain Score'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Add Domain Score' }}</h5>
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
                            <form action="{{ route('admin.ati.domain-score.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="domain_id">{{ 'Domain' }} <span
                                                            style="color:red;">*</span></label>
                                                        <select class="form-control form-select" id="domain_id"
                                                        name="domain_id">
                                                            <option value="">None</option>
                                                            @foreach ($indicators as $indicator)
                                                            <option value="{{ $indicator->id }}">{{ $indicator->variablename }}</option>
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
                                                    <label for="countrycode">{{ 'Country' }} <span
                                                            style="color:red;">*</span></label>
                                                        <select class="form-control form-select" id="countrycode"
                                                        name="countrycode">
                                                            <option value="">None</option>
                                                            @foreach ($countries as $country)
                                                            <option value="{{ $country->country_code }}">{{ $country->country }}</option>
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
                                                    <label for="domain_result">{{ 'Domain Result' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="domain_result" class="form-control"
                                                            value="{{ old('domain_result') }}" placeholder="Domain Result">
                                                    @if($errors->has('domain_result'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('domain_result') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="trend_result">{{ 'Trend Result' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="trend_result" class="form-control"
                                                            value="{{ old('trend_result') }}" placeholder="Trend Result">
                                                    @if($errors->has('trend_result'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('trend_result') }}
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
                                                    <label for="score">{{ 'Score' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="score" class="form-control"
                                                            value="{{ old('score') }}" placeholder="Country Score">
                                                    @if($errors->has('score'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('score') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                           
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="trend_percentage">{{ 'Domain Trend (%)' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="trend_percentage" class="form-control"
                                                            value="{{ old('trend_percentage') }}" placeholder="Domain Trend (%)">
                                                    @if($errors->has('trend_percentage'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('trend_percentage') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12" style="margin-top:20px;">
                                                <div class="form-group">
                                                    <label for="shifts_governance">{{ 'Shifts in Governance' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="shifts_governance" class="form-control"
                                                            value="{{ old('shifts_governance') }}" placeholder="Shifts in Governance">
                                                    @if($errors->has('shifts_governance'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('shifts_governance') }}
                                                    </em>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.ati.domain-score.index')}}">
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

            //For Color Category
            $('#country_col').change(function(){
                var selectedOption = $(this).find('option:selected');
                var countryCategory = selectedOption.data('category') ?? null;

                if(countryCategory !== null){
                    $('#country_cat').val(countryCategory);
                }else{
                    $('#country_cat').val('');
                }
                
            });
            
        });

      var _url = "settings";
      @if(Session::has("message"))
        toastr.success("{{session('message')}}")
      @endif

    </script>
    @endsection