 $(document).ready(function(){  

 	// $('.btn-timein').click(function(e){
 	// 	e.preventDefault();
 	// 	$('.time-in-container').css({'display' : 'none'});
 	// 	$('.time-in').css({'display' : 'none'});
 	// 	// $('.timein-notif-container').css({'top':'0px'})
 	// 	$('.timed-in').css({'display' : 'block'});
     //
 	// 	setTimeout(function(){
 	// 		$('.timein-notif-container').css({'top' : '-55px'});
 	// 	}, 5000);
 	// });

 	$('.btn-timeout').click(function(e){
 		e.preventDefault();
 		$('.timeout-container').css({'display' : 'block'});
 		$('.confirm-timeout').css({'display' : 'block'});
 		$('.timeout').css({'display' : 'block'});
		timer = 0;
		checker = true;
 	});

 	// $('.btn-yes').click(function(e){
 	// 	e.preventDefault();
 	// 	$('.timeout-container').css({'display' : 'none'});
 	// 	$('.btn-timeout').css({'display' : 'none'});
 	// 	// $('.timeout-notif-container').css({'top':'0px'})
 	// 	$('.timed-in').css({'display' : 'none'});
 	// 	$('.timed-out').css({'display' : 'block'});
     //
 	// 	setTimeout(function(){
 	// 		$('.timeout-notif-container').css({'top' : '-55px'});
 	// 	}, 5000);
 	// });

 	$('.btn-cancel').click(function(e){
 		e.preventDefault();
 		$('.timeout-container, .logout-container').css({'display' : 'none'});
 		$('.confirm-timeout, .confirm-logout').css({'display' : 'none'});
 		$('.btn-yes').css({'display' : 'none'});
 		$('.btn-cancel').css({'display' : 'none'});
 	});

 	$('.btn-timeout').click(function(e){
 		e.preventDefault();
 		$('.timeout-container').css({'display' : 'block'});
 		$('.confirm-timeout').css({'display' : 'block'});
 		$('.btn-yes').css({'display' : 'inline-block'});
 		$('.btn-cancel').css({'display' : 'inline-block'});
		$('.forgot-timeout-modal').hide();

 	});


 	$('.timeout-notif-container').click(function(){
 		$('.timeout-notif-container').css({'top' : '-55px'});

 	});

 	// $('.timein-notif-container').click(function(){
 	// 	$('.timein-notif-container').css({'top' : '-55px'});
 	// });

 	$('.close-modal-agenda').click(function(){
 		$('#daily_agenda').closeModal();
 		$('.form-reqmeeting').css({'display':'none'});
 		$('.form-reqleave').css({'display':'none'});
 		$('.required-field').css({'display':'none'});
 		$('.sent').css({'display':'none'});
 		$('#meeting').val('');
 		$('#start-date').val('');
		$('#end-date').val('');
		$('#reason-leave').val('');

 	});

 	$('.btn-logout').click(function(e){
 		e.preventDefault();
 		$('.logout-container').css({'display' : 'block'});
 		$('.confirm-logout').css({'display' : 'block'});
 		$('.btn-yes').css({'display' : 'inline-block'});
 		$('.btn-cancel').css({'display' : 'inline-block'});
 	});

 	$('.form-reqmeeting').hide();
 	$('.form-reqleave').hide();

 	$('.btn-reqmeeting').click(function(){
 		$('.form-reqmeeting').show();
 		$('.form-reqleave').hide();
		$('.sent').hide();
		$('.required-field').hide();
	});

 	$('.btn-reqleave').click(function(){
 		$('.form-reqleave').show();
 		$('.form-reqmeeting').hide();
		$('.sent').hide();
		$('.required-field').hide();
 	});

// MODALS
 	$('.modal-reason').leanModal();
 	$('.modal-emp').leanModal();
 	$('.modal-dept').leanModal();
 	$('.modal-pos').leanModal();
 // END-MODALS


 	$('.responsive-nav').click(function(){
 		if($(this).hasClass('open')) {
 			$(this).removeClass('open');
 			$('.nav-right').css({'left':'-250px'});
 			$('.page-content').css({'left':'0'})

 		} else {
 			$(this).addClass('open');
 			$('.nav-right').css({'left':'0px'});
 			$('.page-content').css({'left':'250px'})
 		}

 	});

	$('.adsasdasdasd').pickadate({
		selectMonths: true,
		selectYears: 15
	});

	$('.asdasdasd').pickadate({
		selectMonths: true,
		selectYears: 15
	});

	$('.birth-date').pickadate({
		selectMonths: true,
		selectYears: 15
	});


	$('.btn-edit-profile').click(function(e){
    	e.preventDefault();

        $('.profile-container input').attr("disabled", false); 
        $('.edit-pdata').css({'display' : 'inline-block'});
        $('.btn-save-profile').css({'display' : 'inline-block'});
        $('.btn-cancel-profile').css({'display' : 'inline-block'});
        $('.btn-edit-profile').css({'display' : 'none'});
    });

	$(".edit-pdata").click(function(e) {
        e.preventDefault();
        $(this).parent('.profile-data').find('input').val('').focus();
        // $(this).parent('.collection-item').find('.btn-edit').css({'display':'none'})
    });

	$('.btn-save-profile').click(function(e){
        e.preventDefault();
        $validate = false;

        $('.btn-edit-profile').css({'display' : 'none'});

        if( !$("#cel-p").val() == '') {
            $validate = true;
            $("#cel-p").closest('.profile-data').find('.btn-edit').css({'display' : 'block'});
            $("#cel-p").closest('.profile-data').find('.required-field').css({'display' : 'none'})
            

        } else {
            $("#cel-p").closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
            $("#cel-p").closest('.profile-data').find('.btn-edit').css({'display':'none'})
            $("#cel-p").closest('.profile-data').find('input').val('').focus();

        }


        if( !$("#tel-p").val() == '') {
            $validate = true;
            $("#tel-p").closest('.profile-data').find('.btn-edit').css({'display' : 'block'});
            $("#tel-p").closest('.profile-data').find('.required-field').css({'display' : 'none'})
        } else {
            $("#tel-p").closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
            $("#tel-p").closest('.profile-data').find('.btn-edit').css({'display':'none'})
            $("#tel-p").closest('.profile-data').find('input').val('').focus();
            
        }

        if( !$("#addr").val() == '') {
            $validate = true;
            $("#addr").closest('.profile-data').find('.btn-edit').css({'display' : 'block'});
            $("#addr").closest('.profile-data').find('.required-field').css({'display' : 'none'})
        } else {
            $("#addr").closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
            $("#addr").closest('.profile-data').find('.btn-edit').css({'display':'none'})
            $("#addr").closest('.profile-data').find('input').val('').focus();
          
        }


        if(!$("#cel-p").val() == '' && $validate == true && !$("#tel-p").val() == '' && !$("#addr").val() == '') {
            $('.btn-save-profile').css({'display' : 'none'});
            $('.btn-cancel-profile').css({'display' : 'none'});
            $('.edit-pdata').css({'display' : 'none'});
            $('.btn-edit-profile').css({'display' : 'inline-block'});
            $('.profile-container input').attr("disabled", true); 
        } else {
            e.preventDefault();
        }

    });

 
  //
  // $('.btn-cancel-profile').click(function(e){
  //       e.preventDefault();
  //
  //          	$('.profile-container input').attr("disabled", true);
  //           $('.edit-pdata').css({'display' : 'none'});
  //           $('.btn-save-profile').css({'display' : 'none'});
  //           $('.btn-cancel-profile').css({'display' : 'none'});
  //           $('.btn-edit-profile').css({'display' : 'inline-block'});
  //
  //   });




	// $('.btn-accept').click(function(e){
	// 	e.preventDefault();
    //
	// 	$('.accept-notif-container').css({'margin-top':'50px'})
    //
	// 	setTimeout(function(){
	// 		$('.accept-notif-container').css({'margin-top' : '-55px'});
	// 	}, 5000);
    //
	// });

	$('.btn-decline').click(function(e){
		e.preventDefault();

		$('.reject').css({'display':'block'});
		$('.required-field').hide();
		$('.reject-reason').val('');
	});

	$('.btn-submitleave').click(function(e){
		e.preventDefault();

		if($("#reason-leave").val() == '') {
			$("#reason-leave").closest('.form-reqleave').find('.required-field').css({'display' : 'block'})
			$("#reason-leave").closest('.form-reqleave').find('.sent').css({'display' : 'none'})
		} else {
			$("#reason-leave").closest('.form-reqleave').find('.required-field').css({'display' : 'none'})
			$("#reason-leave").closest('.form-reqleave').find('.sent').css({'display' : 'block'})
			$("#reason-leave").val('');
		}
 	});

 	// $('.btn-sendtoemail').click(function(e){
 	// 	e.preventDefault();
     //
	 // 	if($("#reject-reason").val() == ''){
	 // 		 $("#reject-reason").closest('.reject').find('.required-field').css({'display' : 'block'})
     //
	 // 	} else {
	 // 		$("#reject-reason").closest('.reject').find('.required-field').css({'display' : 'none'})
	 //		
     //
	 // 		$("#reject-reason").css({'display':'none'});
	 // 		$('.sendtoemail-notif-container').css({'margin-top':'50px'})
	 // 		$('.btn-reasoncancel').css({'display':'none'})
	 // 		$('.btn-sendtoemail').css({'display':'none'})
     //
     //
	 // 		setTimeout(function(){
	 // 			$('.sendtoemail-notif-container').css({'margin-top' : '-55px'});
	 // 		}, 5000);
 	// 	}
 	// });

	$('.btn-add-emp').click(function(e){
	 		e.preventDefault();

	 		if($('.input-field').val() == ''){
	 			$('.input-field').closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
	 		}

	 			else{
	 				$('.add-success-container').css({'margin-top' : '0px'});
	 				$('.input-field').closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#9e9e9e'})
	 				$('.input-field').closest('.profile-data').find('input[type=text]').val('');
	 				$('#modal-emp').closeModal();
			 		setTimeout(function(){
			 			$('.add-success-container').css({'margin-top' : '-55px'});
			 		}, 5000);
	 			}
	 	});

 		$('.btn-add-pos').click(function(e){
 		e.preventDefault();

 			if($('#input-position').val() == ''){
 				$('#input-position').closest('#modal-pos').find('.required-field').css({'display' : 'block'})
 			}

 			else{
 				$('.add-success-container').css({'margin-top' : '0px'});
 				$("#input-position").closest('#modal-pos').find('.required-field').css({'display' : 'none'})
 				$('#input-position').val('');
 				$('#modal-pos').closeModal();
		 		setTimeout(function(){
		 			$('.add-success-container').css({'margin-top' : '-55px'});
		 		}, 5000);
 			}
 	});


 		$('.btn-add-dept').click(function(e){
 		e.preventDefault();

 			if($('#input-department').val() == ''){
 				$("#input-department").closest('#modal-dept').find('.required-field').css({'display' : 'block'})
 			}

 			else{
 				$('.add-success-container').css({'margin-top' : '0px'});
 				$("#input-department").closest('#modal-dept').find('.required-field').css({'display' : 'none'})
 				$('#input-department').val('');
 				$('#modal-dept').closeModal();
		 		setTimeout(function(){
		 			$('.add-success-container').css({'margin-top' : '-55px'});
		 		}, 5000);
 			}
 	});



 		$('.btn-cancel-input').click(function(e){
 		e.preventDefault();

 				$('#input-department').val('');
 				$('#input-position').val('');
 				$('.input-field').closest('.profile-data').find('input[type=text]').val('');
 				$('.input-field').closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#9e9e9e'})
 		});

			
		$("#upload-photo").change(function () {
	        var fileExtension = ['jpeg', 'jpg', 'png'];
    });

 		// $('.initialized').material_select();

 		// $('#calendar').fullCalendar('option', 'height', 650);
	 	$('.modal-change').leanModal();
	 	$('.modal-export').leanModal();

	 // $(".modal-content").closest("#exportchoice").find("#select-dept").change(function(){
		//  $('.departmentselect').select2();
		//  $('.input-name').select2('destroy');
		//  $('#empnames').val('');
	 // });
	 // $(".modal-content").closest("#exportchoice").find("#all-emp").change(function(){
		//  $('#listdept').val('');
		//  $('.departmentselect').select2('destroy');
		//  $('.input-name').select2('destroy');
		//  $('#empnames').val('');
	 // });
	 // $(".modal-content").closest("#exportchoice").find("#select-name").change(function(){
		//  $('.input-name').select2();
		//  $('.departmentselect').select2('destroy');
		//  $('#listdept').val('');
	 // });



	 $('.export-radio-input').change(function() {
		 var type = $(this).data('type'),
			 dept = $('.department-option-sect'),
			 emp  = $('.employee-option-sect');
		 emp.show();
		 dept.show();
		 $('.departmentselect,.input-name').val("").trigger("change");
		 if(type == 'all') {
			 dept.hide();
			 emp.hide();
		 } else if(type == 'dept') {
			 emp.hide();
			 $('#empnames').val('');
		 } else {
			dept.hide();
			 $('#listdept').val('');
		 }
	 })
 		

});  