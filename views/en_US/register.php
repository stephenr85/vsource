


<div id="register" data-role="page">
<div data-role="header" class="headerbg">
	<a href="#splash" data-transition="slide" data-rel="back"><i class="fa fa-chevron-left fa-2x white"></i></a>
		<h1><?php echo t('Create An Account') ?></h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
	<!-- Main Form -->
		<div class="text-center" style="padding:50px 0">
	<div class="login-form-1">
		<form class="text-left" name="userreg" method="post" id="userreg">
			<div class="login-form-main-message"></div>
			<div class="main-login-form">
				<div class="login-group">
				<div class="form-group">
						<label for="fname" class="sr-only"><?php echo t('First Name') ?></label>
						<input type="text" class="form-control" id="fname" name="fname" placeholder="<?php echo strtolower(t('First Name')) ?>">
					</div>
					<div class="form-group">
						<label for="lname" class="sr-only"><?php echo t('Last Name') ?></label>
						<input type="text" class="form-control" id="lname" name="lname" placeholder="<?php echo strtolower(t('Last Name')) ?>">
					</div>
					
					<div class="form-group">
						<label for="email" class="sr-only"><?php echo t('Email address') ?></label>
						<input type="email" class="form-control" id="email" name="email" placeholder="<?php echo strtolower(t('Email')) ?>">
					</div>
					<div class="form-group">
						<label for="password" class="sr-only"><?php echo t('Password') ?></label>
						<input type="password" class="form-control" id="passwordtwo" name="password" placeholder="<?php echo strtolower(t('Password')) ?>">
					</div>
					<div class="form-group">
						<label for="password_again" class="sr-only"><?php echo t('Confirm Password') ?></label>
						<input type="password" class="form-control" id="password_again" name="password_again" placeholder="<?php echo strtolower(t('Confirm Password')) ?>">
					</div>
				</div>
				<input type="hidden" name="userreg" value="form1">
				
			</div>
			<div class="loadingsignup" class="text-center">
<img src="images/loading.gif" width="32px">
</div>
			<div class="buttoncontainer">	
			<button type="submit" class="login-button" id="signupbutton"><?php echo t('Sign Up') ?></button>
			</div>
		</form>
		</div>
	</div>
	<!-- end:Main Form -->
	</div><!-- /content -->
</div>
