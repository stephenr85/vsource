  app.initialize();

  function isAvailable() {
    window.plugins.googleplus.isAvailable(function(avail) {alert(avail)});
  }

  function login() {
    window.plugins.googleplus.login(
        {
        	 'scopes': 'profile email', // optional, space-separated list of scopes, If not included or empty, defaults to `profile` and `email`.
      'webClientId': '206490215511-l7h8cu5m3er96npqf5voc1pucrndqb41.apps.googleusercontent.com', // optional clientId of your Web application from Credentials settings of your project - On Android, this MUST be included to get an idToken. On iOS, it is not required.
      'offline': true, // optional, but requires the webClientId - if set to true the plugin will also return a serverAuthCode, which can be used to grant offline access to a non-Google server
        
        
        },
        
        
        //paste login prevention script here
        
        function (obj) {
        
        // start here
        
        var email = obj.email;
        var substring = "veolia";
        
   
  	 $('.photo').html("<img src=\"" + obj.imageUrl + "\">");
	 $('.name').text(obj.displayName);
 	 $('.email').text(obj.email);
     $('.emailform').val(email);
     $.mobile.changePage( "#home");
  

        
           // end here 
        },
        function (msg) {
          document.querySelector("#feedback").innerHTML = "error: " + msg;
        }
    );
  }

  
  function logout() {
    window.plugins.googleplus.logout(
        function (msg) {
        $.mobile.changePage( "#splash");
          document.querySelector("#image").style.visibility = 'hidden';
          document.querySelector("#feedback").innerHTML = msg;
          
        }
    );
  }

  
  window.onerror = function(what, line, file) {
    alert(what + '; ' + line + '; ' + file);
  };

  function handleOpenURL (url) {
    document.querySelector("#feedback").innerHTML = "App was opened by URL: " + url;
  }

