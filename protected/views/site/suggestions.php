<h1><?= Yii::t('global', 'suggestions') ?></h1>
<?php 
	$count = 1;
	$s = Yii::t('register','clause'.$count);
	while($s != 'clause'.$count)
	{	
		$count++;
?>
<div class="span-22 box">
	<?= $s ?>
</div>
<?php	
		$s = Yii::t('register','clause'.$count);
	} ?>	
