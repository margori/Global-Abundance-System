<h1><?= Yii::t('user','my account') ?></h1>
<?php echo CHtml::beginForm($this->createUrl('user/save')) ?>
<div class="span-22 box">
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','username') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('username', $model->username, array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','real name') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('realName', $model->realName, array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','email') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('email', $model->email, array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','language') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::dropDownList('language', $model->language, $languages) ?>
		</div>
	</div>
	<div class="prepend-1 span-21">
		<?= CHtml::submitButton(Yii::t('global','save'), array('name' => 'save')) ?>
		<?= CHtml::submitButton(Yii::t('global','cancel'), array('name' => 'cancel')) ?>
		<?php if (isset($messageSave)) { ?>
			<span class="errorMessage">
				<?= $messageSave ?>
			</span>
		<?php }?>
	</div>
</div>
<?php echo CHtml::endForm(); ?>
<?php echo CHtml::beginForm($this->createUrl('user/save')) ?>
<div class="span-22 box">
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','password') ?>
		</div>
		<div class="span-16 last">
		<?= CHtml::passwordField('password', '', array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','confirmation') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::passwordField('confirmation', $model->confirmation, array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21">
		<?= CHtml::submitButton(Yii::t('global','change'), array('name' => 'change')) ?>
		<?= CHtml::submitButton(Yii::t('global','cancel'), array('name' => 'cancel')) ?>
		<?php if (isset($messageChangePassword)) { ?>
			<span class="errorMessage">
				<?= $messageChangePassword ?>
			</span>
		<?php }?>
	</div>
</div>
<?= CHtml::endForm(); ?>
<h2><?= Yii::t('user','about me') ?></h2>
<?= CHtml::beginForm($this->createUrl('user/save')); ?>
<div class="span-22 box">
		<?= CHtml::textArea('about',$model->about,array(
				'class'=>'span-22',
				'rows'=>'18',
				'maxlength'=>5000,
				'id' => 'about',
				)) ?>
		<?= CHtml::submitButton(Yii::t('global','save'), array('name' => 'saveAbout')) ?>
</div>
<?= CHtml::endForm(); ?>
<h2><?= Yii::t('user','my zones') ?></h2>
<?= CHtml::beginForm($this->createUrl('user/save')); ?>
<script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"  type="text/javascript"></script>
<script>
	var map;
	var selectedZone = 0;
	var lastZone = <?= count($zones) ?>;
	var rectangles = Array(4);
	var topCircle;
	var bottomCircle;
	
	function refreshZone(n)
	{
		var id = "zone" + n;
		var zone = document.getElementById(id);
		if (n == selectedZone)		
			zone.style.background = "#fff";
		else
			zone.style.background = "#e5eCf9";
		if (n > lastZone)
			zone.style.display = "none";
		else
			zone.style.display = "inline";
		
		var id = "newZone" + n;
		var newZone = document.getElementById(id);
		if (n == lastZone + 1)
			newZone.style.display = "inline";
		else
			newZone.style.display = "none";	

		var id = "deleteZone" + n;
		var element = document.getElementById(id);
		if (n == selectedZone)
			element.style.display = "inline";
		else
			element.style.display = "none";	

		var id = "zone" + n + "Top";
		var top = document.getElementById(id);
		var id = "zone" + n + "Left";
		var left = document.getElementById(id);
		var id = "zone" + n + "Bottom";
		var bottom = document.getElementById(id);
		var id = "zone" + n + "Right";
		var right = document.getElementById(id);
		var id = "zone" + n + "Status";
		var status = document.getElementById(id);
				
		var rectangle = rectangles[n-1];
		if (status.value == "save")
		{
			var topLeft = new L.LatLng(top.value,left.value);
			var bottomRight = new L.LatLng(bottom.value, right.value);
			var bounds = new L.LatLngBounds(topLeft, bottomRight);
			rectangle.setBounds(bounds);
		}
		else
			rectangle.setBounds([[0, 0],[0, 0]]);
	}

	function selectZone(n)
	{
		if (n > lastZone)
			return;
		
		selectedZone = n;

		for(i = 1 ; i<= 4 ; i++)
		{
			refreshZone(i);
		}
				
		if (n>0)
		{
			var id = "zone" + n + "Top";
			var top = document.getElementById(id);
			var id = "zone" + n + "Left";
			var left = document.getElementById(id);
			var id = "zone" + n + "Bottom";
			var bottom = document.getElementById(id);
			var id = "zone" + n + "Right";
			var right = document.getElementById(id);	

			var topLeft = new L.LatLng(top.value,left.value);
			var bottomRight = new L.LatLng(bottom.value, right.value);

			topCircle.setLatLng(topLeft);
			bottomCircle.setLatLng(bottomRight);

			var bounds = new L.LatLngBounds(topLeft, bottomRight);
			map.fitBounds( bounds );
		}
	}
	
	function circleMove(e)
	{
		var corner = e.target.Id.charAt(0);
		var center = e.target.getLatLng();
			
		if (corner == "t")
		{
			var topLeft = center;
			
			var id = "zone" + selectedZone + "Top";
			var element = document.getElementById(id);
			element.value = topLeft.lat;
			
			var id = "zone" + selectedZone + "Left";
			var element = document.getElementById(id);
			element.value = topLeft.lng;
		}
		else
		{
			var bottomRight = center;
			
			var id = "zone" + selectedZone + "Bottom";
			var element = document.getElementById(id);
			element.value = bottomRight.lat;
			
			var id = "zone" + selectedZone + "Right";
			var element = document.getElementById(id);
			element.value = bottomRight.lng;
		}
				
		refreshZone(selectedZone);
	}
	
	function newZone(n)
	{
		lastZone++;
		
		var s = "zone" + n + "Status";
		var zoneStatus = document.getElementById(s);
		zoneStatus.value = "save";

		var centerLatLng = map.getCenter();
		var centerPoint = map.latLngToContainerPoint(centerLatLng);
		
		var topLeft = new L.Point(centerPoint.x - 100, centerPoint.y - 100);
		var topLeftLatLng = map.containerPointToLatLng(topLeft);

		var bottomRight = new L.Point(centerPoint.x + 100, centerPoint.y + 100);
		var bottomRightLatLng = map.containerPointToLatLng(bottomRight);

		var s = "zone" + n + "Top";
		var e = document.getElementById(s);
		e.value = topLeftLatLng.lat;

		var s = "zone" + n + "Left";
		var e = document.getElementById(s);
		e.value = topLeftLatLng.lng;
		
		var s = "zone" + n + "Bottom";
		var e = document.getElementById(s);
		e.value = bottomRightLatLng.lat;
		
		var s = "zone" + n + "Right";
		var e = document.getElementById(s);
		e.value = bottomRightLatLng.lng;

		selectZone(n);
	}
	
	function copy(i, data)
	{
		var s = "zone" + i + data;
		var e1 = document.getElementById(s);
		var s = "zone" + (i+1) + data;
		var e2 = document.getElementById(s);
		e1.value = e2.value;
		
	}

	function deleteZone(n)
	{
		for(i = n; i < 4; i++)
		{
			copy(i, "Status");
			copy(i, "Top");
			copy(i, "Right");
			copy(i, "Bottom");
			copy(i, "Left");
		}
		
		var s = "zone" + lastZone + "Status";
		var zoneStatus = document.getElementById(s);
		zoneStatus.value = "delete";

		lastZone--;
		
		if (n > lastZone)
			selectZone(lastZone);
		else if (lastZone > 0)
			selectZone(n);
		else
			map.setView(new L.LatLng(<?= Yii::app()->params['default_latitude'] ?>, <?= Yii::app()->params['default_longitude'] ?>), 13);
	}
</script>
<div class="span-22 box">
	<div class="span-1" >
		<?php
			$maxZones = 4;
			for($z = 1; $z <= $maxZones; $z ++)
			{
		?>
		<div id="zone<?= $z ?>" class="span-1 last prepend-top" style="text-align: right; padding: 5px; <?= $z > count($zones) ? 'display: none;' : '' ?>" 
			onclick="selectZone(<?= $z ?>);">	
			<img id="deleteZone<?= $z ?>" src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/cross-button.png" style="display: none;"
					 onclick="deleteZone(<?= $z ?>);" />
			<b><?= $z ?></b>
			<input id="zone<?= $z ?>Status" name="zone<?= $z ?>Status" type="hidden" value="<?= $z <= count($zones) ? 'save' : 'delete' ?>"/>
			<input id="zone<?= $z ?>Id" name="zone<?= $z ?>Id" type="hidden" value="<?= $z <= count($zones) ? $zones[$z - 1]['id'] : '' ?>" />
			<input id="zone<?= $z ?>Top" name="zone<?= $z ?>Top" type="hidden" value="<?= $z <= count($zones) ? $zones[$z - 1]['top'] : '0' ?>" />
			<input id="zone<?= $z ?>Right" name="zone<?= $z ?>Right" type="hidden" value="<?= $z <= count($zones) ? $zones[$z - 1]['right'] : '0' ?>" />
			<input id="zone<?= $z ?>Bottom" name="zone<?= $z ?>Bottom" type="hidden" value="<?= $z <= count($zones) ? $zones[$z - 1]['bottom'] : '0' ?>" />
			<input id="zone<?= $z ?>Left" name="zone<?= $z ?>Left" type="hidden" value="<?= $z <= count($zones) ? $zones[$z - 1]['left'] : '0' ?>" />
		</div>
		<div id="newZone<?= $z ?>" class="span-1 last prepend-top" style="text-align: right; padding: 5px; <?= $z == count($zones) + 1 ? '' : 'display: none;' ?>"
			onclick="newZone(<?= $z ?>);">	
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/plus-button.png" />
		</div>
		<?php } ?>	
	</div>
	<div id="map" class="span-21 last" style="height: 450px; background-color: #fff;  "></div>
	<div class="prepend-top span-22 last">
		<?= CHtml::submitButton(Yii::t('global','save'), array('name' => 'zones')) ?>
	</div>
</div>
<script type="text/javascript" >	
	
	var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png';
	var cloudmadeAttribution = '<a href="http://openstreetmap.org">OpenStreetMap</a>, <a href="http://cloudmade.com">CloudMade</a>';
	var	cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 18, attribution: cloudmadeAttribution});

	map = new L.Map('map');
	map.addLayer(cloudmade);
	
	<?php 
		
		$minLat = Yii::app()->params['default_latitude'];
		$maxLat = Yii::app()->params['default_latitude'];
		$minLong = Yii::app()->params['default_longitude'];
		$maxLong = Yii::app()->params['default_longitude'];

		if (count($zones) > 0) 
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
		}
