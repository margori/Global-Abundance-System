<h1><?= Yii::t('item', 'add items') ?></h1>
<?php
	$thisUrl = 'need/addItem/' . $solutionId . '/' . $needId; ?>
<div class="push-1 span-20 box last">
	<div class="span-17">
		<?= CHtml::link($solution['user_name'], $this->createUrl('user/' . $solution['user_id'])) . ' ' . Yii::t('item', 'proposes') ?>
	</div>
	<?php
		if (isset($solution['items']))
			foreach($solution['items'] as $solutionItem) {
	?>
	<div class="span-19">
		<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/puzzle.png" alt="-" />
		<a href="<?= $this->createUrl('share/view/' . $solutionItem['item_id']) ?>" >
			<?= ' ' . $solutionItem['description'] ?>
		</a>
	</div>
	<div class="span-1 last">
		<?php if($userId == $solution['user_id']) { ?> 
		<a href="<?= $this->createUrl('need/deleteSolutionItem/' . $solutionItem['id'] . '/' . $need->id) ?>" >
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/minus-button.png" alt="-" />
		</a>
		<?php } ?> 
	</div>
	<?php 
			} // foreach $solutionItem 
	?>
</div>
<?php echo CHtml::beginForm($this->createUrl($thisUrl)) ?>
<div class="span-22 box">
	<div class="span-22">
		<?= CHtml::label(Yii::t('item','tags'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('tags', $tags,array('class'=>'span-18') ) ?>
		<?= CHtml::submitButton(Yii::t('item','filter')) ?>
	</div>
	<div class="prepend-1 span-16">
		<?= CHtml::checkBox('mine', substr_count($options, 'mine') > 0) . Yii::t('item', 'my shares') ?>	
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
<div class="span-1" >
	<?= CHtml::checkBox('check' . $share['id'], false, array('value' => 'check' . $share['id'], 'style'=>'margin: -1px;')) ?>
	<?= $share['love'] == 3 ? CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart.png','', array('style'=>'margin: -2px;') ) : ''; ?>
</div>
<div class="span-22 append-bottom last">
	<?= CHtml::link($share['user_name'], $this->createUrl('user/view/' . $share['user_id'])) . ' ' . 
			Yii::t('item', 'user shares') . ' ' .
			CHtml::link($share['description'], $this->createUrl('share/view/' . $share['id'])) ?>
</div>
<?php } ?>
<div class="span-21 last">
	<?= CHtml::submitButton(Yii::t('item','add and continue'), array('name' => 'addContinue',)) ?>
	<?= CHtml::submitButton(Yii::t('item','add and return'), array('name' => 'addReturn',)) ?>
	<?= CHtml::submitButton(Yii::t('item','cancel'), array('name' => 'cancel',)) ?>
</div>
<?php echo CHtml::endForm(); ?>
