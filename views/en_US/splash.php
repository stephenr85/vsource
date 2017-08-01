<div data-role="page" class="splashbg" id="splash" data-transition="slide">
	<div class="text-center logo"><img src="images/logo_white.png" alt="logo" width="125px"/></div>
		<h2 class="text-center whitetext">VSOURCE</h2>
		<p class="text-center whitetext">
		<?php echo t('login_intro') ?>
		</p>
	<div class="login">
		<form id="signinform" method="post" class="text-left">
			<div class="login-form-main-message"></div>
			<div class="main-login-form">
				<div class="login-group">
					<div class="form-group">
						<label for="email" class="sr-only"><?php echo t('Your Email') ?></label>
						<input type="email" class="form-control border" id="email" name="email" placeholder="<?php echo strtolower(t('Your Email')) ?>">
					</div>
					<div class="form-group">
						<label for="password" class="sr-only"><?php echo t('Password') ?></label>
						<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo strtolower(t('Password')) ?>">
					</div>
				</div>
			</div>	
				<input type="hidden" name="signin" value="1">
	
			
		<div class="buttoncontainer">	
		<button type="submit" class="home-login-button" id="signinbutton"><?php echo t('Sign In') ?></button>
		</div>
		</form>
 	</div>
		<div class="text-center">
			<div class="etc-login-form">
				<p class="text-center whitetext"> <a href="#register" data-transition="slide"><?php echo t('Create an account') ?></a> &nbsp;&nbsp;
				<a href="#forgotpassword"><?php echo t('Forgot your password?') ?></a>
				</p>
			</div>
		</div>
</div>