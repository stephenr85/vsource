<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>
<div id="videos" data-role="page">
<div data-role="header"  class="headerbgvideo" data-position="fixed">
	
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1><a href="#videofeed"  aria-controls="videos" role="pill" data-toggle="pill">About Us</a>
    <span class="slashtwo">/</span>
    <a href="#globalfeed"  aria-controls="globalvideos" role="pill" data-toggle="pill">Services</a>
</h1>
   
	<a href="#home"><img src="images/logo.png" class="text-left" alt="logo" class="smalllogo" width="" height="" /></a>	
	<div class="graybackground minus">	
		<div class="searchbox">
		 <form id="videosearch">
    <input data-type="search"  placeholder="Search" class="form-control ui-filterable" id="videofeeding">
</form></div>
	
	</div>
	</div><!-- /header -->

	<div role="main" class="ui-content">
	
	<div class="row">
	<div class="col-xs-12">
	<div>
<!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="videofeed"> 

                            <ul id="hyv-watch-related" class="hyv-video-list" data-filter="true" data-input="#videofeeding">
                            </ul>
                  



    </div>
    <div role="tabpanel" class="tab-pane" id="globalfeed">
                        <ul id="hyv-global-related" class="hyv-video-list" data-filter="true" data-input="#videofeeding">
                            </ul>
                  
    </div>
  </div>

</div>
	</div>
	</div>
	
	
	</div><!-- /content -->
</div>
<?php vsource()->endView(); ?>