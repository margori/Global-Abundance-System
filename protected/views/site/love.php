<h1><?= Yii::t('global', 'GAS motivations') ?></h1>
<?php 
	$count = 1;
	$s = Yii::t('love','motive'.$count);
	while($s != 'motive'.$count)
	{	
		$count++;
?>
<div class="span-22 append-bottom">
	<?= $s ?>
</div>
<?php	
		$s = Yii::t('love','motive'.$count);
	} ?>	
