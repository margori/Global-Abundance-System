<h1><?= Yii::t('items', 'add items') ?></h1>
<?php
	$thisUrl = 'need/addItem/' . $solutionId . '/' . $needId;
echo CHtml::beginForm($this->createUrl($thisUrl)) ?>
<div class="span-22 box">
	<div class="span-22">
		<?= CHtml::label(Yii::t('items','tags'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('tags', $tags,array('class'=>'span-18') ) ?>
		<?= CHtml::submitButton(Yii::t('items','filter')) ?>
	</div>
	<div class="prepend-1 span-16">
		<?= CHtml::checkBox('mine', substr_count($options, 'mine') > 0) . Yii::t('items', 'my shares') ?>	
	</div>
	<div class="span-3 last">
		<?= Yii::t('global', 'show') . ' '
						. CHtml::link('10', $this->createUrl($thisUrl.'?ps=10')) . ' '
						. CHtml::link('25', $this->createUrl($thisUrl.'?ps=25')) . ' ' 
						. CHtml::link('50', $this->createUrl($thisUrl.'?ps=50')) . ' ' 
						?>
	</div>
</div>
<?php
	if ($pageCount > 1)
	{
		echo '<div class="span-23  append-bottom">';
		if ($pageCurrent > 1)
		{
			echo CHtml::link('<<', $this->createUrl($thisUrl.'?p=1')) . '  ';
			echo CHtml::link('<', $this->createUrl($thisUrl.'?p=' . ($pageCurrent - 1))) . ' ';
		}
		else
			echo '<<  < ';

		for($i = 1; $i <= $pageCount ; $i++)
		{
			if ($i == $pageCurrent)
				echo $i;
			else
				echo CHtml::link($i, $this->createUrl($thisUrl.'?p=' . $i));
			echo ' ';
		}

		if ($pageCurrent < $pageCount)
			echo CHtml::link('>', $this->createUrl($thisUrl.'?p=' . ($pageCurrent + 1))) . ' ';
		else
			echo '> ';
		echo '</div>';
	}	
?>
<?php foreach($shares as $share) { ?>
<p>
	<?= CHtml::checkBox('check' . $share['id'], false, array('value' => 'check' . $share['id'])) ?>
	<?= CHtml::link($share['description'], $this->createUrl('share/view/' . $share['id']), array('target'=>'_blank')) ?>
</p>
<?php } ?>
<div>
	<?= CHtml::submitButton(Yii::t('items','add and continue'), array('name' => 'addContinue',)) ?>
	<?= CHtml::submitButton(Yii::t('items','add and return'), array('name' => 'addReturn',)) ?>
	<?= CHtml::submitButton(Yii::t('items','cancel'), array('name' => 'cancel',)) ?>
</div>
<?php echo CHtml::endForm(); ?>
