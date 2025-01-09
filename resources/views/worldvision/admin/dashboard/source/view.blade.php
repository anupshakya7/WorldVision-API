@extends('worldvision.admin.dashboard.layout.web')
@section('title','Source View')
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
                        <h4 class="mb-sm-0">{{"Source"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.source.index')}}">Source</a></li>
                                <li class="breadcrumb-item active">{{$source->source}}</li>
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
                            <h5 class="card-title mb-0">{{$source->source}}</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$source->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Indicator</th>
                                            <td>{{$source->indicator->variablename}}</td>
                                        </tr>
                                        <tr>
                                            <th>Source</th>
                                            <td>{{$source->source}}</td>
                                        </tr>
                                        <tr>
                                            <th>Data Level</th>
                                            <td>{{$source->data_level}}</td>
                                        </tr>
                                        <tr>
                                            <th>Impid</th>
                                            <td>{{$source->impid}}</td>
                                        </tr>
                                        <tr>
                                            <th>Units</th>
                                            <td>{{$source->units}}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{$source->description}}</td>
                                        </tr>
                                        <tr>
                                            <th>Url</th>
                                            <td>{{$source->url}}</td>
                                        </tr>
                                        <tr>
                                            <th>Link</th>
                                            <td>{{$source->link}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$source->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($source->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.source.index')}}">
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