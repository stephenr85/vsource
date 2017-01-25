<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div id="offices" data-role="page">
	<div data-role="header" data-position="fixed" class="headerbg">

	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1>Offices</h1>
				<a href="#home"><img src="images/logo.png" class="smalllogo" alt="logo" width="" height="" /></a>
<div class="graybackground minus">
<div class="searchbox">
<div class="bh-sl-form-container">
						<form id="bh-sl-user-location" class="form-inline" data-ajax="false" method="post" role="form">
							<div class="form-input form-group">
								
								<input type="text" class="ui-input-search form-control" id="bh-sl-address" name="bh-sl-address" placeholder="enter address or zip code" /></div>	</div>
							</div>
</form>
					</div>
	</div><!-- /header -->

	<div role="main" class="ui-content offices">
	
	<div class="suggestions"><strong>Suggestions by Location</strong></div>
	<div class="suggestionsshow"></div>						
							
						
					
					
					<div id="bh-sl-map-container" class="bh-sl-map-container">
				<div class="row">
					<div id="map-results-container" class="container">
						<div id="bh-sl-map" class="bh-sl-map col-md-9"></div>
						<div class="bh-sl-loc-list col-md-3">
							<ul></ul>
						</div>
					</div>
				</div>
      </div>


	</div><!-- /content -->
</div>
<?php vsource()->endView(); ?>