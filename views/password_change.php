<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>

<div id="changepassword" data-role="page">
<div data-role="header" class="headerbg">
	<a href="#makeaccount" data-transition="slide" data-rel="back"><i class="fa fa-chevron-left fa-2x white"></i></a>
		<h1>Change Passcode</h1>
</div><!-- /header -->	
	<div role="main">
		<div class="main-content">
		
		<form name="changepasswordform" id="changepasswordform" method="post">
		<p class="text-center">
		<br><br><br>
		Please enter your new password. <br>
		
		</p>
		<div class="main-login-form">
			<input type="password" name="password" class="form-control whitebigtext" placeholder="Enter New Password"></input>
			</div>
			<div class="validatesignup" class="text-center">
<img src="images/loading.gif" width="32px">
</div>
<input type="hidden" name="emailcode" class="emailcode">
<input type="hidden" name="tempcode" class="tempcode">
<input type="hidden" name="newpswd" value="1"/>
			<div class="buttoncontainer">
			<button class="login-button" id="savenewpasscodebutton" name="go">Next</button> 
			</div>
		
		</form>        
		
     </div> <!-- end main-content -->
		</div><!-- /content -->
</div>
<?php vsource()->endView(); ?>