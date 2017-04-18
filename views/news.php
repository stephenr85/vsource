<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div id="news" data-role="page">

<div data-role="header" data-position="fixed" class="headerbg">

	<a href="#leftnav"><i class="fa fa-bars fa-4x" aria-hidden="true"></i></a>
<h1>
	<a href="#newsfeed" id="newsclick" aria-controls="news" role="pill" data-toggle="pill">News</a>
    <span class="slashtwo">/</span>
    <a href="#twitter" id="twitterlink" aria-controls="twitter" role="pill" data-toggle="pill">Twitter</a>
  </h1>
				<a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>
<div class="graybackground minus">
	<div class="searchbox twitterbox">
		 <form id="newssearch">
    <input data-type="search" placeholder="Search" class="form-control" id="newsfeeder">
</form></div>	
					</div>
	</div><!-- /header -->

	<div role="main" class="ui-content">
	<div class="row">
	<div class="col-xs-12">
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel"  class="tab-pane fade in active" id="newsfeed">
    <div class="rssfeed">
    
    	<div id="rssincl-box-container-1096476"></div>
    </div>

    	<?php 
    		if(false){
    			/*
	    		<script type="text/javascript" src="http://output91.rssinclude.com/output?type=js&amp;id=1096476&amp;hash=cd5ca1beade41004defb6fe09d33b89a"></script>
	    		*/
	    		$rssJS = vsource()->getUrlContent('http://output91.rssinclude.com/output?type=js&id=1096476&hash=cd5ca1beade41004defb6fe09d33b89a');

	    		$rssJS = trim(stripcslashes($rssJS));
	    		$rssJS = preg_replace('/^document.write\("/', '', $rssJS);
	    		$rssJS = preg_replace('/"\);$/', '', $rssJS);
	    		echo $rssJS;
    		}
    		


    	?>

    	<?php 
    	if(false){
    		@readfile('http://output85.rssinclude.com/output?type=php&id=1096476&hash=2ee63107f4dc74414b55fcebb7193ffd');
    	}
    	?>

       </div>
    <div role="tabpanel" class="tab-pane fade" id="twitter">
    
   <div id="twitbox">
    
    <a class="twitter-timeline" href="https://twitter.com/Veolia_NA">Tweets by Veolia_NA</a> <script src="http://platform.twitter.com/widgets.js" charset="utf-8"></script>

</div> 
       </div>
</div>
	</div>
	</div>
	</div><!-- /content -->
</div>
<?php vsource()->endView(); ?>