@extends('worldvision.admin.dashboard.layout.web')
@section('title','Category Color View')
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
                        <h4 class="mb-sm-0">{{"Category Color"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.category-color.index')}}">Category Color</a></li>
                                <li class="breadcrumb-item active">{{$color->category}}</li>
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
                            <h5 class="card-title mb-0">{{$color->category}}</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-2">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <td>{{$color->id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td>{{$color->category}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country Color Order</th>
                                            <td>{{$color->country_col_order}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country Color</th>
                                            <td>{{$color->country_leg_col}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sub Country Color Order</th>
                                            <td>{{$color->subcountry_col_order}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sub Country Color</th>
                                            <td>{{$color->subcountry_leg_col}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{$color->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{Carbon\Carbon::parse($color->created_at)->format('Y-m-d')}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a style="margin-top:20px;" class="btn btn-info"
                                    href="{{route('admin.category-color.index')}}">
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