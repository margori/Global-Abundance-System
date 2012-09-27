<?php
	$imagePadding = 'padding: 10px 6px 4px 6px;';
	$brokenUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart-break.png';
	$emptyUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart-empty.png';
	$halfUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart-half.png';
	$fullUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart.png';
?>
<h1><?= Yii::t('user','user') ?></h1>
<div class="span-22 box">
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','real name'), false); ?>:
		</div>
		<div class="span-14">
			<strong>
			<?php
				echo $model->realName ? $model->realName : $model->username;
				switch ($model->hisLove)
				{
					case 0: echo '. ' . sprintf(Yii::t('user','you broke his heart'), 
						CHtml::image($brokenUrl, '', array('title'=>Yii::t('user','have broken')))); break;
					case 3:echo '. ' . sprintf(Yii::t('user','he loves you'), CHtml::image($fullUrl)); break;
				}
			?>
			</strong>
		</div>
		<?php if (!Yii::app()->user->isGuest) { ?>
		<div style="position: absolute; margin-left: 750px; margin-top: -20px; background-color: #fff; padding: 10px;">
			<?= CHtml::link(CHtml::image($brokenUrl), $this->createUrl('user/love/' . $model->id . '/0'), array(
				'title'=>Yii::t('user', 'user broke my heart'),
				'style'=>$imagePadding . ($model->myLove == 0 ? "background-color: #e5eCf9;" : ''),
			)) ?>
			<?= CHtml::link(CHtml::image($emptyUrl), $this->createUrl('user/love/' . $model->id . '/1'), array(
				'title'=>Yii::t('user', 'I dont know this user'),
				'style'=>$imagePadding . ($model->myLove == 1 ? "background-color: #e5eCf9;" : ''),
			)) ?>
			<?= CHtml::link(CHtml::image($halfUrl), $this->createUrl('user/love/' . $model->id . '/2'), array(
				'title'=>Yii::t('user', 'I like this user'),
				'style'=>$imagePadding . ($model->myLove == 2 ? "background-color: #e5eCf9;" : ''),
			)) ?>
			<?= CHtml::link(CHtml::image($fullUrl, '', array('style'=>'margin-right: 6px;')), $this->createUrl('user/love/' . $model->id . '/3'), array(
				'title'=>Yii::t('user', 'I love this user'),
				'style'=>$imagePadding . ($model->myLove == 3 ? "background-color: #e5eCf9;" : ''),
			)) ?>
		</div>
		<?php } ?>
	</div>
	<div class="span-14 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','id'),false); ?>:
		</div>
		<div class="span-10 last">
			<?= $model->id ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','username'),false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->username ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','email'), false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->email ?>
		</div>
	</div>
	<div class="span-22">
		
	</div>
</div>
<?php if (count($zones) > 0) 
	{
		$minLat = 90;
		$maxLat = -90;
		$minLong = 180;
		$maxLong = -180;

		foreach($zones as $zone)
		{
			if ($zone['top'] < $minLat)
				$minLat = $zone['top'];
			if ($zone['bottom'] < $minLat)
				$minLat = $zone['bottom'];
			if ($zone['top'] > $maxLat)
				$maxLat = $zone['top'];
			if ($zone['bottom'] > $maxLat)
				$maxLat = $zone['bottom'];
			
			if ($zone['left'] < $minLong)
				$minLong = $zone['left'];
			if ($zone['right'] < $maxLong)
				$minLong = $zone['right'];
			if ($zone['left'] > $maxLat)
				$maxLong = $zone['left'];
			if ($zone['right'] > $maxLong)
				$maxLong = $zone['right'];
		}
?>
<h2><?= Yii::t('user','zones') ?></h2>
<div class="span-22 box">
	<div id="map" class="span-22 last" style="height: 450px; background-color: #fff;  "></div>
</div>
<script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"  type="text/javascript"></script>
<script>
	var map = L.map('map');
	
	var topLeft = new L.LatLng(<?= $minLat ?>,<?= $minLong ?>);
	var bottomRight = new L.LatLng(<?= $maxLat ?>, <?= $maxLong ?>);
	var bounds = new L.LatLngBounds(topLeft, bottomRight);
		
	map.fitBounds(bounds);
	L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: '<a href="http://openstreetmap.org">OpenStreetMap</a>, <a href="http://cloudmade.com">CloudMade</a>'
	}).addTo(map);
	
<?php foreach($zones as $zone) { ?>
	var topLeft = new L.LatLng(<?= $zone['top'] ?>,<?= $zone['left'] ?>);
	var bottomRight = new L.LatLng(<?= $zone['bottom'] ?>, <?= $zone['right'] ?>);
	var bounds = new L.LatLngBounds(topLeft, bottomRight);
	var rectangle = new L.rectangle(bounds, {weight : 2});
	rectangle.addTo(map);
<?php } ?>
</script>
<?php } ?>
<?php if (count($needs) > 0) { ?>
<h3><?= Yii::t('global', 'needs') ?></h3>
<?php foreach($needs as $need) { ?>
<div class="box push-1 span-20 last">
	<?= CHtml::link($need['description'], $this->createUrl('need/view/' . $need['id'])) ?>
</div>	
<?php } ?>
<div class="clear"></div>
<?php } 
	if (count($shares) > 0) { 
?>
<h3><?= Yii::t('global', 'shares') ?></h3>
<?php foreach($shares as $share) { ?>
<div class="box push-1 span-20 last">
	<?= CHtml::link($share['description'], $this->createUrl('share/view/' . $share['id'])) ?>
</div>	
<?php } } ?>
