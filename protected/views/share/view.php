<h1><?= Yii::t('item','share') ?></h1>
<script type="text/javascript">
function toggleN(id)	
	{
		element = document.getElementById('delete' + id);
		if (element.style.display == 'none')
			element.style.display = 'inline';
		else
			element.style.display = 'none';

	element = document.getElementById('confirmation' + id);
		if (element.style.display == 'none')
			element.style.display = 'inline';
		else
			element.style.display = 'none';
	}
</script>
<div class="span-22 box last">
	<div class="span-18">		
		<?= '<strong>' . CHtml::link($share->username, $this->createUrl('user/' . $share->user_id)) . '</strong> ' . Yii::t('item', 'user shares')
				.' ('. Yii::t('item', 'available').' '. $share->quantity .')'; ?>
		&nbsp;&nbsp;&nbsp;
		<?php if (!Yii::app()->user->isGuest) { ?>
		<a href="<?= $this->createUrl('share/edit/' . $share->id) ?>"><img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/pencil.png" alt="-" /></a>
		<?php } ?>
		<?php if (Yii::app()->user->getState('user_id') == $share->user_id) { ?>
		<span id="deleteU<?= $share->id ?>" style="display: inline">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/cross-button.png" alt="-" 
					 onclick="toggle('deleteU<?= $share->id ?>');toggle('confirmationU<?= $share->id ?>');"/>
		</span>			 
		<span id="confirmationU<?= $share->id ?>" style="display: none">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/slash-button.png" alt="N"
				onclick="toggle('deleteU<?= $share->id ?>');toggle('confirmationU<?= $share->id ?>');"/>
			<?= Yii::t('global', 'sure?') ?>
			<a href="<?= $this->createUrl('share/delete/' . $share->id) ?>" >
				<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/tick-button.png" alt="Y"/>
			</a>
		</span>
		<?php } ?>
	</div>
	<div id="currentDescription" class="span-22">
		<?= '<strong>'. $share->description .'</strong>'?>
	</div>
	<div class="right last" onclick="toggle('originalDescription');"><?= Yii::t('item','original') ?></div>		
	<div id="originalDescription" class="span-22" style="display: none">
		<?= $share->original_description ?>
	</div>
</div>
<?php if (isset($userId)) { ?>
<div class="clear push-1">
	<a href="<?= $this->createUrl('share/completeSolution/' . $share->id) ?>" ><img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/exclamation-shield.png" alt="-" /> <?= Yii::t('item','solution from share') ?>
	</a>
</div>
<?php } ?>
<div class="prepend-1 span-20 last">
	<?php
		$userId = Yii::app()->user->getState('user_id');
		foreach($comments as $comment)
		{ ?>
	<div class="span-20">
			<?= CHtml::link($comment['user_name'], $this->createUrl('user/' . $comment['user_id'])) . ' ' . Yii::t('item','comments'). ' ' ?>
		<?php if ($comment['user_id'] == $userId) { ?>
			<span id="deleteC<?= $comment['id'] ?>" style="display: inline">
				<img src="../../../images/icons/16x16/cross-button.png" alt="-" onclick="toggleN('C<?= $comment['id'] ?>');"/>
			</span>			 
			<span id="confirmationC<?= $comment['id'] ?>" style="display: none">
				<img src="../../../images/icons/16x16/slash-button.png" alt="N"
						  onclick="toggleN('C<?= $comment['id'] ?>');"/>
				<?= Yii::t('global', 'sure?') ?>
				<a href="<?= $this->createUrl('share/deleteComment/'.$comment['id'].'/'.$share->id) ?>" >
					<img src="../../../images/icons/16x16/tick-button.png" alt="Y"/>
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
	<div class="span-22">
	<?php	
			echo CHtml::beginForm($this->createUrl('share/comment/'.$share->id)); 		
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
