<h1><?= Yii::t('items','solution for') ?></h1>
<div class="span-22 box last">
	<div class="span-18">		
		<?= '<strong>' . CHtml::link($need->username, $this->createUrl('user/' . $need->user_id)) . '</strong> ' . Yii::t('items', 'user needs'); ?>
	</div>
	<?php if (!Yii::app()->user->isGuest) { ?>
	<div class="right">		
		<a href="<?= $this->createUrl('need/edit/' . $need->id) ?>"><img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/pencil.png" alt="-" /></a>
	</div>
	<?php } ?>
	<div id="currentDescription" class="span-22">
		<?= '<strong>'. $need->description .'</strong>'?>
	</div>
	<div class="right last">
		<span id="originalLabel" onclick="toggle('originalDescription');"><?= Yii::t('items','original') ?></span>		
	</div>
	<div id="originalDescription" class="span-22" style="display: none">
		<?= $need->original_description ?>
	</div>
</div>
<!-- Solutions -->
<?php foreach($solutions as $solution) { ?>
<div class="push-1 span-20 box last">
	<div class="span-17">
		<?= CHtml::link($solution['user_name'], $this->createUrl('user/' . $solution['user_id'])) . ' ' . Yii::t('items', 'proposes') ?>
	</div>
	<div class="span-3 last">
		<?php if($userId == $solution['user_id']) { ?> 
		<span id="deleteS<?= $solution['id'] ?>" style="display: inline">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/cross-button.png" alt="-" 
						onclick="toggle('deleteS<?= $solution['id'] ?>');toggle('confirmationS<?= $solution['id'] ?>');;"/>
		</span>			 
		<span id="confirmationS<?= $solution['id'] ?>" style="display: none">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/slash-button.png" alt="N"
						onclick="toggle('deleteS<?= $solution['id'] ?>');toggle('confirmationS<?= $solution['id'] ?>');"/>
			<?= Yii::t('global', 'sure?') ?>
			<a href="<?= $this->createUrl('need/deleteSolution/' . $solution['id'] . '/' . $need->id) ?>" >
				<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/tick-button.png" alt="Y"/>
			</a>
		</span>			 
		<? } ?>
	</div>
	<?php
		if (isset($solution['items']))
			foreach($solution['items'] as $solutionItem) {
	?>
	<div class="span-19">
		<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/puzzle.png" alt="-" />
		<?= ' ' . $solutionItem['description'] ?>
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
			if (!Yii::app()->user->isGuest)
			{
	?>
	<div class="span-21" >
		<div class="span-6">
			<a href="<?= $this->createUrl('need/addItem/' . $solution['id'] . '/' . $need->id) ?>?p=1" ><img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/plus-button.png" alt="-" /></a>
			<?php if (count($solution['items']) == 0 ) echo Yii::t('items', '< add items'); ?>
		</div>
		<div class="prepend-9 span-5">
			<?php  
				if($solution['status'] == 1) // Draft
					echo '<strong>';
				echo CHtml::link(Yii::t('items', 'draft'), $this->createUrl('need/draft/' . $solution['id'] . '/' . $need->id)) . '&nbsp;';
				if($solution['status'] == 1)
					echo CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/hard-hat.png') . '&nbsp;</strong>';
				if($solution['status'] == 2)
					echo '<strong>';
				echo CHtml::link(Yii::t('items', 'complete'), $this->createUrl('need/complete/' . $solution['id'] . '/' . $need->id)) ;
				if($solution['status'] == 2)
					echo CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/light-bulb.png') . '</strong>';
				if ($need->user_id == Yii::app()->user->getState('user_id'))
					echo '&nbsp;'. CHtml::link(Yii::t('items', 'take'), $this->createUrl('need/take/' . $solution['id'] . '/' . $need->id))
							.'&nbsp;'. CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/thumb-up.png');
			?>
		</div>
	</div> 
	<?php } ?>
</div>
<?php } // foreach $solution ?>
<?php if (isset($userId)) { ?>
<div class="clear push-1">
	<a href="<?= $this->createUrl('need/newSolution/' . $need->id) ?>" >
		<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/exclamation-button.png" alt="-" />
		<?= Yii::t('items','solution new') ?>
	</a>
</div>
<?php } ?>
<!-- Comments -->
<div class="prepend-1 span-20 last">
<?php
	$userId = Yii::app()->user->getState('user_id');
	foreach($comments as $comment)
	{ ?>
	<div class="span-14">
			<?= CHtml::link($comment['user_name'], $this->createUrl('user/' . $comment['user_id'])) . ' ' . Yii::t('interaction','comments'). ' ' ?>
		<?php if ($comment['user_id'] == $userId) { ?>
			<span id="deleteC<?= $comment['id'] ?>" style="display: inline">
				<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/cross-button.png" alt="-" 
							onclick="toggle('deleteC<?= $comment['id'] ?>');toggle('confirmationC<?= $comment['id'] ?>');"/>
			</span>			 
			<span id="confirmationC<?= $comment['id'] ?>" style="display: none">
				<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/slash-button.png" alt="N"
							onclick="toggle('deleteC<?= $comment['id'] ?>');toggle('confirmationC<?= $comment['id'] ?>');"/>
				<?= Yii::t('global', 'sure?') ?>
				<a href="<?= $this->createUrl('need/deleteComment/'.$comment['id'].'/'.$need->id) ?>" >
					<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/tick-button.png" alt="Y"/>
				</a>
			</span>			 
		<?php } ?>
	</div>
	<div class="span-21 append-bottom">
		<?= $comment['comment'] ?>		
	</div>		
<?php 
	} 
	if (!Yii::app()->user->isGuest)
	{
?>
	<div class="span-13">
	<?php	
			echo CHtml::beginForm($this->createUrl('need/comment/'.$need->id)); 		
			echo CHtml::textArea('comment','',array(
				'class'=>'span-21',
				'rows'=>'5',
				'maxlength'=>1000,
				));
			echo CHtml::submitButton(Yii::t('interaction','comment'), array('name'=>'comment_button'));
			echo CHtml::endForm(); 
	?>
	</div>	
<?php } ?>
</div>