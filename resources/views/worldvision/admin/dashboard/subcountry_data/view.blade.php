@extends('worldvision.admin.dashboard.layout.web')
@section('title','Sub Country Data View')
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
                        <h4 class="mb-sm-0">{{"Sub Country Data"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.country-data.index')}}">Sub Country Data</a></li>
                                <li class="breadcrumb-item active">{{optional($subCountryData->subcountry)->geoname}}</li>
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
                            <h5 class="card-title mb-0">{{optional($subCountryData->subcountry)->geoname}}</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$subCountryData->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Indicator</th>
                                            <td>{{$subCountryData->indicator->variablename}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sub Country</th>
                                            <td>{{optional($subCountryData->subcountry)->geoname}}</td>
                                        </tr>
                                        <tr>
                                            <th>Geo Code</th>
                                            <td>{{$subCountryData->geocode}}</td>
                                        </tr>
                                        <tr>
                                            <th>Year</th>
                                            <td>{{$subCountryData->year}}</td>
                                        </tr>
                                        <tr>
                                            <th>Raw</th>
                                            <td>{{$subCountryData->raw}}</td>
                                        </tr>
                                        <tr>
                                            <th>Banded</th>
                                            <td>{{$subCountryData->banded}}</td>
                                        </tr>
                                        <tr>
                                            <th>In Country Rank</th>
                                            <td>{{$subCountryData->in_country_rank}}</td>
                                        </tr>
                                        <tr>
                                            <th>Admin Category</th>
                                            <td>{{$subCountryData->admin_cat}}</td>
                                        </tr>
                                        <tr>
                                            <th>Admin Color</th>
                                            <td>{{$subCountryData->admin_col}}</td>
                                        </tr>
                                        <tr>
                                            <th>Source Id</th>
                                            <td>{{$subCountryData->source_id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Statement</th>
                                            <td>{{$subCountryData->statements}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$subCountryData->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($subCountryData->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.sub-country-data.index')}}">
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