<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div data-role="page" id="home">
<div data-role="header" class="headerbg">
	
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1><a href="#home"><img src="images/logo.png" alt="logo" width="" height="" align="top" /></a></h1>
		
	</div><!-- /header -->
<div role="main" class="ui-content">
<div class="row">
<div class="col-xs-6 menubutton text-center">
<a href="#news" data-transition="slideup"><img src="images/news.png" class="home-icon" width="60%">News</a>
</div>	
<div class="col-xs-6 menubutton text-center"><a href="#videos" data-transition="slidedown">
<img src="images/video.png" class="home-icon" width="60%"> Videos</a>
</div>	
</div>	<!-- end row -->
<div class="row">
<div class="col-xs-6 menubutton text-center">
<a href="#ideas" data-transition="slidedown"><img src="images/share.png" width="70%" class="home-icon" data-transition="fade"> Share your idea</a>
</div>	
<div class="col-xs-6 menubutton text-center">	<a href="#offices" data-transition="slidedown"><img src="images/offices.png"  width="40%" class="home-icon">Offices</a>
</div>	
</div> <!-- end row -->
<div class="col-xs-6 menubutton text-center"><a href="#tools" data-transition="slidedown"><img src="images/tools.png" class="home-icon" width="70%">
Tools</a>	
	</div>
<div class="col-xs-6 menubutton text-center"><a href="#join" data-transition="slidedown"><img src="images/join.png" class="home-icon" width="50%">
Join</a>	
	</div>		
	</div><!-- /content -->
</div>

<?php vsource()->endView(); ?>