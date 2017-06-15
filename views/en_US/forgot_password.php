


<div data-role="page" id="forgotpassword" data-transition="slide">

	<div data-role="header" class="headerbg">
		<a href="#makeaccount" data-transition="slide" data-rel="back"><i class="fa fa-chevron-left fa-2x white"></i></a>
			<h1><?php echo t('Forgot your Password') ?></h1>
	</div><!-- /header -->
		
	
	<div role="main">
		<div class="main-content">
		
		<form name="forgotpassword" id="forgotpasswordform" method="post">
		<p class="text-center">
		<br><br><br>
		<?php echo t('Enter your email address.') ?> <br>
		
		</p>
		<div class="main-login-form">
			<input type="email" id="forgotemail" name="email" class="form-control whitebigtext" placeholder="Email Address"></input>
			</div>
			<input type="hidden" name="passwordreset" value="1"/>
			<div class="buttoncontainer">
			<button class="login-button" id="newpassword" name="go"><?php echo t('Next') ?></button> 
			</div>
		
		</form>        
		
     </div> <!-- end main-content -->
		</div><!-- /content -->
</div>

