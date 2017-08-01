


<div id="news" data-role="page">

<div data-role="header" data-position="fixed" class="headerbg">

	<a href="#leftnav"><i class="fa fa-bars fa-4x" aria-hidden="true"></i></a>
<h1>
	  <a href="#news" id="newsclick" aria-controls="news"><?php echo t('News') ?></a>
    <span class="slashtwo">/</span>
    <a href="#news_twitter" id="twitterlink" aria-controls="twitter"><?php echo t('Twitter') ?></a>
  </h1>
				<a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>
<div class="graybackground minus">
	<div class="searchbox twitterbox">
		 <form id="newssearch">
    <input data-type="search" placeholder="Search" class="form-control" id="newsfeedsearch">
</form></div>	
					</div>
	</div><!-- /header -->
<br><br>
	<div role="main" class="ui-content">
	<div class="row">
	<div class="col-xs-12">
  <!-- Tab panes -->
    
  <?php
        $newsFeed = new Vsource\NewsFeed();
        
        $newsFeed->load();

        //print_r($newsFeed->getFeedItems());
  ?>
      <div class="newsfeed-content" data-filter="true" data-input="#newsfeedsearch">
       <?php foreach($newsFeed->getFeedItems() as $feedItem){ 
          $itemImage = $feedItem['image'];
          if(!$itemImage){
            $itemImage = '/images/news-veolia-thumb.jpg';
          }
        ?>

          <div class="newsfeed-entry">
            <div class="newsfeed-itemimage" style="background-image:url(<?php echo $itemImage ?>);">
              <a href="<?php echo $feedItem['url'] ?>" target="_blank"> 
                <!--<img src="<?php echo $itemImage ?>" alt="<?php echo htmlspecialchars($feedItem['title']) ?>"  width="150.00" height="72.02380952381">-->
              </a>
            </div>
            <p class="newsfeed-itemfeedtitle"><a href="<?php echo $feedItem['feedUrl'] ?>" target="_blank"><?php echo $feedItem['feedName'] ?></a></p>
            <p class="newsfeed-itemdate"><?php echo date('F j, Y', $feedItem['date']) ?></p>
            <p class="newsfeed-itemtitle"><a href="<?php echo $feedItem['url'] ?>" target="_blank"><?php echo $feedItem['title'] ?></a></p>
            <div class="newsfeed-clear"></div>
          </div>

        <?php } ?>

        </div>

	 </div>
	</div><!-- /content -->
</div>
