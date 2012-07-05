<h1><?= Yii::t('items','shares') ?></h1>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('./share')) ?>
	<div class="span-22">
		<?= CHtml::label(Yii::t('items','tags'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('tags', $tags,array('class'=>'span-18', 'title'=>Yii::t('items', 'use -')) ) ?>
		<?= CHtml::submitButton(Yii::t('items','filter'), array('name'=>'filter')) ?>
	</div>
	<?php if (!Yii::app()->user->isGuest) { ?>
	<div class="prepend-1 span-16">
		<?= CHtml::checkBox('mine', substr_count($options, 'mine') > 0) . Yii::t('items', 'my shares') ?>	
	</div>
	<?php } ?>
	<div class="span-3 last">
		<?= Yii::t('global', 'show') . ' '
						. CHtml::link('10', $this->createUrl('?ps=10')) . ' '
						. CHtml::link('25', $this->createUrl('?ps=25')) . ' ' 
						. CHtml::link('50', $this->createUrl('?ps=50')) . ' ' 
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
<?php
	if (count($items) == 0)
		echo Yii::t('items', 'no shares');
	foreach($items as $item) { ?>
<div class="span-23 append-bottom">
	<div class="span-20">
		<?= CHtml::link($item['user_name'], $this->createUrl('user/view/' . $item['user_id'])) . ' ' . 
				Yii::t('items', 'user shares') . ' ' .
				CHtml::link($item['description'], $this->createUrl('share/view/' . $item['id'])) ?>
	</div>
	<div class="span-3 last">
		<?php if ($item['user_id'] == Yii::app()->user->getState('user_id')) { ?>
		<span id="deleteU<?= $item['id'] ?>" style="display: inline">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/cross-button.png" alt="-" 
					 onclick="toggle('deleteU<?= $item['id'] ?>');toggle('confirmationU<?= $item['id'] ?>');"/>
		</span>			 
		<span id="confirmationU<?= $item['id'] ?>" style="display: none">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/slash-button.png" alt="N"
				onclick="toggle('deleteU<?= $item['id'] ?>');toggle('confirmationU<?= $item['id'] ?>');"/>
			<?= Yii::t('global', 'sure?') ?>
			<a href="<?= $this->createUrl('share/delete/' . $item['id']) ?>" >
				<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/tick-button.png" alt="Y"/>
			</a>
		</span>
		<?php } ?>
	</div>
</div>
<?php } ?>
