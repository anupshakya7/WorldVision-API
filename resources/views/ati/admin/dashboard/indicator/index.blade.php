@extends('ati.admin.dashboard.layout.web')
@section('title','Domain & Indicator')
@section('content')
<style>
    button {
        border: none;
        background-color: transparent;
    }
</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{"Domain & Indicator"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item active">{{"Domain & Indicator"}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom-dashed">
                            <div class="row g-4 align-items-center">
                                <div class="col-sm">
                                    <div>
                                        <h5 class="card-title mb-0">Domain & Indicator</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <a class="btn btn-soft-success" href="{{ route('admin.ati.indicator.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{ "Domain & Indicator" }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables"
                                    class="table table-striped table-hover datatable datatable-country"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>{{'#'}}</th>
                                            <th>{{'Domain'}}</th>
                                            <th>{{'Variable Name Long'}}</th>
                                            <th>{{'Variable Name'}}</th>
                                            <th>{{'Variable Description'}}</th>
                                            <th>{{'Level'}}</th>
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($indicators as $indicator)
                                        <tr>
                                            <td>{{$indicator->serial_no}}</td>
                                            <td>{{$indicator->domain_id != null ? $indicator->domains->variablename : $indicator->variablename}}</td>
                                            <td>{{$indicator->variablename_long}}</td>
                                            <td>{{$indicator->variablename}}</td>
                                            <td>{{Str::limit($indicator->vardescription,50)}}</td>
                                            <td>{{$indicator->level == 0 ? 'Domain':'Indicator'}}</td>
                                            <td>{{$indicator->user->name }}</td>
                                            <td>
                                                <a href="{{route('admin.ati.indicator.show',$indicator)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{route('admin.ati.indicator.edit',$indicator)}}"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$indicators->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection