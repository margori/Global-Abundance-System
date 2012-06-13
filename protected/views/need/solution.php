<h1><?= Yii::t('items','solution for') ?></h1>
<script type="text/javascript">

function showCurrent(){
		document.getElementById('currentDescription').style.display = 'block'; 
		document.getElementById('originalDescription').style.display = 'none'; 
	}

function showOriginal(){
		document.getElementById('currentDescription').style.display = 'none'; 
		document.getElementById('originalDescription').style.display = 'block'; 
	}
</script>
<div class="span-22 box last">
	<div class="span-18">		
		<?= '<strong>' . CHtml::link($need->username, $this->createUrl('user/' . $need->user_id)) . '</strong> ' . Yii::t('items', 'user needs'); ?>
	</div>
	<div class="span-4 last">
		<a onclick="showCurrent()"><?= Yii::t('item','current') ?></a>		
		&nbsp;
		<a onclick="showOriginal()"><?= Yii::t('item','original') ?></a>		
		&nbsp;
		<?= CHtml::link(Yii::t('item','edit'), $this->createUrl('need/edit/' . $need->id)) ?>
	</div>
	<div id="currentDescription" class="span-23">
		<?= '<strong>'. $need->description .'</strong>'?>
	</div>
	<div id="originalDescription" class="span-23" style="display: none">
		<?= $need->original_description ?>
	</div>
</div>
<div class="span-16">
	<?php foreach($solutions as $solution) { ?>
	<div class="span-15 box">
		<div class="span-12">
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
		<div class="span-14">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/block.png" alt="-" />
			<?= ' ' . $solutionItem['description'] ?>
		</div>
		<div class="span-1 last">
			<a href="<?= $this->createUrl('need/deleteSolutionItem/' . $solutionItem['id'] . '/' . $need->id) ?>" >
				<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/minus-button.png" alt="-" />
			</a>
		</div>
		<?php 
				} // foreach $solutionItem 
				if (!Yii::app()->user->isGuest)
				{
		?>
		<div class="span-15" >
			<div class="span-6">
				<a href="<?= $this->createUrl('need/addItem/' . $solution['id'] . '/' . $need->id) ?>?p=1" ><img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/plus-button.png" alt="-" /></a>
				<?php if (count($solution['items']) == 0 ) echo Yii::t('items', '< add items'); ?>
			</div>
			<div class="prepend-5 span-3">
				<?php  
					if($solution['status'] == 1) // Draft
						echo '<strong>';
					echo CHtml::link(Yii::t('items', 'draft'), $this->createUrl('need/draft/' . $solution['id'] . '/' . $need->id)) . '&nbsp;';
					if($solution['status'] == 1)
						echo '</strong>';
					if($solution['status'] == 2)
						echo '<strong>';
					echo CHtml::link(Yii::t('items', 'complete'), $this->createUrl('need/complete/' . $solution['id'] . '/' . $need->id)) . '&nbsp;';
					if($solution['status'] == 2)
						echo '</strong>';
					if ($need->user_id == Yii::app()->user->getState('user_id'))
						echo CHtml::link(Yii::t('items', 'take'), $this->createUrl('need/take/' . $solution['id'] . '/' . $need->id));
				?>
			</div>
		</div> 
		<?php } ?>
	</div>
	<?php } // foreach $solution ?>
	<?php if (isset($userId)) { ?>
	<div>
		<a href="<?= $this->createUrl('need/newSolution/' . $need->id) ?>" >
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/exclamation-button.png" alt="-" />
			<?= Yii::t('items','solution new') ?>
		</a>
	</div>
	<?php } ?>
</div>
<div class="span-7 last">
	<?php
		$userId = Yii::app()->user->getState('user_id');
		foreach($comments as $comment)
		{ ?>
	<div class="span-6">
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
	<div class="span-6 append-bottom">
		<?= $comment['comment'] ?>		
	</div>		
	<?php 
		} 
		if (!Yii::app()->user->isGuest)
		{
	?>
	<div class="span-6">
	<?php	
			echo CHtml::beginForm($this->createUrl('need/comment/'.$need->id)); 		
			echo CHtml::textArea('comment','',array(
				'class'=>'span-6',
				'rows'=>'5',
				));
			echo CHtml::submitButton(Yii::t('interaction','comment'), array('name'=>'comment_button'));
			echo CHtml::endForm(); 
	?>
	</div>	
	<?php } ?>
</div>
