<?php
	$brokenUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart-break.png';
	$emptyUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart-empty.png';
	$halfUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart-half.png';
	$fullUrl = Yii::app()->baseUrl . '/images/icons/16x16/heart.png';
?>
<h1><?= Yii::t('item','shares') ?></h1>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('./share')) ?>
	<div class="span-22">
		<?= CHtml::label(Yii::t('item','tags'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('tags', $tags,array('class'=>'span-18', 'title'=>Yii::t('item', 'use -')) ) ?>
		<?= CHtml::submitButton(Yii::t('item','filter'), array('name'=>'filter')) ?>
	</div>
	<?php if (!Yii::app()->user->isGuest) { ?>
	<div class="prepend-1 span-16">
		<?= CHtml::checkBox('mine', substr_count($options, 'mine') > 0) . Yii::t('item', 'my shares') ?>	
		&nbsp;&nbsp;&nbsp;
		<?= CHtml::radioButton('minLove', $minLove == 0, array('value' => 0)) . CHtml::image($brokenUrl) ?>
		<?= CHtml::radioButton('minLove', $minLove == 1, array('value' => 1)) . CHtml::image($emptyUrl) ?>
		<?= CHtml::radioButton('minLove', $minLove == 2, array('value' => 2)) . CHtml::image($halfUrl) ?>
		<?= CHtml::radioButton('minLove', $minLove == 3, array('value' => 3)) . CHtml::image($fullUrl) ?>
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
		echo '<div class="prepend-1 span-22 append-bottom">';
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
		echo Yii::t('item', 'no shares');
	foreach($items as $item) { ?>
<div class="span-1" style="text-align: right;">
	<? if ($item['love'] == 2)
			echo CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart-half.png','' );
		else if ($item['love'] == 3)
			echo CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart.png','' );
		else
			echo '&nbsp;'; ?>
</div>
<div class="span-22 last append-bottom">
	<div class="span-21">
		<?php
			echo CHtml::link($item['user_name'], $this->createUrl('user/view/' . $item['user_id'])); 
			if (isset($item['project_name']))
				echo ' ' . Yii::t('interaction', 'from') . ' ' . CHtml::link($item['project_name'], $this->createUrl('project/' . $item['project_id']));
			echo ' ' . Yii::t('item', 'user shares') . ' ' .
				CHtml::link($item['description'], $this->createUrl('share/view/' . $item['id'])) ?>
	</div>
	<div class="span-1 last">
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
<?php
	if ($pageCount > 1)
	{
		echo '<div class="prepend-1 span-22 append-bottom">';
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
