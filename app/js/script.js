
(function($){

	vsource = window.vsource = {};
	//vsource.apiUrl = 'http://vesnaus.com/vsource';
	vsource.apiUrl = '';


	document.addEventListener("deviceready", onDeviceReady, false);

    // PhoneGap is loaded and it is now safe to make calls PhoneGap methods
    //
    function onDeviceReady() {
        // Now safe to use the PhoneGap API
    }

	$(document).ready(function ($) {
	    $("#twitbox").twitterFetcher({
	        widgetid: '370406282223566848', 
	        lang: 'en',
	        showImages: true,
	        enableLinks:true,
	        showRetweet: true,
	        maxTweets: 150,
	        enablePermalink: true
	    });

	    $('#twitbox').on('click', 'a[href^="http"]', function(evt){
	    	evt.preventDefault();

	    	window.open(this.href, $(this).attr('target') || '_blank');
	    });
	});

	$( document ).bind( "mobileinit", function() {
	    // Make your jQuery Mobile framework configuration changes here!

	   $.mobile.allowCrossDomainPages = true;
	   $.support.cors = true;
	});


	$(document).ready(function() {
		$( "body > [data-role='panel']" ).panel();
	});

	$('#providefeedback').click(function(){
		$('.feedbackform').show();
		$('#feedbackarea').focus();
	});

	$('.rssincl-entry').not(':has(.rssincl-itemimage)').prepend( "<div class='rssincl-itemimage'><img src='" + vsource.apiUrl + "/images/veolianewslogo.png' ></div>" ); 


	$('#twitterlink').click(function(){
		$('.twitterbox').hide();
	});

	 $('#userreg').validate({
	  	rules: {
	  	  fname: "required",
	  	  lname: "required",
	  	  phone: "required",
	      email: "required",
	  	  password: "required",
	  	  password_again: {
	      equalTo: "#passwordtwo"
	      }
	    },
	  	errorClass: "form-invalid"
	  });
	  

	 $("#validatecode").validate({
	  	rules: {
	  	  valcode: "required",
	    },
	  	errorClass: "form-invalid"
	  });


	$('#newsclick').click(function(){
		$('.twitterbox').show();
	});

	$("p.rssincl-itemfeedtitle:contains(News)").text("VNA Newsroom");
	$("p.rssincl-itemfeedtitle:contains(Planet)").text("Planet North America");



	var random = Math.floor(100000 + Math.random() * 900000);
	$('#random').val(random);

	//Google Maps location finder

	var isLocatorInitialized = false;

	 $(document).on('pagebeforeshow', '#offices[data-role=page]', function(evt){

	 	if(!isLocatorInitialized){
	 		$('#bh-sl-map-container').storeLocator({
				'mapSettings' : {
					zoom : 12,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					
					'dataType': 'json',
					'dataLocation': 'data/locations.json'
				}
			});
			isLocatorInitialized = true;
	 	}	 	

	 });




	// User sign in	
	$(document).on('submit','#signinform', function(e){
		
		e.preventDefault();
		var regdetails = $('#signinform').serialize();
		$.post(vsource.apiUrl + '/signin.php', regdetails, function(data){
				if (data == 0) {
					alert('Your login information is incorrect!');
				}
				
				else if (data == 1) {
					$.mobile.changePage( "#home");			
				}
				else if (data == 2) {
					$.mobile.changePage( "#guesthome");
				}	
				;
			})
	});	

	// User registration	
	$(document).on('submit','#userreg', function(e){
		
		e.preventDefault();
		$('.loadingsignup').show();
		var regdetails = $('#userreg').serialize();
		$.post(vsource.apiUrl + '/userregistration.php', regdetails, function(data){
		
		if (data == 0) {
			alert('You already have an account!');
			$('.loadingsignup').hide();
		}
		
		else {
		$.mobile.changePage( "#validate");
		$('.loadingsignup').hide();
		$('.userID').val(data);
		userID = data;
		
		}
	})
	});	


	// Validate Code	
	$(document).on('click','#validationbutton', function(e){
		e.preventDefault();
		$('.validatesignup').show();
		var regdetails = $('#validatecode').serialize();
		$.post(vsource.apiUrl + '/validatecode.php', regdetails, function(data){
		
		if (data == 0) {
			alert("You have entered an invalid confirmation code.");
			$('.validatesignup').hide();
		}
			else if (data == 1) {
				$.mobile.changePage( "#splash");
				alert("Your account is now active. Please login.")		
			}
		})
	});	


	// password forgot
	$(document).on('click','#newpassword', function(e){
		e.preventDefault();
		var email = $('#forgotemail').val();
		var regdetails = $('#forgotpassword').serialize();
		$.post(vsource.apiUrl + '/resetpassword.php', regdetails, function(data){
		
		if (data == 0) {
			alert("No account found!");
			
		}
			else if (data == 1) {
				$.mobile.changePage( "#entertemppassword");
				$('.emailcode').val(email);
			}
		})
	});	



	// validate password change
	$(document).on('click','#newpasscodebutton', function(e){
		e.preventDefault();
		var tempCode = $('[name=newcode]:input').val();
		var regdetails = $('#newcode').serialize();
		$.post(vsource.apiUrl + '/newpassword.php', regdetails, function(data){
		
			if (data == 0) {
				alert("No account found!");
				
			}
			else if (data == 1) {
				$('.tempcode').val(tempCode);
				$.mobile.changePage( "#changepassword");		
			}
		})
	});	


	// save new password
	$(document).on('click','#savenewpasscodebutton', function(e){
		e.preventDefault();
		
		var regdetails = $('#changepasswordform').serialize();
		$.post(vsource.apiUrl + '/changepassword.php', regdetails, function(data){
		
			if (data == 0) {
				alert("Password not updated. Contact administrator.");
				
			}
			else if (data == 1) {
				alert("Password updated!");
				$.mobile.changePage( "#");		
			}
		})
	});	
	




	// Share Idea	
	$(document).on('click','#shareideabutton', function(e){
		e.preventDefault();
		$('.loading').show();
		var regdetails = $('#shareidea').serialize();
		$.post(vsource.apiUrl + '/shareidea.php', regdetails, function(data){
		if (data == 0) {
			$('#myModal').modal('show');
		}
		
		})
	});	


	// Share Feedback	
	$(document).on('click','#feedbacksubmit', function(e){
		e.preventDefault();
		$('.loading').show();
		var regdetails = $('#feedbackform').serialize();
		$.post(vsource.apiUrl + '/providefeedback.php', regdetails, function(data){
		if (data == 0) {
			$('#feedbackModal').modal('show');
		}
		
		})
	});	


	// Open links in system browser
	 
	$('a[target=_blank]').on('click', function(e) {
	e.preventDefault();
	window.open($(this).attr('href'), '_system');
	return false;
	}); 


	//Feedback area buttons

	$('#sweetbutton').click(function(){

		$('.loading').hide();
		$('#feedbackarea').val('');
	});
		
	$('.close').click(function(){
		$('.loading').hide();
		$('#feedbackarea').val('');
	});	
				

	//Feedback confirmation buttons	
	$('#learnbutton').click(function(){

		$('.loading').hide();
		$.mobile.changePage( "#join");
		$('.idea').val('');
		$('.problem').val('');
		$('.solve').val('');

	});	
		

	//Close button 	
	$('#closebutton').click(function(){
		$('.loading').hide();
		$('.idea').val('');
		$('.problem').val('');
		$('.solve').val('');
	});	
		

	//Display submit button when one of the form fields are entered	
	$('.idea').blur(function(){

		$('.submitbutton').fadeIn();

	}); 

	$('.problem').blur(function(){

		$('.submitbutton').fadeIn();

	}); 
		
	$('.solve').blur(function(){

		$('.submitbutton').fadeIn();

	}); 	


	(function ($) {
	    "use strict";
	    function centerModal() {
	        $(this).css('display', 'block');
	        var $dialog  = $(this).find(".modal-dialog"),
	        offset       = ($(window).height() - $dialog.height()) / 2,
	        bottomMargin = parseInt($dialog.css('marginBottom'), 10);

	        // Make sure you don't hide the top part of the modal w/ a negative margin if it's longer than the screen height, and keep the margin equal to the bottom margin of the modal
	        if(offset < bottomMargin) offset = bottomMargin;
	        $dialog.css("margin-top", offset);
	    }

	    $(document).on('show.bs.modal', '.modal', centerModal);
	    $(window).on("resize", function () {
	        $('.modal:visible').each(centerModal);
	    });
	}(jQuery));	
		

	//Offices page to hide the suggestions section
	$('#bh-sl-address').focus(function() {

		$('.suggestions').fadeOut();
		$('.suggestionsshow').fadeIn();

	});	


		
	(function ($) {
	    "use strict";
	    function centerModal() {
	        $(this).css('display', 'block');
	        var $dialog  = $(this).find(".modal-dialog"),
	        offset       = ($(window).height() - $dialog.height()) / 2,
	        bottomMargin = parseInt($dialog.css('marginBottom'), 10);

	        // Make sure you don't hide the top part of the modal w/ a negative margin if it's longer than the screen height, and keep the margin equal to the bottom margin of the modal
	        if(offset < bottomMargin) offset = bottomMargin;
	        $dialog.css("margin-top", offset);
	    }

	    $(document).on('show.bs.modal', '.modal', centerModal);
	    $(window).on("resize", function () {
	        $('.modal:visible').each(centerModal);
	    });
	}(jQuery));	
	  
	  
	$('#myTabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})  

	$('.rssincl-content').attr('data-filter','true');
	$('.rssincl-content').attr('data-input','#' + 'newsfeeder');


	function searchListByKeyword(part, maxResults, q, type) {
	  var request = gapi.client.youtube.search.list(
	      {maxResults: maxResults,
	       part: part,
	       q: q,
	       type: type});
	  executeRequest(request);
	}

	searchListByKeyword('snippet', 25, 'veolianorthamerica', 'video');


})(jQuery);
   
