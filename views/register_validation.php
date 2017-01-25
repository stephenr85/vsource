<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div id="register_validation" data-role="page">
<div data-role="header" class="headerbg">
	<a href="#makeaccount" data-transition="slide" data-rel="back"><i class="fa fa-chevron-left fa-2x white"></i></a>
		<h1>Confirmation</h1>
</div><!-- /header -->
	
	
	<div role="main">
		<div class="main-content">
		
		<form name="validatecode" id="validatecode" method="post">
		<p class="text-center">
		<br><br><br>
		A validation code has been sent to your email. <br>
		
		</p>
		<div class="main-login-form">
			<input type="text" name="valcode" class="form-control whitebigtext" placeholder="Enter Code"></input>
			</div>
			<div class="validatesignup" class="text-center">
<img src="images/loading.gif" width="32px">
</div>
<input type="hidden" name="valpost" value="1"/>
			<div class="buttoncontainer">
			<button class="login-button" id="validationbutton" name="go">Validate</button> 
			</div>
		
		</form>        
		
     </div> <!-- end main-content -->
		</div><!-- /content -->
</div>
<?php vsource()->endView(); ?>