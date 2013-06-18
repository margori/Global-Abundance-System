<h1><?= Yii::t('item', 'add items') ?></h1>
<div class="span-22 box last">
	<div class="span-18">		
		<?php 
			echo '<strong>' . CHtml::link($need->username, $this->createUrl('user/' . $need->user_id)) . '</strong> ';
			if ($need->project_name)
				echo Yii::t('interaction', 'for') . ' ' . '<strong>' . CHtml::link($need->project_name, $this->createUrl('project/' . $need->project_id)) . '</strong> ';
			echo Yii::t('item', 'user needs'); 
		?>
	</div>
	<div id="currentDescription" class="span-22">
		<?= '<strong>'. $need->description .'</strong>'?>
	</div>
</div>
<div class="push-1 span-20 box last">
	<div class="span-17">
		<?= CHtml::link($solution['user_name'], $this->createUrl('user/' . $solution['user_id'])) . ' ' . Yii::t('item', 'proposes') ?>
	</div>
	<?php
		if (isset($solution->items))
			foreach($solution->items as $solutionItem) {
	?>
	<div class="span-19">
		<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/puzzle.png" alt="-" />
		<a href="<?= $this->createUrl('share/view/' . $solutionItem['item_id']) ?>" >
			<?= ' ' . $solutionItem['description'] ?>
		</a>
	</div>
	<div class="span-1 last">
		<?php if($userId == $solution['user_id']) { ?> 
		<a href="<?= $this->createUrl('need/deleteSolutionItemBackToSolution/' . $solution['id'] . '/' . $solutionItem['id']) ?>" >
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/minus-button.png" alt="-" />
		</a>
		<?php } ?> 
	</div>
	<?php 
			} // foreach $solutionItem 
	?>
</div>
<?php
	$thisUrl = 'need/addItem/' . $solution['id'] . '/' . $need['id'];
	echo CHtml::beginForm($this->createUrl($thisUrl)) ?>
<div class="push-2 span-19 border">
	<div class="span-18 box border">
		<div class="span-18">
			<?= CHtml::label(Yii::t('item','tags'),false,array('class'=>'span-2')); ?>
			<?= CHtml::textField('tags', $tags,array('class'=>'span-14') ) ?>
			<?= CHtml::submitButton(Yii::t('item','filter')) ?>
		</div>
		<div class="prepend-1 span-12">
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
	<?php echo CHtml::endForm(); ?>
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
	<div class="push-1 span-18" >
		<div class="span-1" >
			<?= $share['love'] == 3 ? CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart.png','', array('style'=>'margin: -2px;') ) : ''; ?>
		</div>
		<div class="span-16">
			<?= CHtml::link($share['user_name'], $this->createUrl('user/view/' . $share['user_id'])) . ' ' . 
					Yii::t('item', 'user shares') . ' ' .
					CHtml::link($share['description'], $this->createUrl('share/view/' . $share['id'])) ?>
		</div>
		<div class="right span-1 last">
			<a href="<?= $this->createUrl('need/addItem/' . $solution['id'] . '/' . $share['id']) ?>?p=1" ><img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/plus-button.png" alt="+" /></a>
		</div>
	</div>
	<?php } ?>
	<div class="clear" />
	<div class="span-1 box right last" >
		<?= CHtml::link(Yii::t('global','ready'), $this->createUrl('need/' . $need['id'] )) ?>
	</div>
</div>