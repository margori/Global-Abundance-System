<h1><?= Yii::t('global','users') ?></h1>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('./user')) ?>
	<div class="span-22">
		<?= CHtml::label(Yii::t('user','name filter'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('nameFilter', $nameFilter,array('class'=>'span-18') ) ?>
		<?= CHtml::submitButton(Yii::t('item','filter'), array('name'=>'filter')) ?>
	</div>
	<div class="right span-3 last" style="margin-right: 78px;">
		<?= Yii::t('global', 'show') . ' '
						. CHtml::link('25', $this->createUrl('?ps=25')) . ' '
						. CHtml::link('50', $this->createUrl('?ps=50')) . ' ' 
						. CHtml::link('100', $this->createUrl('?ps=100')) . ' ' 
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
<?php foreach($users as $user) { ?>
<div class="box" style="display: inline-block">
	<?= $user['love'] == 0 ? CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart-break.png', '', array('style'=>'margin-bottom: -4px;')) : ''; ?>
	<?= $user['love'] == 2 ? CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart-half.png', '', array('style'=>'margin-bottom: -4px;')) : ''; ?>
	<?= $user['love'] == 3 ? CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart.png', '', array('style'=>'margin-bottom: -4px;')) : ''; ?>
	<?= CHtml::link($user['real_name'], $this->createUrl('user/' . $user['id'])) ?>
</div>
<?php } ?>
