<?php require_once('../../_init.php'); ?>
<?php vsource()->startView(); ?>

<div data-role="panel" id="leftnav" data-position-fixed="true" data-position="left" data-display="overlay" data-theme="a">



<div class="profile">
<div class="photo"></div>
<div class="name">
</div>

<div class="email"></div>


</div>
      <ul>
  <li><a href="#home"> <i class="fa fa-home" aria-hidden="true"></i> Home</a></li>    
  <li><a href="#news"> <i class="fa fa-newspaper-o"></i> News</a></li>
   <li><a href="#videos"> <i class="fa fa-film"></i> Videos</a></li>
  <li><a href="#ideas"><img src="images/share.png" class="menu-icon" width="10%"> My Ideas</a></li>
  <li><a href="#offices"><img src="images/offices.png" class="menu-icon" width="10%"> Offices</a></li>
  <li><a href="#tools"><img src="images/tools.png" class="menu-icon" width="10%"> Tools</a></li>
  <li><a href="#bdcontacts"><i class="fa fa-handshake-o"></i> BD Contacts</a></li>
  
</ul>        
<hr/>
<ul class="second_nav">
<li><a href="#ask">Ask</a></li>
<li>
<a href="#join">Join</a></li>
<li><a href="#about">About</a></li>
</ul>
<hr/>
<ul class="second_nav">
<li><a href="#splash"> Logout </a></li>
        </ul>
           
 </div><!-- /leftnav -->


<?php vsource()->endView(); ?>