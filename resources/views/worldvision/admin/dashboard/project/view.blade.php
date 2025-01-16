@extends('worldvision.admin.dashboard.layout.web')
@section('title','Project View')
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
                        <h4 class="mb-sm-0">{{"Project"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.project.index')}}">Project</a></li>
                                <li class="breadcrumb-item active">{{$project->project_title}}</li>
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
                            <h5 class="card-title mb-0">{{$project->project_title}}</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$project->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Project</th>
                                            <td>{{$project->project_title}}</td>
                                        </tr>
                                        <tr>
                                            <th>Project Overview</th>
                                            <td>{{$project->project_overview}}</td>
                                        </tr>
                                        <tr>
                                            <th>Year</th>
                                            <td>{{$project->year}}</td>
                                        </tr>
                                        <tr>
                                            <th>Region</th>
                                            <td>{{optional($project->region)->country}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country</th>
                                            <td>{{optional($project->country)->country}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sub Country</th>
                                            <td>{{optional($project->subcountry)->geoname}}</td>
                                        </tr>
                                        <tr>
                                            <th>Latitute</th>
                                            <td>{{$project->latitude}}</td>
                                        </tr>
                                        <tr>
                                            <th>Longitude</th>
                                            <td>{{$project->longitude}}</td>
                                        </tr>
                                        <tr>
                                            <th>Domain</th>
                                            <td>{{optional($project->domain)->variablename}}</td>
                                        </tr>
                                        <tr>
                                            <th>Indicator</th>
                                            <td>{{optional($project->indicator)->variablename}}</td>
                                        </tr>
                                        <tr>
                                            <th>Link</th>
                                            <td>{{$project->link}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$project->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($project->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.project.index')}}">
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