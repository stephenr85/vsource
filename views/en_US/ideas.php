

<div id="ideas" data-role="page">
<div data-role="header" data-position="fixed" class="headerbg">
	<a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
		<h1><?php echo t('Share Your Idea') ?></h1>
	<a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>
</div><!-- /header -->

<div role="main" class="ui-content">
<div class="text-center">

<strong>If you have an idea to grow the <br>business, let us know. </strong><br>
Ideas are not anonymous.
<br><br>
<strong>We want your ideas for growth.</strong>

</div>
<br>

<div class="loading" class="text-center">
<img src="images/loading.gif" width="64px">
</div>

<form name="shareidea" id="shareidea" method="post">
  <textarea class="form-control textcontainer idea" name="idea" placeholder="<?php echo t('Name of your idea') ?>" rows="10" required></textarea>
  <textarea class="form-control textcontainer problem" name="problem"  placeholder="<?php echo t('Problem it solves') ?>" rows="10" required></textarea>
  <textarea class="form-control textcontainer solve" name="solve"  placeholder="<?php echo t('Your idea to solve it') ?>" rows="10" required></textarea>  

  <input type="hidden" name="shareemail" id="shareemail">
<div class="submitbutton">
		<button type="submit" id="shareideabutton" class="btn submitbutton redbutton btn-lg btn-block"><?php echo t('SUBMIT') ?></button>
		</div>
		
</form>	
	
</div><!-- /content -->
</div>
