@extends('worldvision.admin.dashboard.layout.web')
@section('title','Sub Country View')
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
                        <h4 class="mb-sm-0">{{"Sub Country"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.sub-country.index')}}">Sub Country</a></li>
                                <li class="breadcrumb-item active">{{$subcountry->geoname}}</li>
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
                            <h5 class="card-title mb-0">{{$subcountry->geoname}}</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$subcountry->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country Code</th>
                                            <td>{{$subcountry->countrycode}}</td>
                                        </tr>
                                        <tr>
                                            <th>Geo Code</th>
                                            <td>{{$subcountry->geocode}}</td>
                                        </tr>
                                        <tr>
                                            <th>Geo Name</th>
                                            <td>{{$subcountry->geoname}}</td>
                                        </tr>
                                        <tr>
                                            <th>Geometry</th>
                                            <td>{{$subcountry->geometry}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$subcountry->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($subcountry->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.sub-country.index')}}">
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