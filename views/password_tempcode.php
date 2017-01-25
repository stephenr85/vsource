<?php require_once('../_init.php'); ?>
<?php vsource()->startView(); ?>
<div id="entertemppassword" data-role="page">
<div data-role="header" class="headerbg">
	<a href="#makeaccount" data-transition="slide" data-rel="back"><i class="fa fa-chevron-left fa-2x white"></i></a>
		<h1>Enter Temporary Passcode</h1>
</div><!-- /header -->	
	<div role="main">
		<div class="main-content">
		
		<form name="newcode" id="newcode" method="post">
		<p class="text-center">
		<br><br><br>
		A temporary passcode was sent to your email. Please enter it below.<br>
		
		</p>
		<div class="main-login-form">
			<input type="text" name="newcode" class="form-control whitebigtext" placeholder="Enter Code"></input>
			</div>
			<div class="validatesignup" class="text-center">
<img src="images/loading.gif" width="32px">
</div>
<input type="hidden" name="emailcode" class="emailcode">
<input type="hidden" name="newpasscode" value="1"/>
			<div class="buttoncontainer">
			<button class="login-button" id="newpasscodebutton" name="go">Next</button> 
			</div>
		
		</form>        
		
     </div> <!-- end main-content -->
		</div><!-- /content -->
</div>
<?php vsource()->endView(); ?>