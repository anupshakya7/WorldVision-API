@extends('worldvision.admin.dashboard.layout.web')
@section('title','Sub Country Data Bulk Import')
@section('content')
<style>
    .error {
        color: red;
    }

    .hidden {
        display: none;
    }

    .has-error .invalid-feedback {
        display: block;
        font-size: 16px;
    }

    .has-error .form-control {
        border: 1px solid red;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css" rel="stylesheet">
<!-- Import table plugin specific stylesheet -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/ui/trumbowyg.table.min.css">
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Sub Country Data</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">World Vision</a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.country-data.index')}}">Sub Country Data</a></li>
                                <li class="breadcrumb-item active">{{ 'Import Sub Country Data'}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-info">
                                <p class="mb-0">
                                    <span class="fw-semibold">NOTES:</span><br>
                                    <span id="note">*** Please insert csv file same as sample format for successful entry  *****
                                    </span>
                                </p>
                                  <a href="{{asset('sample/subcountry-data-sample.csv')}}" class="btn btn-primary ms-2 my-3" download="">
                                <i class="ri-download-line"></i> Download Sample
                            </a>
                                
                            </div>
			            </div>
			        </div>
			    </div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-header border-bottom-dashed">
							<h5 class="card-title mb-0">Import Sub Country Data</h5>
						</div>

						<div class="card-body">
						    
							<form action="{{route('admin.sub-country-data.bulk.insert')}}" method="POST" enctype="multipart/form-data">
								@csrf
                                <div class="row">
									<div class="col-12" style="margin-top:12px;">
									    <label for="myfile">Select a file: <span
                                            style="color:red;">*</span></label>
                                        <input type="file" class="form-control" name="csv_file">
									</div>
								</div>					
		
								<div class="col-12 text-end" style="margin-top:15px;">
									<button class="btn btn-success" type="submit" id="uploadButton">
										<i class="ri-save-line"></i> Import
									</button>  
								</div>                        
						</form>
					</div>				
				</div>
			</div>
		</div>
        </div>
    </div>

    @endsection
    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
    <!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/table/trumbowyg.table.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('#body-desc').trumbowyg({btns: [
			['viewHTML'],
			['formatting'],
			['strong', 'em', 'del'],
			['superscript', 'subscript'],
			['link'],
			['image'], // Our fresh created dropdown
			['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
			['unorderedList', 'orderedList'],
			['horizontalRule'],
			['removeformat'],
			['fullscreen'],
			['table'], 
			['tableCellBackgroundColor', 'tableBorderColor']
			]});
			//$('#sifaris').trumbowyg();

            //For Color Category
            $('#country_col').change(function(){
                var selectedOption = $(this).find('option:selected');
                var countryCategory = selectedOption.data('category') ?? null;

                if(countryCategory !== null){
                    $('#country_cat').val(countryCategory);
                }else{
                    $('#country_cat').val('');
                }
                
            });
            
        });

      var _url = "settings";
      @if(Session::has("message"))
        toastr.success("{{session('message')}}")
      @endif

    </script>
    @endsection