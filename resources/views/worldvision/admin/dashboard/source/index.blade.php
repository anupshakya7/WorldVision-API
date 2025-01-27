@extends('worldvision.admin.dashboard.layout.web')
@section('title','Source')
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
                                <li class="breadcrumb-item active">{{"Source"}}</li>
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
                                        <h5 class="card-title mb-0">Source</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <a class="btn btn-soft-success" href="{{ route('admin.source.create') }}">
                                            <i class="ri-add-circle-line align-middle me-1"></i> {{ "Add"
                                            }} {{ "Source" }}
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
                                            {{-- <th>{{'Indicator'}}</th> --}}
                                            <th>{{'Source'}}</th>
                                            {{-- <th>{{'Data Level'}}</th> --}}
                                            <th>{{'Impid'}}</th>
                                            <th>{{'Units'}}</th>
                                            <th>{{'Description'}}</th>
                                            <th>{{'Url'}}</th>
                                            <th>{{'Link'}}</th>
                                            <th>{{'Created By'}}</th>
                                            <th>{{'Action'}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sources as $source)
                                        <tr>
                                            <td>{{$source->serial_no}}</td>
                                            {{-- <td>{{optional($source->indicator)->variablename}}</td> --}}
                                            <td>{{$source->source}}</td>
                                            {{-- <td>{{$source->data_level}}</td> --}}
                                            <td>{{$source->impid}}</td>
                                            <td>{{$source->units}}</td>
                                            <td>{{Str::limit($source->description,100)}}</td>
                                            <td>{{$source->url}}</td>
                                            <td>{{$source->link}}</td>
                                            <td>{{$source->user->name}}</td>
                                            <td>
                                                <a href="{{route('admin.source.show',$source)}}"><i
                                                        class="ri-eye-line align-bottom me-2 text-success"></i>
                                                    <a href="{{route('admin.source.edit',$source)}}"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-primary"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$sources->links()}}
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