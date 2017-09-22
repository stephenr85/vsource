<div id="health_safety_week" data-role="page">
  <div data-role="header" data-position="fixed" class="headerbg">
  
  <a href="#leftnav"><i class="fa fa-bars" aria-hidden="true"></i></a>
    <h1><?php echo t('health_safety_week_title') ?></h1>
  <a href="#home"><img src="images/logo.png" alt="logo" width="" height="" /></a>
</div><!-- /header -->

  <div role="main" class="ui-content">
    <div class="row">
  
  <div class="col-xs-12">

<?php

$contentHelper = new \Vsource\LumSites\ContentHelper();
$data = $contentHelper->getPageDataBySlug('international-health-safety-week-2017');

echo '<!--';

//print_r($data);

echo '-->';

$dataDom = $contentHelper->buildTemplateDom($data['template']);

$dataDom->filter('#c63cf3b4-2c3a-48dc-9115-49eba999333d a')->each(function($a){
	$a->attr('target', '_blank');
});

$dataDom->filter('[style*="margin-left: 180"]')->each(function($el){
	$el->attr('style', str_replace('margin-left: 180px;', '', $el->attr('style')));
});

$html = $dataDom->saveHTML();

echo $html;
?>

<style type="text/css">


#health_safety_week .component-row-cell > *,
#health_safety_week .component-widget-cell > * {
	clear:both;
}

#health_safety_week .widget-html,
#health_safety_week .widget-support {
	border-bottom: 1px solid rgba(0,0,0,.5);
	padding-bottom: 20px;
	margin-bottom: 20px;
}

#health_safety_week .widget-support img,
#health_safety_week .widget-html img {
	max-width: 100% !important; 
	height: auto !important;
}

#health_safety_week .widget-html table td {
	padding: 8px;
	vertical-align: bottom;
}

#health_safety_week .widget-html table td a {
	display: block;
}

#health_safety_week .widget-html table td br {
	display: none;
}

#health_safety_week .widget-html .ui-link {
	text-decoration: underline;
}


#health_safety_week #f43036c1-a699-4f31-bed8-2e9c4b1b050b img {
	float: left;
	margin: 0 12px 6px 0;
	width: 20% !important;
}

#health_safety_week #f43036c1-a699-4f31-bed8-2e9c4b1b050b ul {
	margin-bottom: 20px;
	margin-left: 1em;
	padding: 0;
}
#health_safety_week #f43036c1-a699-4f31-bed8-2e9c4b1b050b ul li {
	position: relative;
	left: 1em;
}

#health_safety_week .widget-support img {
	float: left;
	width: 20%;
	margin: 0 12px 6px 0;
}

</style>


	</div><!-- /content -->
</div>
