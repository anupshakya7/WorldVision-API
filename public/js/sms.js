$(".block_check_all").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});


$('.accordion-body').on('click','.member_check_all',function(){
				
	if($(this).is(":checked")){
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',true);
		//console.log('hello');
	}else{
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',false);

	}
});


$('.accordion-body').on('click','.senior_check_all',function(){
				
	if($(this).is(":checked")){
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',true);
		//console.log('hello');
	}else{
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',false);

	}
});

$('.accordion-body').on('click','.owner_check_all',function(){
				
	if($(this).is(":checked")){
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',true);
		//console.log('hello');
	}else{
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',false);

	}
});

$('.accordion-body').on('click','.business_check_all',function(){
	console.log('business checked');
	if($(this).is(":checked")){
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',true);
		//console.log('hello');
	}else{
		$(this).parents('.accordion-body').find('input[type="checkbox"]').prop('checked',false);

	}
});

$('.add-contacts').on('click',function(){
	//console.log('add contact btn clicked');
	var addnos = "";
	
	$('.box input[type="checkbox"]:checked').each(function(){
		var number = $(this).attr('number');
		if(typeof number!=""){
			addnos+=number+', ';
		}
	});
	
	if(addnos!=''){
		$('#destination_content').val(addnos);
		 var no = addnos.split(",");
		 var total=no.length-1;
		 console.log(total);
		 $('.total').html('('+total+')');
	}else{
		alert('Please Select Numbers');
	}
});

/*setting messages in textarea depending on message template selected*/
$('#msg_tpl_select').on('change',function(){
		var msg_tmp=$(this).val();
		//console.log(msg_tmp);
		$('.message_content').val(msg_tmp);
});

/*counting character in textarea*/
$(document).ready(function() {
    var $txtArea = $('.message_content');
    var $chars   = $('#chars');
    var textMax = $txtArea.attr('maxlength');
	//console.log(textMax);
    $chars.html(textMax + ' characters remaining');

    $txtArea.on('keyup', countChar);
    
    function countChar() {
        var textLength = $txtArea.val().length;
        var textRemaining = textMax - textLength;
        $chars.html(textRemaining + ' characters remaining');
    };
});