@extends('worldvision.admin.dashboard.layout.web')
@section('title','Project')
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
                                <li class="breadcrumb-item active">{{"Project"}}</li>
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
                                        <h5 class="card-title mb-0">Project</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <a class="btn btn-soft-success" href="{{ route('admin.project.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{ "Project" }}
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
                                            <th>{{'Year'}}</th>
                                            <th>{{'Region'}}</th>
                                            <th>{{'Country'}}</th>
                                            <th>{{'Sub Country'}}</th>
                                            <th>{{'Latitude'}}</th>
                                            <th>{{'Longitude'}}</th>
                                            <th>{{'Project'}}</th>
                                            <th>{{'Project Overview'}}</th>
                                            <th>{{'Domain'}}</th>
                                            <th>{{'Indicator'}}</th>
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                        <tr>
                                            <td>{{$project->serial_no}}</td>
                                            <td>{{$project->year}}</td>
                                            <td>{{optional($project->region)->country}}</td>
                                            <td>{{optional($project->country)->country}}</td>
                                            <td>{{optional($project->subcountry)->geoname}}</td>
                                            <td>{{$project->latitude}}</td>
                                            <td>{{$project->longitude}}</td>
                                            <td>{{$project->project_title}}</td>
                                            <td>{{Str::limit($project->project_overview,50)}}</td>
                                            <td>{{optional($project->domain)->variablename}}</td>
                                            <td>{{optional($project->indicator)->variablename}}</td>
                                            <td>{{optional($project->user)->name}}</td>
                                            <td>
                                                <a href="{{route('admin.project.show',$project)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{route('admin.project.edit',$project)}}"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$projects->links()}}
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