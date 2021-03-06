{{#location}}
<li data-markerid="{{markerid}}">
	
	<div>
		<div class="list-content">
		<div class="phone"><a href="tel:{{phone}}"><img src="http://vesnaus.com/vsource/v1.6/images/phone.png" alt="phone" width="55" height="55" /></a></div>
			<div class="loc-name"><strong>{{name}}</strong></div>
			<div class="loc-addr">{{address}}</div> 
			<div class="loc-addr2">{{address2}}</div> 
			<div class="loc-addr3">{{city}}{{#if city}},{{/if}} {{state}} {{postal}}</div>
			<div class="loc-phone"><a href="tel:{{phone}}">{{phone}}</a></div>
			<br>
			<div class="loc-services">{{#if services}}<br><strong><?php echo t('Services Offered:') ?></strong><br/>{{/if}}{{services}}</div>
			<div class="loc-web"><a href="{{web}}" target="_blank">{{niceURL web}}</a></div>
			{{#if distance}}
				<div class="loc-dist"><br>{{distance}} {{length}}</div>
				<div class="loc-directions"><a href="https://maps.google.com/maps?saddr={{origin}}&amp;daddr={{address}} {{address2}} {{city}}, {{state}} {{postal}}" target="_blank"><?php echo t('Directions') ?></a></div>
			{{/if}}
		</div>
	</div>
</li>
{{/location}}