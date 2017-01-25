<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div id="ask" data-role="page">
	<div data-role="header" data-position="fixed" class="headerbg">
	
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1>Ask Question</h1>
	<a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>
</div><!-- /header -->

	<div role="main" class="ui-content">
	
	<div class="row">
	
	<div class="col-xs-12">
	<strong>Have a question about Veolia? </strong>
<br><br>
Ask us and we’ll do our best to answer. Questions are not anonymous.<br><br> 
<div class="text-center">
<a href="mailto:matt.demo@veolia.com?subject=I have a question about Veolia (submitted via VSOURCE)"  class="ui-btn ui-btn-inline redbg">ASK QUESTION</a> 
</div>
		
	</div>
	</div>
	</div><!-- /content -->

	<div data-role="footer" data-position="fixed">
		
	</div>
</div>
<?php vsource()->endView(); ?>