<h1><?= Yii::t('server', 'servers') ?></h1>
<h2>
	<?= CHtml::link(Yii::t('server', 'register'), 'server/register'); ?>
	<?= CHtml::link(Yii::t('server', 'update'), 'server/update'); ?>
</h2>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('server/index')) ?>
	<div class="span-3 last">
		<?= Yii::t('global', 'show') . ' '
				. CHtml::link('10', $this->createUrl('?ps=10')) . ' '
				. CHtml::link('25', $this->createUrl('?ps=25')) . ' ' 
				?>
	</div>
	<?php echo CHtml::endForm(); ?>
</div>
<?php
	if ($pageCount > 1)
	{
		echo '<div class="span-23 append-bottom">';
		if ($pageCurrent > 1)
		{
			echo CHtml::link('<<', $this->createUrl('?p=1')) . '  ';
			echo CHtml::link('<', $this->createUrl('?p=' . ($pageCurrent - 1))) . ' ';
		}
		else
			echo '<<  < ';

		for($i = 1; $i <= $pageCount ; $i++)
		{
			if ($i == $pageCurrent)
				echo $i;
			else
				echo CHtml::link($i, $this->createUrl('?p=' . $i));
			echo ' ';
		}

		if ($pageCurrent < $pageCount)
			echo CHtml::link('>', $this->createUrl('?p=' . ($pageCurrent + 1))) . ' ';
		else
			echo '> ';
		echo '</div>';
	}	
?>
<?php foreach($servers as $server) { ?>
<div class="span-22 append-bottom box ">
		<?= CHtml::link($server['name'], 'http://' . $server['address'], array('target'=>'_blank')); ?>
</div>
<?php } ?>

