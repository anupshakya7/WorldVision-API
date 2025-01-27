@extends('worldvision.admin.dashboard.layout.web')
@section('title','Indicator View')
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
                        <h4 class="mb-sm-0">{{"Indicator"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.indicator.index')}}">Indicator</a>
                                </li>
                                <li class="breadcrumb-item active">{{$indicator->variablename}}</li>
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
                            <h5 class="card-title mb-0">{{$indicator->variablename}}</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$indicator->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Domain</th>
                                            <td>{{$indicator->domain}}</td>
                                        </tr>
                                        <tr>
                                            <th>Variable Name Long</th>
                                            <td>{{$indicator->variablename_long}}</td>
                                        </tr>
                                        <tr>
                                            <th>Variable Name</th>
                                            <td>{{$indicator->variablename}}</td>
                                        </tr>
                                        <tr>
                                            <th>Variable Description</th>
                                            <td>{{$indicator->vardescription}}</td>
                                        </tr>
                                        <tr>
                                            <th>Variable Units</th>
                                            <td>{{$indicator->varunits}}</td>
                                        </tr>
                                        <tr>
                                            <th>Is More Better</th>
                                            <td>{{$indicator->is_more_better}}</td>
                                        </tr>
                                        <tr>
                                            <th>Transformation</th>
                                            <td>{{$indicator->transformation}}</td>
                                        </tr>
                                        <tr>
                                            <th>Lower</th>
                                            <td>{{$indicator->lower}}</td>
                                        </tr>
                                        <tr>
                                            <th>Upper</th>
                                            <td>{{$indicator->upper}}</td>
                                        </tr>
                                        <tr>
                                            <th>Source Links</th>
                                            <td>{{$indicator->sourcelinks}}</td>
                                        </tr>
                                        <tr>
                                            <th>Subnational</th>
                                            <td>{{$indicator->subnational}}</td>
                                        </tr>
                                        <tr>
                                            <th>National</th>
                                            <td>{{$indicator->national}}</td>
                                        </tr>
                                        <tr>
                                            <th>Imputation</th>
                                            <td>{{$indicator->imputation}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$indicator->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($indicator->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.indicator.index')}}">
                                    <i class="ri-arrow-left-line"></i> Back to list
                                </a>
                            </div>

                            <nav class="mb-3">
                                <div class="nav nav-tabs">

                                </div>
                            </nav>
                            <div class="tab-content">

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