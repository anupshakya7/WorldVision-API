@extends('ati.admin.dashboard.layout.web')
@section('title','Voice Of People View')
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
                        <h4 class="mb-sm-0">{{"Voice Of People"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item active">{{'Country Data'}}</li>
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.voice-people.index')}}">Voice Of People</a></li>
                                <li class="breadcrumb-item active">{{optional($countryData->country)->country ?? 'No Country'}}</li>
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
                            <h5 class="card-title mb-0">{{optional($countryData->country)->country ?? 'No Country'}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$countryData->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country</th>
                                            <td>{{optional($countryData->country)->country ?? 'No Country'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country Code</th>
                                            <td>{{$countryData->countrycode}}</td>
                                        </tr>
                                        <tr>
                                            <th>Voice Of People</th>
                                            <td>{{$countryData->remarks}}</td>
                                        </tr>
                                        <tr>
                                            <th>Score (%)</th>
                                            <td>{{$countryData->country_score ? $countryData->country_score.'%':''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Year</th>
                                            <td>{{$countryData->year}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$countryData->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($countryData->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.ati.voice-people.index')}}">
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