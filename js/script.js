
(function($){
	
	vsource = window.vsource = {};
	vsource.hasDeviceReady = typeof window.cordova !== 'undefined';
	vsource.locale = 'en-US';
	
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

	vsource.getUserLocale = function(){

		//Look for query string override first
		if(vsource.queryString('locale')){
			vsource.locale = vsource.queryString('locale');
			return;
		}
		return vsource.locale;

		//Look at device setting
		if(typeof navigator.globalization !== 'undefined'){
			navigator.globalization.getLocaleName(
		        function (locale) {
		        	if(locale.value.indexOf('fr-') > -1){
		        		vsource.locale = 'fr-CA';
		        	}else{
		        		//vsource.locale = 'en-US';
		        	}
		        },
		        function () {
		        	vsource.log('Error getting locale\n');
		        }
		    );
		}
	};

	vsource.queryString = function (name, url) {
	    if (!url) url = window.location.href;
	    name = name.replace(/[\[\]]/g, "\\$&");
	    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

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
				/*
				console.log(this);
				if(!this.data) this.data = 'auth=' + vsource.authToken + '&locale=' + vsource.locale;
				else if (typeof(this.data) === 'string') this.data += '&auth=' + encodeURIComponent(vsource.authToken)+ '&locale=' + vsource.locale;
		        //xhr.setRequestHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
		        //xhr.setRequestHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
		        //xhr.setRequestHeader('Authorization','Token token="' + vsource.authToken + '"');
		        console.log('data:' + this.data);
		        if(this.type == 'GET'){
		        	this.url = this.url.replace(/((\.\/[a-z][0-9])*\?+[=%&a-z0-9]*)&?token=[a-z0-9]*&?([=%&a-z0-9]*)/gi, "$1$3");
		        }*/

		        if(!this.data) this.data = 'auth=' + encodeURIComponent(vsource.authToken) + '&locale=' + vsource.locale;
				else if (typeof(this.data) === 'string') this.data += '&auth=' + encodeURIComponent(vsource.authToken)+ '&locale=' + vsource.locale;

		        xhr.setRequestHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
		        xhr.setRequestHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
		        xhr.setRequestHeader('Authorization','Token token="' + vsource.authToken + '"');
		    },
		    data: {
		    	locale: vsource.locale,
		    	auth: encodeURIComponent(vsource.authToken)
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

		vsource.setAuthToken(vsource.getAuthToken());

		$.mobile.page.prototype.options.domCache = true;
	    $.mobile.allowCrossDomainPages = true;
	    $.support.cors = true;   

	    var $activePage = $(':mobile-pagecontainer').pagecontainer('getActivePage');

	    $.mobile.loading('show');
	    $('#splash').remove();
	    //Replace splash with server version
	    $activePage.remove();
	    $.get(vsource.apiUrl + '/view.php/splash').then(function(html){
			$(':mobile-pagecontainer').append(html);
			vsource.pages['splash'].onLoad();
			$.mobile.loading('hide');
		});

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
		$.get(vsource.apiUrl + '/view.php/panels/leftnav').then(function(html){
			$(html).appendTo('body').panel();
		});

		vsource.loadGoogleClient();
		vsource.loadYouTubeApi();
		setInterval(function(){
			//vsource.loadGoogleClient();
			//vsource.loadYouTubeApi();
		}, 1000 * 60 * 5);

		vsource.domReady();
	};

	vsource.loadGoogleClient = function(){
		vsource.whenGoogleClient = $.Deferred();

		gapi.load('client', function(){
			vsource.log(['Google client ready', arguments]);
			gapi.client.setApiKey(vsource.gapiKey);
			vsource.whenGoogleClient.resolve();
		});

		return vsource.whenGoogleClient;
	};

	vsource.loadYouTubeApi = function(){
		vsource.whenYouTube = $.Deferred();

		vsource.whenGoogleClient.done(function(){

			gapi.client.load('youtube', 'v3', function(){
				console.log(['YouTube API loaded', arguments]);
				vsource.whenYouTube.resolve();
			});		
		});	

		return vsource.whenYouTube;	
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
	
	if(vsource.hasDeviceReady){
		document.addEventListener("deviceready", onDeviceReady, false);

	    // PhoneGap is loaded and it is now safe to make calls PhoneGap methods
	    //
	    function onDeviceReady() {
	        // Now safe to use the PhoneGap API
	        console.log('deviceready');
	        vsource.getUserLocale();
	        setTimeout(vsource.init, 0);
	    }
	}else{
		$(document).ready(function() {
			console.log('docready');
			vsource.getUserLocale();
			setTimeout(vsource.init, 0);
		});
	}


	$(document).on("mobileinit", function() {
	    // Make your jQuery Mobile framework configuration changes here!
	   //doesn't seem to be firing
	   //alert('mobileinit');
	});


	


	$(document).on('pagebeforeshow', function(evt, info){
		vsource.log('pagebeforeshow', arguments);

		var pageName = info.toPage.attr('id'),
			pageObj = vsource.pages[pageName];

		if(pageObj){
			gaU('send', 'pageview', window.location.protocol === 'file:' ? location.hash : window.location);
			pageObj.$el = $(evt.target);
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
		url: '/view.php/splash',

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
		url: '/view.php/register'
	};

	vsource.pages['register_validation'] = {
		url: '/view.php/register_validation'
	};

	vsource.pages['forgotpassword'] = {
		url: '/view.php/forgot_password',

		onLoad: function(){
			$.get(vsource.apiUrl + '/view.php/password_tempcode').then(function(response){
				$(response).appendTo('body');
			});
			$.get(vsource.apiUrl + '/view.php/password_change').then(function(response){
				$(response).appendTo('body');
			});
		}
	};

	vsource.pages['guesthome'] = {
		url: '/view.php/guests/home',
		authGroup: 2,
		isHome: true
	};

	vsource.pages['guestservices'] = {
		url: '/view.php/guests/services'
	};

	vsource.pages['gueststats'] = {
		url: '/view.php/guests/stats'
	};



	vsource.pages['home'] = {
		url: '/view.php/home',
		authGroup: 1,
		isHome: true,
		onShow: function(){
			//alert('test');
		}
	};

	vsource.pages['news'] = {
		url: '/view.php/news',

		onLoad: function(){
			/*
			$('#twitterlink').click(function(){
				$('.twitterbox').hide();
			});


			$('#newsclick').click(function(){
				$('.twitterbox').show();
			});
			*/
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

		    $('#news a[href*="/view.php/news_item"]').on('click', function(evt){
		    	evt.preventDefault();

		    	$.mobile.loading('show');

		    	$.get(this.href, function(articlehtml){
		    		$.mobile.loading('hide');
		    		//console.log(arguments);
		    		var $article = $(articlehtml);
		    		//$('body').append($article);
		    		//$article.popup();
		    		$article.filter('[id]').popup().popup('open',  {
		    			positionTo: evt.target,
		    			y: 'top'
		    		});
		    	});
		    	console.log(this);
		    	return false;
		    });

		}
	};

	vsource.pages['news_twitter'] = {
		url: '/view.php/news_twitter',

		onLoad: function(){			

		    $('#twitbox').on('click', 'a[href^="http"]', function(evt){
		    	evt.preventDefault();

		    	window.open(this.href, $(this).attr('target') || '_blank');
		    });


		    $('#twitbox').on('click', 'a[href^="http"]', function(evt){
		    	evt.preventDefault();

		    	window.open(this.href, $(this).attr('target') || '_blank');
		    });
		    
		}
	};

	vsource.pages['videos'] = {
		url: '/view.php/videos',

		onLoad: function(){
			var I = this;


			I.loadPlayer();
			
		},

		onShow: function(){
			var I = this;	

			setTimeout(function(){
				I.loadVideos();
			}, 1)
			
			
					
		},

		loadPlayer: function(){
			if($('#youtube_modal').length < 1){
				$.get(vsource.apiUrl + '/view.php/youtube_modal').then(function(response){
					$(response).appendTo('body');
				});
			}
		},

		loadVideos: function(){
			var I = this;			

			if(!I.aboutVideosLoaded){
				$('.loading').show();
				return vsource.whenYouTube.done(function(){
					var request = gapi.client.youtube.playlistItems.list({
						playlistId: I.$el.find("#video-table").attr('data-playlist'),
						//playlistId: 'PLH-nnUXtAYzpIJxKuLmldQ8EW0Kyu6dSL',
						maxResults: 25,
					    relevanceLanguage: 'en',
					    type: 'video',
					    part: 'snippet'
					});

					request.execute(function(data){
						vsource.log(data);
						$('.loading').hide();
						if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
			            if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
			            var items = data.items,
			            	videoList = "";
			            $("#pageTokenNext").val(data.nextPageToken);
			            $("#pageTokenPrev").val(data.prevPageToken);

			            $.each(items, function(index,e) {
			            	if(!e.snippet.thumbnails) {
			            		console.log('no thumbnails', e);
			            		return;
			            	}
			                videoList = videoList + '<tr style="border-bottom:2px solid #fff;"><td style="padding-bottom: 2px;"><div class=""><a href="https://www.youtube.com/watch?v='+e.snippet.resourceId.videoId+'" class="" data-lity><div class="play"> </div><span class=""><img width="140px" alt="'+e.snippet.title +'" src="'+e.snippet.thumbnails.default.url+'" ></span></a></div></td><td style="padding-left:10px; padding-top: 5px;" vAlign="top"><span class="title">'+e.snippet.title+'</span><br>'+'</td></tr><tr class="spacer"></tr>';
			            });
			            I.$el.find("#video-table").html(videoList);
			            I.aboutVideosLoaded = true;
					});

				});
			}

		}
	};


	vsource.pages['videos_services'] = $.extend({}, vsource.pages['videos'], {
		url: '/view.php/videos_services',

		/*
		loadVideos: function(){
			var I = this;

			if(!I.serviceVideosLoaded){
				$('.loading').show();
				return vsource.whenYouTube.done(function(){

					var request = gapi.client.youtube.playlistItems.list({
					    playlistId: I.$el.find("#video-table").attr('data-playlist'),
					    //playlistId: 'PLH-nnUXtAYzp7UDj29arg6RlyLDuJ-cEa',
					    maxResults: 25,
					    relevanceLanguage: 'en',
					    type: 'video',
					    part: 'snippet'
					});

					request.execute(function(data){
						vsource.log(data);
						$('.loading').hide();
						if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
			            if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
			            var items = data.items,
			            	videoList = "";
			            $("#pageTokenNext").val(data.nextPageToken);
			            $("#pageTokenPrev").val(data.prevPageToken);

			            $.each(items, function(index,e) {
			                videoList = videoList + '<tr style="border-bottom:2px solid #fff;"><td style="padding-bottom: 2px;"><div class=""><a href="https://www.youtube.com/watch?v='+e.snippet.resourceId.videoId+'" class="" data-lity><div class="play"> </div><span class=""><img width="140px" alt="'+e.snippet.title +'" src="'+e.snippet.thumbnails.default.url+'" ></span></a></div></td><td style="padding-left:10px; padding-top: 5px;" vAlign="top"><span class="title">'+e.snippet.title+'</span><br>'+'</td></tr><tr class="spacer"></tr>';
			            });
			            I.$el.find("#video-table").html(videoList);
			            I.serviceVideosLoaded = true;
					});

				});
			}
		}*/
	});

	vsource.pages['ideas'] = {
		url: '/view.php/ideas',

		onLoad: function(){
			$.get(vsource.apiUrl + '/view.php/idea_modal').then(function(response){
				$(response).appendTo('body');
			});
		}
	};

	vsource.pages['offices'] = {
		url: '/view.php/offices',

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
					listTemplatePath: vsource.apiUrl + '/view.php/offices_location_description_template',
					KMLinfowindowTemplatePath: vsource.apiUrl + '/js/plugins/storeLocator/templates/kml-infowindow-description.html',
					KMLlistTemplatePath: vsource.apiUrl + '/js/plugins/storeLocator/templates/kml-location-list-description.html',
					dataLocation: vsource.apiUrl + '/locations.php'
					
				});
				I.isLocatorInitialized = true;
		 	}	 	
		}
	};

	vsource.pages['more'] = {
		url: '/view.php/more'
	};

	vsource.pages['ask'] = {
		url: '/view.php/ask'
	};

	vsource.pages['join'] = {
		url: '/view.php/join'
	};

	vsource.pages['about'] = {
		url: '/view.php/about',

		onLoad: function(){

			$.get(vsource.apiUrl + '/view.php/feedback_modal').then(function(response){
				$(response).appendTo('body');
			});

			$('#providefeedback').click(function(){
				$('.feedbackform').show();
				$('#feedbackarea').focus();
			});
		}
	};

	

	vsource.pages['sales_contacts'] = {
		url: '/view.php/sales_contacts',

		onLoad: function(){

			
		}
	};

	

	

	



	// User sign in	
	$(document).on('submit','#signinform', function(e){
		console.log(arguments);
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

			$('#idea_modal').modal('show');
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
			$('#feedback_modal').modal('show');
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

	$(document).on('click', '#sweetbutton', function(){
		$('.loading').hide();
		$('#feedbackform').get(0).reset();
	});
		
	$(document).on('click', '.close', function(){
		$('.loading').hide();
		$('#feedbackform').get(0).reset();
	});
				

	//Feedback confirmation buttons	
	$(document).on('click', '#learnbutton', function(){

		$('.loading').hide();
		$('#shareidea').get(0).reset();
		$.mobile.changePage( "#join");		

	});	
		

	//Close button 	
	$(document).on('click', '#closebutton', function(){
		$('.loading').hide();
		$('#shareidea').get(0).reset();
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
   
