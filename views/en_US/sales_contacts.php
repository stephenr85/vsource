


<div id="sales_contacts" data-role="page">
  <div data-role="header" data-position="fixed" class="headerbg">
  
  <a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
    <h1><?php echo t('Sales Contacts') ?></h1>
  <a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>
</div><!-- /header -->

  <div role="main" class="ui-content">
    <div class="row">
  
  <div class="col-xs-12">
  <!--
    <p>Looking for Sales Contacts?<br>
You can find them here.</p>-->
    

  <?php 
    $feed = new \Vsource\SalesContactsFeed();
    //$feedItems = $feed->loadOriginalData();
    $feedData = $feed->loadData();
    //print_r($feedData);die;

    $print_contact = function($contact) {
      $html = '<p>';
      $html .= '<strong>' . $contact->name . '</strong>';
      if($contact->email) $html .= '<br><a href="mailto:'.$contact->email.'">'.$contact->email.'</a>';
      if($contact->phone) $html .= '<br><a href="tel:'.$contact->phone.'">'.$contact->phone.'</a>';
      if($contact->description) $html .= '<br>'.$contact->description;
      $html .= '</p>';

      return $html;
    };

    $print_category = function($cat) use (&$print_category, &$print_contact){
      $html = '<div data-role="collapsible">';
      $html .= '<h2 class="redbutton" style="background-color: '.$cat->color.' !important;">'.$cat->name.'</h2>';

      $html .= '<p>'.$cat->description.'</p>';

      foreach($cat->contacts as $contact){
        $html .= $print_contact($contact);
      }
      foreach($cat->children as $child){
        $html .= $print_category($child);
      }

      $html .= '</div>';
      return $html;
    };

    foreach($feedData as $cat){
      echo $print_category($cat);
    }

  ?>

  
  </div>



  
  </div><!-- /content -->

</div>

  