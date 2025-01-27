@extends('ati.admin.dashboard.layout.web')
@section('title','Country View')
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
                        <h4 class="mb-sm-0">{{"Country"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.country.index')}}">Country</a></li>
                                <li class="breadcrumb-item active">{{$country->country}}</li>
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
                            <h5 class="card-title mb-0">{{$country->country}}</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$country->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td>{{$country->country}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country Code</th>
                                            <td>{{$country->country_code}}</td>
                                        </tr>
                                        <tr>
                                            <th>Parent Id</th>
                                            <td>{{optional($country->parentData)->country}}</td>
                                        </tr>
                                        <tr>
                                            <th>Latitute</th>
                                            <td>{{$country->latitude}}</td>
                                        </tr>
                                        <tr>
                                            <th>Longitude</th>
                                            <td>{{$country->longitude}}</td>
                                        </tr>
                                        <tr>
                                            <th>Bounding Box</th>
                                            <td>{{$country->bounding_box}}</td>
                                        </tr>
                                        <tr>
                                            <th>Geometry</th>
                                            <td>{{$country->geometry}}</td>
                                        </tr>
                                        <tr>
                                            <th>Level</th>
                                            <td>{{$country->level == 0 ? 'Region':'Country'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$country->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($country->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.ati.country.index')}}">
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