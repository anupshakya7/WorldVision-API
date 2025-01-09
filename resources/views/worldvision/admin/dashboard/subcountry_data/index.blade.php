@extends('worldvision.admin.dashboard.layout.web')
@section('title','Sub Country Data')
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
                                <li class="breadcrumb-item active">{{"Sub Country Data"}}</li>
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
                                        <h5 class="card-title mb-0">Sub Country Data</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <a class="btn btn-soft-primary" href="{{ route('admin.sub-country-data.generate.csv') }}">
                                            <i class="mdi mdi-file-export align-middle me-1"></i>
                                            {{ "Export Data" }}
                                        </a>
                                        <a class="btn btn-soft-success" href="{{ route('admin.sub-country-data.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{ "Sub Country Data" }}
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
                                            <th>{{'Indicator'}}</th>
                                            <th>{{'Sub Country'}}</th>
                                            <th>{{'Geo Code'}}</th>
                                            <th>{{'Year'}}</th>
                                            <th>{{'Raw'}}</th>
                                            <th>{{'Banded'}}</th>
                                            <th>{{'In Country Rank'}}</th>
                                            <th>{{'Admin Category'}}</th>
                                            <th>{{'Admin Color'}}</th>
                                            <th>{{'Source Id'}}</th>
                                            <th>{{'Statement'}}</th>
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subcountriesData as $subcountryData)
                                        <tr>
                                            <td>{{$subcountryData->id}}</td>
                                            <td>{{$subcountryData->indicator->variablename}}</td>
                                            <td>{{optional($subcountryData->subcountry)->geoname ?? 'No Sub Country'}}</td>
                                            <td>{{$subcountryData->geocode}}</td>
                                            <td>{{$subcountryData->year}}</td>
                                            <td>{{$subcountryData->raw}}</td>
                                            <td>{{$subcountryData->banded}}</td>
                                            <td>{{$subcountryData->in_country_rank}}</td>
                                            <td>{{$subcountryData->admin_cat}}</td>
                                            <td>{{$subcountryData->admin_col}}</td>
                                            <td>{{$subcountryData->source_id}}</td>
                                            <td>{{Str::limit($subcountryData->statements,50)}}</td>
                                            <td>{{$subcountryData->user->name}}</td>
                                            <td>
                                                <a href="{{route('admin.sub-country-data.show',$subcountryData)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{route('admin.sub-country-data.edit',$subcountryData)}}"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$subcountriesData->links()}}
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