<h1><?= Yii::t('archive', 'archive title') ?></h1>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('./archive')) ?>
	<div class="span-22">
		<?= CHtml::label(Yii::t('item','tags'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('tags', $tags,array('class'=>'span-18') ) ?>
		<?= CHtml::submitButton(Yii::t('item','filter'), array('name'=>'filter')) ?>
	</div>
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
<?php foreach($archives as $archive) { 
	$s = str_replace("\n", "<br />", $archive['description'])
	?>
<div class="span-22 append-bottom box ">
		<?= $s ?>
</div>
<?php } ?>
