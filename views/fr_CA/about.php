

<div id="about" data-role="page">
	<div data-role="header" data-position="fixed" class="headerbg">
	
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1><?php echo t('About') ?></h1>
				<a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>

	</div><!-- /header -->

	<div role="main" class="ui-content">
	<div class="aboutbg">
	<div class="row">
	
	<div class="col-xs-12">

	<p>
	<strong>L’application VSOURCE a été développée par l’équipe MarCom nord-américaine. </strong>
	</p>
	<p>
Nous serions ravis de recevoir vos questions ou vos idées concernant l’application, que vous pouvez nous soumettre ci-dessous. </p>

<button class="redbg btn btn-lg" id="providefeedback"><?php echo strtoupper(t('Provide Feedback')) ?></button>
<div class="loading" class="text-center">
<img src="images/loading.gif" width="64px">
</div>
<div class="feedbackform">
<form name="feedback" id="feedbackform">
<textarea class="form-control" name="feedbackform" id="feedbackarea" rows="10">


</textarea> 
<input type="hidden" name="feedbackemail" id="feedbackemail">
<div class="text-center">
<button type="submit" class="btn btn-lg redbg" id="feedbacksubmit"><?php echo strtoupper(t('Submit')) ?></button>


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

