@extends('worldvision.admin.dashboard.layout.web')
@section('title','Indicator')
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
                        <h4 class="mb-sm-0">{{"Indicator"}}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item active">{{"Indicator"}}</li>
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
                                        <h5 class="card-title mb-0">Indicator</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <a class="btn btn-soft-success" href="{{ route('admin.indicator.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{ "Indicator" }}
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
                                            <th>{{'Variable Name Long'}}</th>
                                            <th>{{'Variable Name'}}</th>
                                            <th>{{'Variable Description'}}</th>
                                            <th>{{'Variable Units'}}</th>
                                            <th>{{'Is More Better'}}</th>
                                            <th>{{'Transformation'}}</th>
                                            <th>{{'Lower'}}</th>
                                            <th>{{'Upper'}}</th>
                                            <th>{{'Source Links'}}</th>
                                            <th>{{'Subnational'}}</th>
                                            <th>{{'National'}}</th>
                                            <th>{{'Imputation'}}</th>
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($indicators as $indicator)
                                        <tr>
                                            <td>{{$indicator->serial_no}}</td>
                                            <td>{{$indicator->domain}}</td>
                                            <td>{{$indicator->variablename_long}}</td>
                                            <td>{{$indicator->variablename}}</td>
                                            <td>{{Str::limit($indicator->vardescription,50)}}</td>
                                            <td>{{Str::limit($indicator->varunits,40)}}</td>
                                            <td>{{$indicator->is_more_better}}</td>
                                            <td>{{$indicator->transformation}}</td>
                                            <td>{{$indicator->lower}}</td>
                                            <td>{{$indicator->upper}}</td>
                                            <td>{{$indicator->sourcelinks}}</td>
                                            <td>{{$indicator->subnational}}</td>
                                            <td>{{$indicator->national}}</td>
                                            <td>{{$indicator->imputation}}</td>
                                            <td>{{$indicator->user->name }}</td>
                                            <td>
                                                <a href="{{route('admin.indicator.show',$indicator)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{route('admin.indicator.edit',$indicator)}}"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$indicators->links()}}
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