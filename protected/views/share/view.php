<h1><?= Yii::t('items','share') ?></h1>
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
		<?= '<strong>' . CHtml::link($share->username, $this->createUrl('user/' . $share->user_id)) . '</strong> ' . Yii::t('items', 'user shares')
				.' ('. Yii::t('items', 'available').' '. $share->quantity .')'; ?>
	</div>
	<div class="span-4 last">
		<a onclick="toggle('currentDescription'); toggle('originalDescription');"><?= Yii::t('item','current') ?></a>		
		&nbsp;
		<a onclick="toggle('currentDescription'); toggle('originalDescription');"><?= Yii::t('item','original') ?></a>		
		&nbsp;
		<?= CHtml::link(Yii::t('item','edit'), $this->createUrl('share/edit/' . $share->id)) ?>
	</div>
	<div id="currentDescription" class="span-23">
		<?= '<strong>'. $share->description .'</strong>'?>
	</div>
	<div id="originalDescription" class="span-23" style="display: none">
		<?= $share->original_description ?>
	</div>
</div>
<div class="prepend-1 span-20 last">
	<?php
		$userId = Yii::app()->user->getState('user_id');
		foreach($comments as $comment)
		{ ?>
	<div class="span-20">
			<?= CHtml::link($comment['user_name'], $this->createUrl('user/' . $comment['user_id'])) . ' ' . Yii::t('interaction','comments'). ' ' ?>
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
