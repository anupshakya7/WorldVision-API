@extends('ati.admin.dashboard.layout.web')
@php
    $title = isset($type) ? $type : 'Indicator';
@endphp
@section('title',$title.' Score')
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
                        <h4 class="mb-sm-0">{{$title." Score"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item">{{"Country Data"}}</li>
                                <li class="breadcrumb-item active">{{$title." Score"}}</li>
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
                                        <h5 class="card-title mb-0">{{$title." Score"}}</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        {{-- <a class="btn btn-soft-primary" href="{{ route('admin.ati.country-data.generate.csv') }}">
                                            <i class="mdi mdi-file-export align-middle me-1"></i>
                                            {{ "Export Data" }}
                                        </a> --}}
                                        <a class="btn btn-soft-success" href="{{ $title == 'Domain' ? route('admin.ati.domain-score.create') : route('admin.ati.indicator-score.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{$title." Score"}}
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
                                            <th>{{$title}}</th>
                                            <th>{{'Country'}}</th>
                                            <th>{{'Country Code'}}</th>
                                            <th>{{'Year'}}</th>
                                            <th>{{'Value'}}</th>
                                            <th>{{'Banded'}}</th>
                                            <th>{{'Imputed'}}</th>
                                            @if($title == "Domain")
                                            <th>{{'Domain Result'}}</th>
                                            @endif
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($countriesData as $countryData)
                                        <tr>
                                            <td>{{$countryData->serial_no}}</td>
                                            <td>{{$countryData->indicator->variablename}}</td>
                                            <td>{{optional($countryData->country)->country ?? 'No Country'}}</td>
                                            <td>{{$countryData->countrycode}}</td>
                                            <td>{{$countryData->year}}</td>
                                            <td>{{$countryData->country_score}}</td>
                                            <td>{{$countryData->banded}}</td>
                                            <td>{{$countryData->imputed}}</td>
                                            @if($title == "Domain")
                                            <td>{{$countryData->remarks}}</td>
                                            @endif
                                            <td>{{$countryData->user->name}}</td>
                                            <td>
                                                <a href="{{$title == 'Domain' ? route('admin.ati.domain-score.show',$countryData) : route('admin.ati.indicator-score.show',$countryData)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{$title == 'Domain' ? route('admin.ati.domain-score.edit',$countryData) : route('admin.ati.indicator-score.edit',$countryData)}}"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$countriesData->links()}}
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