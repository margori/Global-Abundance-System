<div class="span-22 box last" style="text-align: center;">
<?php
	foreach (Yii::app()->params['languages'] as $iso => $language) 
	{
		echo '<h1>';
		echo Yii::t('user','ban',array(),null, $iso); 
		echo '</h1>';
	}
?>
</div>
