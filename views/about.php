<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>
<div id="about" data-role="page">
	<div data-role="header" data-position="fixed" class="headerbg">
	
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1>About</h1>
				<a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>

	</div><!-- /header -->

	<div role="main" class="ui-content">
	<div class="aboutbg">
	<div class="row">
	
	<div class="col-xs-12">
	<strong>The VSOURCE app was built by the North American MarCom team. </strong>
<br><br>
We'd love to hear your questions or ideas about the app, which you can submit below.<br><br> 



<button class="redbg btn btn-lg" id="providefeedback">PROVIDE FEEDBACK</button>
<div class="loading" class="text-center">
<img src="images/loading.gif" width="64px">
</div>
<div class="feedbackform">
<form name="feedback" id="feedbackform">
<textarea class="form-control" name="feedbackform" id="feedbackarea" rows="10">


</textarea> 
<input type="hidden" name="feedbackemail" id="feedbackemail">
<div class="text-center">
<button type="submit" class="btn btn-lg redbg" id="feedbacksubmit">SUBMIT</button>


</div>
</form>
</div>
	</div>
		
	</div>
	</div>
	</div><!-- /content -->

	<div data-role="footer" class="aboutfooter" >
		
	</div>
</div>

<?php vsource()->endView(); ?>