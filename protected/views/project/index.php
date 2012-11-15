<h1><?= Yii::t('project', 'projects') ?></h1>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('./project')) ?>
	<div class="span-22">
		<?= CHtml::label(Yii::t('project','name'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('nameFilter', $nameFilter,array('class'=>'span-18', 'title'=>Yii::t('project', 'use -')) ) ?>
	</div>
	<div class="span-22">
		<?= CHtml::label(Yii::t('project','tags'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('tags', $tags,array('class'=>'span-18', 'title'=>Yii::t('project', 'use -')) ) ?>
		<?= CHtml::submitButton(Yii::t('project','filter'), array('name'=>'filter')) ?>
	</div>
	<div class="prepend-2 span-16">
		<?= Yii::app()->user->isGuest ? '' : CHtml::checkBox('mine', substr_count($options, 'mine') > 0) . Yii::t('project', 'my projects') ?>	
	</div>
	<div class="span-3 last">
		<?= Yii::t('global', 'show') . ' '
						. CHtml::link('10', $this->createUrl('?ps=10')) . ' '
						. CHtml::link('25', $this->createUrl('?ps=25')) . ' ' 
						. CHtml::link('50', $this->createUrl('?ps=50')) . ' ' 
						?>
	</div>
	<?php echo CHtml::endForm(); ?>
</div>
<div class="push-1 span-21 append-bottom last">
	<a href="<?= $this->createUrl('project/new') ?>" >
		<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/exclamation.png" alt="-" />
		<?= Yii::t('project','project new') ?>
	</a>
</div>
<?php
	if ($pageCount > 1)
	{
		echo '<div class="prepend-1 span-22 last append-bottom">';
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
<div class="clear"></div>
<?php 
	if (count($projects) == 0)
		echo '<div class="push-1 span-22 last append-bottom">' . Yii::t('project', 'no projects') . '</div>';
	foreach($projects as $project) { ?>
<div class="span-1" style="text-align: right;">
	<?= $project['love'] == 3 ? CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart.png' ) : '&nbsp;'; ?>
</div>
<div class="span-22 last append-bottom">
	<div class="span-21">
		<?= CHtml::link($project['user_name'], $this->createUrl('user/view/' . $project['user_id'])) . ' ' . 
				Yii::t('project', 'user projects') . ' ' .
				CHtml::link($project['name'] . ': ' .$project['description'], $this->createUrl('project/view/' . $project['id'])) ?>
	</div>
	<div class="span-1 last">
		<?php if ($project['user_id'] == Yii::app()->user->getState('user_id')) { ?>
		<span id="deleteU<?= $project['id'] ?>" style="display: inline">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/cross-button.png" alt="-" 
					 onclick="toggle('deleteU<?= $project['id'] ?>');toggle('confirmationU<?= $project['id'] ?>');"/>
		</span>			 
		<span id="confirmationU<?= $project['id'] ?>" style="display: none">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/slash-button.png" alt="N"
				onclick="toggle('deleteU<?= $project['id'] ?>');toggle('confirmationU<?= $project['id'] ?>');"/>
			<?= Yii::t('global', 'sure?') ?>
			<a href="<?= $this->createUrl('project/delete/' . $project['id']) ?>" >
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
		echo '<div class="prepend-1 span-22 last append-bottom">';
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
<div class="push-1 span-21 append-bottom last">
	<a href="<?= $this->createUrl('project/new') ?>" >
		<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/exclamation.png" alt="-" />
		<?= Yii::t('project','project new') ?>
	</a>
</div>

