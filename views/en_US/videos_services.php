

<div id="videos_services" data-role="page">
<div data-role="header"  class="headerbgvideo" data-position="fixed">
	
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1><a href="#videos"  aria-controls="videos"><?php echo t('About Us') ?></a>
    <span class="slashtwo">/</span>
    <a href="#videos_services"  aria-controls="servicesvideos" ><?php echo t('Services') ?></a>
</h1>
   
	<a href="#home"><img src="images/logo.png" class="text-left" alt="logo" class="smalllogo" width="" height="" /></a>	
	<div class="graybackground minus">	
		<div class="searchbox">
		 <form id="videosearch">
    <input data-type="search"  placeholder="Search" class="form-control ui-filterable" id="videosearch">
</form></div>
	
	</div>
	</div><!-- /header -->

	<div role="main" class="ui-content">
	
		<div class="row">
		<div class="col-xs-12">

			<div>
		        <table id="video-table" class="hyv-video-list" data-filter="true" data-input="#videosearch" data-playlist="<?php echo t('youtube_services_playlist') ?>">
		        </table>
		    </div>
	   
	  	</div>
		</div>	
	
	</div><!-- /content -->
</div>
