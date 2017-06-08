(function(){

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','gaU');

var gaAccounts = {
		'default': 'UA-100256730-2',
		'android': 'UA-100256730-2',
		'ios': 'UA-100256730-1'		
	},
	isDevice = (typeof window.device !== 'undefined'),	
	platform = !isDevice ? 'default' : window.device.platform.toLowerCase(),
	options = !isDevice ? 'auto' : {
		'storage': 'none',
		'clientId': window.device.uuid
	};

gaU('create', gaAccounts[platform] || gaAccounts['default'], options);

if(window.location.protocol !== 'http:'){
	gaU('set', 'checkProtocolTask', null);
	//gaU('set', 'anonymizeIp', true);
	//gaU('forceSSL', true);
}

gaU('send', 'pageview');
})();
