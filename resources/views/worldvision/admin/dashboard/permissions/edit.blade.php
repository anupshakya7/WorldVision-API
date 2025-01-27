@extends('worldvision.admin.dashboard.layout.web')
@section('title','Permission Update')
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
    
    .danger_cross{
        display: inline-block;
        position: relative;
    }
    .danger_cross_icon{
        position: absolute;
        top:-10px;
        right:-14px;
        border-radius: 50%;
        font-size: 15px;
        color:#757070;
        background: none;
        border: none;
    }
    .danger_cross_icon:hover{
        color: #bd3030;
    }
</style>
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" rel="stylesheet">
<!-- Import table plugin specific stylesheet -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/ui/trumbowyg.table.min.css"> --}}
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Permission</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.permissions.index')}}">Permission</a></li>
                                <li class="breadcrumb-item active">{{ 'Update Permission'}}</li>
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
                            <h5 class="card-title mb-0">{{ 'Update Permission' }}</h5>
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
                            <form action="{{ route('admin.permissions.update',$permission) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="name">{{ 'Permission' }} <span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name',$permission->name) }}" placeholder="Permission">
                                                        @if($errors->has('name'))
                                                        <span class="invalid-feedback">
                                                            {{ $errors->first('name') }}
                                                        </span>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    {{-- <a class="btn btn-info" href="{{route('admin.permissions.index')}}">
                                        <i class="ri-arrow-left-line"></i> Back to list
                                    </a> --}}
                                    <button class="btn btn-success float-end" type="submit" id="uploadButton">
                                        <i class="ri-save-line"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <h5 class="card-title mb-0">{{ 'Permission Roles' }}</h5>
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
                            <div class="row">
                                @if(count($permission->roles)>0)
                                    <div class="col-12 mb-4">
                                        <label for="assign_permission">Assigned Roles:  </label>
                                        @foreach($permission->roles as $role)
                                        <div class="danger_cross mx-2">
                                            <span class="badge text-bg-danger">{{$role->name}}</span>
                                            <form action="{{route('admin.permissions.roles.remove',['permission'=>$permission,'role'=>$role])}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="danger_cross_icon">
                                                    <i class="mdi mdi-close-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                            </div>
                           
                            <form action="{{ route('admin.permissions.roles',$permission) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <div class="form-group">
                                                    <label for="role">{{ 'Role' }} <span
                                                            style="color:red;">*</span></label>
                                                        <select class="form-control form-select" id="role"
                                                        name="role">
                                                            <option value="">None</option>
                                                            @foreach ($roles as $role)
                                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if($errors->has('role'))
                                                        <span class="invalid-feedback">
                                                            {{ $errors->first('role') }}
                                                        </span>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    

                                </div>

                                <div class="col-12" style="margin-top:30px;">
                                    <a class="btn btn-info" href="{{route('admin.roles.index')}}">
                                        <i class="ri-arrow-left-line"></i> Back to list
                                    </a>
                                    <button class="btn btn-success float-end" type="submit" id="uploadButton">
                                        <i class="ri-save-line"></i> Assign
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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script> --}}
    <!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/trumbowyg.table.min.js"></script>
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

    </script> --}}
    @endsection