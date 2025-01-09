var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

//searching members
$("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".members .box").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
 
//searching assigned members 
$("#myGroup").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".member-group .groupbox").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  
$(".block_check_all").click(function(){
    //$('input:checkbox').not(this).prop('checked', this.checked);
	var targetchk = $(this);
	if($(targetchk).is(':checked')){
		$('.members').find(':checkbox').each(function(){
			 $(this).prop('checked', true);
		});
	}else{
		$('.members').find(':checkbox').each(function(){
			 $(this).prop('checked', false);
		});
	}	
	
});

$(".group_check_all").click(function(){
    //$('input:checkbox').not(this).prop('checked', this.checked);
	var targetchk = $(this);
	if($(targetchk).is(':checked')){
		$('.member-group').find(':checkbox').each(function(){
			 $(this).prop('checked', true);
		});
	}else{
		$('.member-group').find(':checkbox').each(function(){
			 $(this).prop('checked', false);
		});
	}	
	
});

$('.addmember').on('click',function(){
	//console.log('add contact btn clicked');
	$('.loader').addClass('loader-show');
	$('.loader').show();
	const addnos = [];
	var groupid = $(this).attr('groupid');
	
	$('.box input[type="checkbox"]:checked').each(function(){
		var number = $(this).attr('number');
		var member = $(this).attr('mname');
		var citizen = $(this).attr('mcitizenship');
		var divinner = '<div class="row g-4 align-items-center groupbox">'+
							'<div class="col-sm">'+								
								'<div class="form-check form-check-success mb-12">'+
									'<input class="form-check-input" type="checkbox" id="formCheck6" mcitizenship="'+citizen+'" mname="'+member+'" number="'+number+'">'+
									'<label class="form-check-label" for="formCheck6">'+member+' ('+citizen+')'+ 
									'</label>'+
								'</div>'+
							'</div>'+
						'</div>';
		$('.member-group').append(divinner);
		$(this).parents('.box').remove();
		if(typeof number!=""){
			addnos.push(number);
		}
	});
	
	if(addnos!=''){
		//$('#destination_content').val(addnos);
		//var array = addnos.split(', ');
		var urlpath = baseUrl+"/public/member-assign";
		$.ajax({
				type:'post',
						url:urlpath,
						data:{memberid:addnos,groupid:groupid},
						//dataType: "json",
						success: function(res){
							//console.log(res);
							setTimeout(function(){
								$('.loader').removeClass('loader-show');
								$('.loader').hide();
								toastr.success("Successfully added to the group");
							},2000);
							
						},error:function(){
							console.log('Unable to currently process data!!');
						}
			});
		//console.log(member);
		
	}else{
		alert('Please Select Numbers');
		$('.loader').removeClass('loader-show');
		$('.loader').hide();
	}
});

$('.removemember').on('click',function(){
	//console.log('add contact btn clicked');
	$('.loader').addClass('loader-show');
	$('.loader').show();
	const addmembs = [];
	var groupid = $(this).attr('groupid');
	
	$('.groupbox input[type="checkbox"]:checked').each(function(){
		var number = $(this).attr('number');
		var member = $(this).attr('mname');
		var citizen = $(this).attr('mcitizenship');
		var divinner = '<div class="row g-4 align-items-center groupbox">'+
							'<div class="col-sm">'+								
								'<div class="form-check form-check-success mb-12">'+
									'<input class="form-check-input" type="checkbox" id="formCheck6" mcitizenship="'+citizen+'" mname="'+member+'" number="'+number+'">'+
									'<label class="form-check-label" for="formCheck6">'+member+' ('+citizen+')'+ 
									'</label>'+
								'</div>'+
							'</div>'+
						'</div>';
		$('.members').prepend(divinner);
		
		$(this).parents('.groupbox').remove();
		if(typeof number!=""){
			addmembs.push(number);
		}
	});
	
	if(addmembs!=''){
		//$('#destination_content').val(addnos);
		var urlpath = baseUrl+"/public/member-dismiss";
		console.log(urlpath);
		$.ajax({
			type:'post',
					url:urlpath,
					data:{memberid:addmembs,groupid:groupid},
					//dataType: "json",
					success: function(res){
						//console.log(res);
						setTimeout(function(){
								$('.loader').removeClass('loader-show');
								$('.loader').hide();
								toastr.success("Successfully removed from the group")
							},2000);						
						
					},error:function(){
						console.log('Unable to currently process data!!');
					}
		});
		
	}else{
		alert('Please Select Numbers');
		$('.loader').removeClass('loader-show');
		$('.loader').hide();
	}
});