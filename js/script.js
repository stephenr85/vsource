
(function($){

	vsource = window.vsource = {};
	vsource.apiUrl = 'http://vesnaus.com/vsource/v1.9';
	//vsource.apiUrl = '';

	vsource.log = function(){
		console.log(arguments);
	};

	vsource.alert = function(msg){
		if(typeof(window.navigator) !== 'undefined' && typeof(navigator.alert) !== 'undefined'){
			navigator.alert(msg);
		}else{
			alert(msg);
		}		
	};

	vsource.logout = function(page){
		if(!arguments.length) page = '#splash';
		$.cookie('vsource_auth_group', '');
		$.cookie('vsource_auth', '');
		if(page) $.mobile.changePage(page);
		return this;
	};

	vsource.setAuthToken = function(token){
		$.cookie('vsource_auth', token);
		$.ajaxSetup({
			beforeSend: function (xhr) {
				//vsource.log(typeof(this.data));
				if(!this.data) this.data = 'auth=' + vsource.authToken;
				else if (typeof(this.data) === 'string') this.data += '&auth=' + encodeURIComponent(vsource.authToken);
		        //xhr.setRequestHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
		        //xhr.setRequestHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
		        //xhr.setRequestHeader('Authorization','Token token="' + vsource.authToken + '"');
		    }
		});
		this.authToken = token;
		return this;
	};

	vsource.getAuthToken = function(){
		return $.cookie('vsource_auth');
	};
	vsource.authToken = vsource.getAuthToken();


	vsource.setAuthGroup = function(groupID){
		$.cookie('vsource_auth_group', groupID);
		return this;
	};
	vsource.getAuthGroup = function(){
		return $.cookie('vsource_auth_group');
	};

	vsource.gotoUserHome = function(authGroup){
		if(!arguments.length){
			authGroup = vsource.getAuthGroup();
		}
		if(authGroup == 1){
			$.mobile.changePage('#home');
		}else if(authGroup == 2){
			$.mobile.changePage('#guesthome');
		}else{
			console.log('Unknown authGroup: ' + authGroup)
			$.mobile.changePage('#guesthome');
		}
	};

	vsource.changePage = function(page, options){

		//if(!$(page).length){
			$(":mobile-pagecontainer").pagecontainer("change", page, options || {


			});

		//}
	};

	//check for cookie
	//set auth headers
	//send user to default page
	if(vsource.authToken){
		vsource.setAuthToken(vsource.authToken);

		$(document).ready(function(){
			vsource.gotoUserHome();
		});
	}


	vsource.init = function(){

		$.mobile.page.prototype.options.domCache = true;
	    $.mobile.allowCrossDomainPages = true;
	    $.support.cors = true;   

	    var $activePage = $(':mobile-pagecontainer').pagecontainer('getActivePage');
		if($activePage.is('#placeholder')){
			$(':mobile-pagecontainer').pagecontainer('change', '#splash');
		}

		//Load left nav panel
		$.get(vsource.apiUrl + '/views/panels/leftnav.php').then(function(html){
			$(html).appendTo('body').panel();
		});
		

		gapi.load('client', vsource.googleApiClientReady);

		

		vsource.domReady();
	};

	vsource.googleApiClientReady = function(){
		console.log('googleApiClientReady');

		gapi.client.setApiKey('AIzaSyAEyLPdX3kvIkGbetsf95OI5IqmQR4jOFc');
		gapi.client.load('youtube', 'v3', function(){
			console.log('YouTube API loaded');
		});

	};


	vsource.domReady = function(context){
		var $context = $(context || 'body');
		$context.find("[data-role='panel']").panel();
		$context.find("[data-role='button']").button();
		
	};


	
	$(document).on('click', '[href="#splash"]', function(){
		//Logout
		vsource.logout(null);
	});

	$(document).ajaxError(function(evt, xhr, settings, thrownError){		
		var isVsourceRequest = (vsource.apiUrl.length && settings.url.indexOf(vsource.apiUrl) >= 0) || settings.url[0] == '/';
		vsource.log('ajax error', isVsourceRequest, arguments);
		if(isVsourceRequest){
			if(xhr.status == 401 || xhr.status == 403){
				vsource.alert('Your session is no longer valid. Please login again.');
				$.mobile.changePage('#splash');
			}
		}
		
	});
	

	document.addEventListener("deviceready", onDeviceReady, false);

    // PhoneGap is loaded and it is now safe to make calls PhoneGap methods
    //
    function onDeviceReady() {
        // Now safe to use the PhoneGap API
    }


	$( document ).bind( "mobileinit", function() {
	    // Make your jQuery Mobile framework configuration changes here!
	   //doesn't seem to be firing
	   //alert('mobileinit');
	});


	$(document).ready(function() {
		vsource.init();
	});




	$(document).on('pagebeforeshow', function(evt, info){
		vsource.log('pagebeforeshow', arguments);

		var pageName = info.toPage.attr('id'),
			pageObj = vsource.pages[pageName];

		if(pageObj){
			if(pageObj.onShow) pageObj.onShow();
		}
	});

	//Load pages on demand
	$(document).on('pagecontainerchangefailed', function(evt, ui){
		vsource.log('changefailed', arguments);
		var pageName = ui.toPage.split('#')[1],
			pageObj = vsource.pages[pageName];

		if(pageObj){
			$.mobile.loading('hide', {
	            //text: msgText,
	            //textVisible: textVisible,
	            //theme: theme,
	            //textonly: textonly,
	            //html: html
		    });

			$.get(vsource.apiUrl + pageObj.url).then(function(html){
				var $html = $(html);
				$html.prependTo(':mobile-pagecontainer');
				vsource.domReady($html);
				
				if(pageObj.onLoad) pageObj.onLoad();

				$.mobile.loading('hide');

				$(':mobile-pagecontainer').pagecontainer('change', '#'+pageName, {
					transition:'slide'
				});
			});
		}
	});


	vsource.pages = {};

	vsource.pages['splash'] = {
		url: '/views/splash.php',

		onLoad: function(){
			var random = Math.floor(100000 + Math.random() * 900000);
			$('#random').val(random);

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
		},

		onShow: function(){
			
		}
	};

	vsource.pages['register'] = {
		url: '/views/register.php'
	};

	vsource.pages['forgotpassword'] = {
		url: '/views/forgot_password.php',

		onLoad: function(){
			$.get(vsource.apiUrl + '/views/password_tempcode.php').then(function(response){
				$(response).appendTo('body');
			});
			$.get(vsource.apiUrl + '/views/password_change.php').then(function(response){
				$(response).appendTo('body');
			});
		}
	};

	vsource.pages['guesthome'] = {
		url: '/views/guests/home.php'
	};

	vsource.pages['guestservices'] = {
		url: '/views/guests/services.php'
	};

	vsource.pages['gueststats'] = {
		url: '/views/guests/stats.php'
	};



	vsource.pages['home'] = {
		url: '/views/home.php',

		onShow: function(){
			//alert('test');
		}
	};

	vsource.pages['news'] = {
		url: '/views/news.php',

		onShow: function(){
			$('#twitterlink').click(function(){
				$('.twitterbox').hide();
			});

			 


			$('#newsclick').click(function(){
				$('.twitterbox').show();
			});

			$("p.rssincl-itemfeedtitle:contains(News)").text("VNA Newsroom");
			$("p.rssincl-itemfeedtitle:contains(Planet)").text("Planet North America");

			


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

		    $('.rssincl-entry').not(':has(.rssincl-itemimage)').prepend( "<div class='rssincl-itemimage'><img src='" + vsource.apiUrl + "/images/veolianewslogo.png' ></div>" ); 

		    $('#myTabs a').click(function (e) {
			  e.preventDefault();
			  $(this).tab('show');
			});

		    $('.rssincl-content').attr('data-filter','true');
			$('.rssincl-content').attr('data-input','#' + 'newsfeeder');
		}
	};

	vsource.pages['videos'] = {
		url: '/views/videos.php',

		onLoad: function(){
			$.get(vsource.apiUrl + '/views/youtube_modal.php').then(function(response){
				$(response).appendTo('body');
			});
		},

		onShow: function(){
			var I = this;

			setTimeout(function(){

				I.searchListByKeyword('snippet', 25, 'veolianorthamerica', 'video');
			
			}, 1);
			
			
		},

		searchListByKeyword: function(part, maxResults, q, type){
			var request = gapi.client.youtube.search.list(
			      {maxResults: maxResults,
			       part: part,
			       q: q,
			       type: type});
			request.execute(function(data){
				console.log(data);

				if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
	            if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
	            var items = data.items, videoList = "";
	            $("#pageTokenNext").val(data.nextPageToken);
	            $("#pageTokenPrev").val(data.prevPageToken);
	            $.each(items, function(index,e) {
	                videoList = videoList + '<tr style="border-bottom:2px solid #fff;"><td style="padding-bottom: 2px;"><div class=""><a href="https://www.youtube.com/watch?v='+e.id.videoId+'" class="" data-lity><div class="play"> </div><span class=""><img width="140px" alt="'+e.snippet.title +'" src="'+e.snippet.thumbnails.default.url+'" ></span></a></div></td><td style="padding-left:10px; padding-top: 5px;" vAlign="top"><span class="title">'+e.snippet.title+'</span><br>'+'</td></tr><tr class="spacer"></tr>';
	            });
	            $("#hyv-watch-related").html(videoList);
	            // JSON Responce to display for user
	            new PrettyJSON.view.Node({ 
	                el:$(".hyv-watch-sidebar-body"), 
	                data:data
	            });
			});
		}
	};

	vsource.pages['ideas'] = {
		url: '/views/ideas.php',

		onLoad: function(){
			$.get(vsource.apiUrl + '/views/idea_modal.php').then(function(response){
				$(response).appendTo('body');
			});
		}
	};

	vsource.pages['offices'] = {
		url: '/views/offices.php',

		isLocatorInitialized: false,
		onLoad: function(){
			var I = this;
			//Offices page to hide the suggestions section
			$('#bh-sl-address').focus(function() {

				$('.suggestions').fadeOut();
				$('.suggestionsshow').fadeIn();

			});

			//Google Maps location finder
			if(!I.isLocatorInitialized){
		 		$('#bh-sl-map-container').storeLocator({
					'mapSettings' : {
						zoom : 12,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
					},
					dataType: 'json',
					infowindowTemplatePath: vsource.apiUrl + '/js/plugins/storeLocator/templates/infowindow-description.html',
					listTemplatePath: vsource.apiUrl + '/js/plugins/storeLocator/templates/location-list-description.html',
					KMLinfowindowTemplatePath: vsource.apiUrl + '/js/plugins/storeLocator/templates/kml-infowindow-description.html',
					KMLlistTemplatePath: vsource.apiUrl + '/js/plugins/storeLocator/templates/kml-location-list-description.html',
					dataLocation: vsource.apiUrl + '/data/locations.json'
					
				});
				I.isLocatorInitialized = true;
		 	}	 	
		}
	};

	vsource.pages['tools'] = {
		url: '/views/tools.php'
	};

	vsource.pages['ask'] = {
		url: '/views/ask.php'
	};

	vsource.pages['join'] = {
		url: '/views/join.php'
	};

	vsource.pages['about'] = {
		url: '/views/about.php',

		onLoad: function(){

			$.get(vsource.apiUrl + '/views/feedback_modal.php').then(function(response){
				$(response).appendTo('body');
			});

			$('#providefeedback').click(function(){
				$('.feedbackform').show();
				$('#feedbackarea').focus();
			});
		}
	};

	

	

	

	

	




	// User sign in	
	$(document).on('submit','#signinform', function(e){
		
		e.preventDefault();
		var formdata = $('#signinform').serialize();

		$.post(vsource.apiUrl + '/signin.php', formdata, function(data){
				if (data == 0) {
					vsource.alert('Your login information is incorrect!');
				}else{
					data = data.split(';');
					vsource.setAuthGroup(data[0]);
					vsource.setAuthToken(data[1]);

					if(data[0] == 1){

						vsource.changePage('#home');
					}else{
						$.mobile.changePage('#guesthome');
					}
				}

			})
	});	

	// User registration	
	$(document).on('submit','#userreg', function(e){
		
		e.preventDefault();
		$('.loadingsignup').show();
		var regdetails = $('#userreg').serialize();
		$.post(vsource.apiUrl + '/userregistration.php', regdetails, function(data){
		
		if (data == 0) {
			vsource.alert('You already have an account!');
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
			vsource.alert("You have entered an invalid confirmation code.");
			$('.validatesignup').hide();
		}
			else if (data == 1) {
				$.mobile.changePage( "#splash");
				vsource.alert("Your account is now active. Please login.")		
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
			vsource.alert("No account found!");
			
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
				vsource.alert("No account found!");
				
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
				vsource.alert("Password not updated. Contact administrator.");
				
			}
			else if (data == 1) {
				vsource.alert("Password updated!");
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
	 
	$(document).on('click', 'a[target=_blank]', function(e) {
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
	$(document).on('click', '#learnbutton', function(){

		$('.loading').hide();
		$.mobile.changePage( "#join");
		$('.idea').val('');
		$('.problem').val('');
		$('.solve').val('');

	});	
		

	//Close button 	
	$(document).on('click', '#closebutton', function(){
		$('.loading').hide();
		$('.idea').val('');
		$('.problem').val('');
		$('.solve').val('');
	});	
		

	//Display submit button when one of the form fields are entered	
	$(document).on('blur', '.idea', function(){

		$('.submitbutton').fadeIn();

	}); 

	$(document).on('blur', '.problem', function(){

		$('.submitbutton').fadeIn();

	}); 
		
	$(document).on('blur', '.solve', function(){

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
	  
	  
	

	


	
	


})(jQuery);
   
