


<div id="register" data-role="page">
<div data-role="header" class="headerbg">
	<a href="#splash" data-transition="slide" data-rel="back"><i class="fa fa-chevron-left fa-2x white"></i></a>
		<h1>Create an Account</h1>
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
						<label for="fname" class="sr-only">First Name</label>
						<input type="text" class="form-control" id="fname" name="fname" placeholder="first name">
					</div>
					<div class="form-group">
						<label for="lname" class="sr-only">Last Name</label>
						<input type="text" class="form-control" id="lname" name="lname" placeholder="last name">
					</div>
					
					<div class="form-group">
						<label for="email" class="sr-only">Email address</label>
						<input type="email" class="form-control" id="email" name="email" placeholder="email">
					</div>
					<div class="form-group">
						<label for="password" class="sr-only">Password</label>
						<input type="password" class="form-control" id="passwordtwo" name="password" placeholder="password">
					</div>
					<div class="form-group">
						<label for="password_again" class="sr-only">Password Confirm</label>
						<input type="password" class="form-control" id="password_again" name="password_again" placeholder="confirm password">
					</div>
				</div>
				<input type="hidden" name="userreg" value="form1">
				
			</div>
			<div class="loadingsignup" class="text-center">
<img src="images/loading.gif" width="32px">
</div>
			<div class="buttoncontainer">	
			<button type="submit" class="login-button" id="signupbutton">Sign Up</button>
			</div>
		</form>
		</div>
	</div>
	<!-- end:Main Form -->
	</div><!-- /content -->
</div>
