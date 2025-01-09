@extends('ati.admin.dashboard.layout.web')
@section('title','Domain Score')
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
                        <h4 class="mb-sm-0">{{"Domain Score"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.ati.home')}}">ATI</a></li>
                                <li class="breadcrumb-item">{{"Country Data"}}</li>
                                <li class="breadcrumb-item active">{{"Domain Score"}}</li>
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
                                        <h5 class="card-title mb-0">Domain Score</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        {{-- <a class="btn btn-soft-primary" href="{{ route('admin.ati.country-data.generate.csv') }}">
                                            <i class="mdi mdi-file-export align-middle me-1"></i>
                                            {{ "Export Data" }}
                                        </a> --}}
                                        <a class="btn btn-soft-success" href="{{ route('admin.ati.domain-score.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{ "Domain Score" }}
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
                                            <th>{{'Domain'}}</th>
                                            <th>{{'Country'}}</th>
                                            <th>{{'Country Code'}}</th>
                                            <th>{{'Year'}}</th>
                                            <th>{{'Score'}}</th>
                                            <th>{{'Domain Result'}}</th>
                                            <th>{{'Trend Result'}}</th>
                                            <th>{{'Trend Percentage'}}</th>
                                            <th>{{'Shift in Governance'}}</th>
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($countriesData as $countryData)
                                        <tr>
                                            <td>{{$countryData->serial_no}}</td>
                                            <td>{{$countryData->domain->variablename}}</td>
                                            <td>{{optional($countryData->country)->country ?? 'No Country'}}</td>
                                            <td>{{$countryData->countrycode}}</td>
                                            <td>{{$countryData->year}}</td>
                                            <td>{{$countryData->score}}</td>
                                            <td>{{$countryData->domain_result}}</td>
                                            <td>{{$countryData->trend_result}}</td>
                                            <td>{{$countryData->trend_percentage ? $countryData->trend_percentage.'%':''}}</td>
                                            <td>{{$countryData->shifts_governance}}</td>
                                            <td>{{$countryData->user->name}}</td>
                                            <td>
                                                <a href="{{route('admin.ati.domain-score.show',$countryData)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{route('admin.ati.domain-score.edit',$countryData)}}"><i
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