?>
	var topLeft = new L.LatLng(<?= $minLat ?>,<?= $minLong ?>);
	var bottomRight = new L.LatLng(<?= $maxLat ?>, <?= $maxLong ?>);
	var bounds = new L.LatLngBounds(topLeft, bottomRight);
	map.fitBounds(bounds);
	
	for(var i = 1; i<=4; i++)
	{
		var rectangle = new L.rectangle([[0,0], [0,0]], {weight : 2});
		rectangle.addTo(map);
		rectangles[i-1] = rectangle;	
		refreshZone(i);
	}

	topCircle = new L.Marker([0,0], {icon : new L.icon({
			iconUrl: '<?= Yii::app()->baseUrl ?>/images/icons/16x16/layer.png',
			iconSize: [16, 16],
			iconAnchor: [8, 8]
		}), draggable : true});
	topCircle.Id = "top";
	topCircle.on('drag',circleMove);
	topCircle.addTo(map);

	bottomCircle = new L.Marker([0,0], {icon : new L.icon({
			iconUrl: '<?= Yii::app()->baseUrl ?>/images/icons/16x16/layer.png',
			iconSize: [16, 16],
			iconAnchor: [8, 8]
		}), draggable : true});
	bottomCircle.Id = "bottom";
	bottomCircle.on('drag',circleMove);
	bottomCircle.addTo(map);
</script>
<?= CHtml::endForm(); ?>
<div class="prepend-1 span-22 append-bottom">
	<?= Yii::t('user', 'delete account') ?>
	<span id="deleteU" style="display: inline">
		<img src="../../images/icons/16x16/cross-button.png" alt="-" 
				 onclick="toggle('deleteU');toggle('confirmationU');"/>
	</span>			 
	<span id="confirmationU" style="display: none">
		<img src="../../images/icons/16x16/slash-button.png" alt="N"
		  onclick="toggle('deleteU');toggle('confirmationU');"/>
		<?= Yii::t('global', 'sure?') ?>
		<a href="<?= $this->createUrl('user/delete/'.$model->id) ?>" >
			<img src="../../images/icons/16x16/tick-button.png" alt="Y"/>
		</a>
	</span>
</div>
