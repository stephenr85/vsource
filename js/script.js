
(function($){

	vsource = window.vsource = {};
	vsource.apiUrl = 'http://vesnaus.com/vsource/v1.6';
	//vsource.apiUrl = '';
	vsource.gapiKey = 'AIzaSyAEyLPdX3kvIkGbetsf95OI5IqmQR4jOFc';
	vsource.initHash = window.location.hash;	



	vsource.log = function(){
		console.log(arguments);
	};

	vsource.alert = function(msg, callback, title, buttonName){
		if(!title) title = document.title;

		if(typeof(window.navigator) !== 'undefined' && typeof(navigator.notification) !== 'undefined'){
			navigator.notification.alert(msg, callback, title, buttonName);
		}else{
			alert(msg);
			if(typeof(callback) === 'function'){
				callback();
			}			
		}		
	};


	vsource.logout = function(){
		
		vsource.setAuthGroup('');
		vsource.setAuthToken('');
		window.location.reload();

		return this;
	};

	vsource.setAuthToken = function(token){
		$.cookie('vsource_auth', token);
		if(window.localStorage){
			window.localStorage.setItem("vsource_auth", token);
		}
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
		vsource.authToken = token;
		return this;
	};

	vsource.getAuthToken = function(){
		if(window.localStorage){
			return window.localStorage.getItem("vsource_auth");
		}else{
			return $.cookie('vsource_auth');
		}
		
	};
	//Check for existing auth cookie
	vsource.authToken = vsource.getAuthToken();
	if(vsource.authToken){
		vsource.setAuthToken(vsource.authToken);
	}

	vsource.setAuthGroup = function(groupID){
		$.cookie('vsource_auth_group', groupID * 1);
		if(window.localStorage){
			window.localStorage.setItem('vsource_auth_group', groupID * 1);
		}
		return this;
	};
	vsource.getAuthGroup = function(){
		if(window.localStorage){
			return window.localStorage.getItem('vsource_auth_group') * 1;
		}else{
			return $.cookie('vsource_auth_group') * 1;
		}		
	};

	vsource.gotoUserHome = function(authGroup){
		vsource.log('gotoUserHome', arguments);
		if(!arguments.length || arguments[0] === null){
			authGroup = vsource.getAuthGroup();
		}
		//vsource.alert('Auth group: ' + authGroup + typeof(authGroup));

		for(var alias in vsource.pages){
			var page = vsource.pages[alias];
			if(authGroup === page.authGroup && page.isHome){
				$(':mobile-pagecontainer').pagecontainer('change', '#' + alias);
			}
		}
	};

	vsource.changePage = function(page, options){

		//if(!$(page).length){
			$(':mobile-pagecontainer').pagecontainer('change', page, options || {


			});

		//}
	};


	vsource.init = function(){
		//remove local stylesheet now that remote JS has loaded
		$('link[href="css/styles.css"]').remove();


		$.mobile.page.prototype.options.domCache = true;
	    $.mobile.allowCrossDomainPages = true;
	    $.support.cors = true;   

	    var $activePage = $(':mobile-pagecontainer').pagecontainer('getActivePage');

	    //Send user to splash/login by default
		if(!vsource.authToken){
			$(':mobile-pagecontainer').pagecontainer('change', '#splash');
		}
		//If they have an auth token and there's already an initHash, send them there
		else if(vsource.initHash){
			$(':mobile-pagecontainer').pagecontainer('change', vsource.initHash);

		}
		//Send authenticated user to respective "home" page
		else{
			vsource.gotoUserHome();
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

		gapi.client.setApiKey(vsource.gapiKey);
		gapi.client.load('youtube', 'v3', function(){
			console.log('YouTube API loaded');
			vsource.whenYouTube.resolve();
		});

	};

	vsource.whenYouTube = $.Deferred();


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
			$.mobile.loading('show', {
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

	vsource.pages['register_validation'] = {
		url: '/views/register_validation.php'
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
		url: '/views/guests/home.php',
		authGroup: 2,
		isHome: true
	};

	vsource.pages['guestservices'] = {
		url: '/views/guests/services.php'
	};

	vsource.pages['gueststats'] = {
		url: '/views/guests/stats.php'
	};



	vsource.pages['home'] = {
		url: '/views/home.php',
		authGroup: 1,
		isHome: true,
		onShow: function(){
			//alert('test');
		}
	};

	vsource.pages['news'] = {
		url: '/views/news.php',

		onLoad: function(){
			$('#twitterlink').click(function(){
				$('.twitterbox').hide();
			});


			$('#newsclick').click(function(){
				$('.twitterbox').show();
			});

			$("p.rssincl-itemfeedtitle:contains(News)").text("VNA Newsroom");
			$("p.rssincl-itemfeedtitle:contains(Planet)").text("Planet North America");

			
			/*

			$("#twitbox").twitterFetcher({
		        widgetid: '370406282223566848', 
		        lang: 'en',
		        showImages: true,
		        enableLinks:true,
		        showRetweet: true,
		        maxTweets: 150,
		        enablePermalink: true
		    });

			*/
		    $('#twitbox').on('click', 'a[href^="http"]', function(evt){
		    	evt.preventDefault();

		    	window.open(this.href, $(this).attr('target') || '_blank');
		    });


		    $('#twitbox').on('click', 'a[href^="http"]', function(evt){
		    	evt.preventDefault();

		    	window.open(this.href, $(this).attr('target') || '_blank');
		    });

		    (function() {
			  var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
			  s.src = 'http://output17.rssinclude.com/output?type=asyncjs&id=1096476&hash=aa04a2a29484dc3e2905773e0dd03a6a';
			  document.getElementsByTagName('head')[0].appendChild(s);
			})();

			var onInitFeed = setInterval(function(){

				if($('.rssincl-content').length){
					$('.rssincl-entry').not(':has(.rssincl-itemimage)').prepend( "<div class='rssincl-itemimage'><img src='" + vsource.apiUrl + "/images/veolianewslogo.png' ></div>" );
				    $('.rssincl-content').attr('data-filter','true');
					$('.rssincl-content').attr('data-input','#' + 'newsfeeder');
					$('.rssincl-content').filterable();
					clearInterval((onInitFeed));
				}

			}, 250);

	
		    
		    $('.rssfeed').on('click', 'a[href*="rss-site-updates"]', function(evt){
		    	evt.preventDefault();
		    	window.open('http://www.veolianorthamerica.com/en/media/media/newsroom', $(this).attr('target') || '_blank');
		    	return false;
		    });

		    $('#myTabs a').click(function (e) {
			  e.preventDefault();
			  $(this).tab('show');
			});


		    
		}
	};

	vsource.pages['videos'] = {
		url: '/views/videos.php',

		onLoad: function(){
			var I = this;

			$.get(vsource.apiUrl + '/views/youtube_modal.php').then(function(response){
				$(response).appendTo('body');
			});


			I.loadAboutVideos();
			$('[href="#videofeed"]').on('click', function(){
				I.loadAboutVideos();
			});

			$('[href=#globalfeed]').on('click', function(){
				I.loadServicesVideos();
			});
			
			
		},

		onShow: function(){
			var I = this;			
		},

		loadAboutVideos: function(){

			return vsource.whenYouTube.done(function(){


				var request = gapi.client.youtube.playlistItems.list({
					playlistId: 'PLH-nnUXtAYzpIJxKuLmldQ8EW0Kyu6dSL',
					maxResults: 25,
				    relevanceLanguage: 'en',
				    type: 'video',
				    part: 'snippet'
				});

				request.execute(function(data){
					vsource.log(data);

					if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
		            if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
		            var items = data.items, videoList = "";
		            $("#pageTokenNext").val(data.nextPageToken);
		            $("#pageTokenPrev").val(data.prevPageToken);
		            $.each(items, function(index,e) {
		                videoList = videoList + '<tr style="border-bottom:2px solid #fff;"><td style="padding-bottom: 2px;"><div class=""><a href="https://www.youtube.com/watch?v='+e.snippet.resourceId.videoId+'" class="" data-lity><div class="play"> </div><span class=""><img width="140px" alt="'+e.snippet.title +'" src="'+e.snippet.thumbnails.default.url+'" ></span></a></div></td><td style="padding-left:10px; padding-top: 5px;" vAlign="top"><span class="title">'+e.snippet.title+'</span><br>'+'</td></tr><tr class="spacer"></tr>';
		            });
		            $("#hyv-watch-related").html(videoList);
				});

			});

		},

		loadServicesVideos: function(){

			return vsource.whenYouTube.done(function(){

				var request = gapi.client.youtube.playlistItems.list({
				    playlistId: 'PLH-nnUXtAYzp7UDj29arg6RlyLDuJ-cEa',
				    maxResults: 25,
				    relevanceLanguage: 'en',
				    type: 'video',
				    part: 'snippet'
				});

				request.execute(function(data){
					vsource.log(data);

					if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
		            if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
		            var items = data.items, videoList = "";
		            $("#pageTokenNext").val(data.nextPageToken);
		            $("#pageTokenPrev").val(data.prevPageToken);
		            $.each(items, function(index,e) {
		                videoList = videoList + '<tr style="border-bottom:2px solid #fff;"><td style="padding-bottom: 2px;"><div class=""><a href="https://www.youtube.com/watch?v='+e.snippet.resourceId.videoId+'" class="" data-lity><div class="play"> </div><span class=""><img width="140px" alt="'+e.snippet.title +'" src="'+e.snippet.thumbnails.default.url+'" ></span></a></div></td><td style="padding-left:10px; padding-top: 5px;" vAlign="top"><span class="title">'+e.snippet.title+'</span><br>'+'</td></tr><tr class="spacer"></tr>';
		            });
		            $("#hyv-global-related").html(videoList);
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

	vsource.pages['more'] = {
		url: '/views/more.php'
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

	

	vsource.pages['sales_contacts'] = {
		url: '/views/sales_contacts.php',

		onLoad: function(){

			
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

					vsource.gotoUserHome();

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
		$.mobile.changePage( "#register_validation");
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
		var formdata = $('#forgotpasswordform').serialize();

		$.post(vsource.apiUrl + '/resetpassword.php', formdata, function(data){
		
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
   
