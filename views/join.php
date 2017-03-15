<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div id="join" data-role="page">
	<div data-role="header" data-position="fixed" class="headerbg">
	
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1>Join</h1>
	<a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>
</div><!-- /header -->

	<div role="main" class="ui-content">
		<div class="row">
	
	<div class="col-xs-12"><strong>Be part of Veolia's push for growth.<br>
		HOW YOU CAN HELP:</strong>
		
		<div data-role="collapsibleset" data-inset="false">
    <div data-role="collapsible">
        <h2 class="redbutton">JOIN THE SOCIAL TEAM</h2>
        
	<p><strong>Veolia will occasionally ask for your help pushing news.</strong></p>
	<br>
	<div class="text-center"><a href="mailto:matt.demo@veolia.com?subject=Join the Social Team&body=I would like to help Veolia share news. Can you tell me how the social team works?" id="jointeam" class="ui-btn ui-btn-inline redbg">Join the Team</a> 
	</div>

	</div>
<?php /*
	<div data-role="collapsible">
        <h2 class="greenbutton">2. JOIN A COMPANY GROUP</h2>
        <p class="text-center"><strong>We currently offer the following groups: Safety, Sustainability, Women in Leadership, Diversity &amp; Veterans.</strong></p>  
<div class="text-center">
<a href="mailto:matt.demo@veolia.com?subject=I’m interested in joining an affinity group&body=I'm interested in joining the INSERT GROUP NAME group. Can you tell me more about the process?" id="jointeam" class="ui-btn ui-btn-inline greenbg">Join a Group</a> 
</div>
 </div>
*/ ?>
<div data-role="collapsible">
        <h2 class="bluebutton">SUGGEST A GROUP</h2>
        <div class="text-center"><strong>Have an idea for a company group? Let us know.  </strong>
</div>
<br>

<div class="text-center">
<a href="mailto:matt.demo@veolia.com?subject=I have a suggestion for a Veolia group&body=I’m interested in suggesting a Veolia group. My suggestion is below – can you let me know more details about how this works?" id="jointeam" class="ui-btn ui-btn-inline bluebg">Suggest Group</a> 
</div>

</div>
</div>
		
	</div>
	
	</div>
	
	</div><!-- /content -->

</div>
<?php vsource()->endView(); ?>
	