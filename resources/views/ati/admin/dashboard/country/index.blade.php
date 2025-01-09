@extends('ati.admin.dashboard.layout.web')
@section('title','Country')
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
                                <li class="breadcrumb-item active">{{"Country"}}</li>
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
                                        <h5 class="card-title mb-0">Country</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <a class="btn btn-soft-success" href="{{ route('admin.ati.country.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{ "Country" }}
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
                                            <th>{{'Title'}}</th>
                                            <th>{{'Country Code'}}</th>
                                            <th>{{'Parent'}}</th>
                                            <th>{{'Latitude'}}</th>
                                            <th>{{'Longitude'}}</th>
                                            <th>{{'Bounding Box'}}</th>
                                            <th>{{'Geometry'}}</th>
                                            <th>{{'Level'}}</th>
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($countries as $country)
                                        <tr>
                                            <td>{{$country->serial_no}}</td>
                                            <td>{{$country->country}}</td>
                                            <td>{{$country->country_code}}</td>
                                            <td>{{optional($country->parentData)->country}}</td>
                                            <td>{{$country->latitude}}</td>
                                            <td>{{$country->longitude}}</td>
                                            <td>{{$country->bounding_box}}</td>
                                            <td>{{Str::limit($country->geometry,50)}}</td>
                                            <td>{{$country->level == 0 ? 'Region':'Country'}}</td>
                                            <td>{{$country->user->name}}</td>
                                            <td>
                                                <a href="{{route('admin.ati.country.show',$country)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{route('admin.ati.country.edit',$country)}}"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$countries->links()}}
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