
<?php 

  $slug = $_REQUEST['slug'];
  $source = 'lumsites';
  $lang = vsource()->getLanguage();
  
  $newsFeed = new Vsource\NewsFeed();
  $newsFeed->debug(FALSE);

  //$newsFeed->loadLumSitesNews();
  $item = $newsFeed->loadLumSitesNewsItem($slug);

?>

<div data-role="popup" id="<?php echo $slug ?>">
<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo t('Close') ?></a>
<div class="ui-content">
<?php
    
    $title = $item['title'];
    if(isset($item['title'][$lang])){
      $title = $item['title'][$lang];
    }else{
      $title = $item['title']['en'];
    }
    echo '<h1 class="article-title">'.$title.'</h1>';
    echo '<p class="article-date">'.date('F j, Y', strtotime($item['publicationDate'])).'</p>';
    //print_r($item);
    foreach($item['template']['components'] as $component){
      foreach($component['cells'] as $cell){
        foreach($cell['components'] as $cellComponent){

          $content = $cellComponent['properties']['content'];
          if($content){
            if(isset($content[$lang])){
              $content = $content[$lang];
            }else{
              $content = $content['en'];
            }
          }

          //print_r($cellComponent);
          if($cellComponent['widgetType'] == 'intro'){
            echo '<h4 class="article-intro">'.$content.'</h4>'; 
          }
          if($cellComponent['widgetType'] == 'html'){
            echo '<div class="article-html">'.$content.'</div>';
          }
        }
      }
    }
  //

  //print_r($item);

?>
</div>
</div>