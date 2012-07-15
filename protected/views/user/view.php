<?php
	$imagePadding = 'padding: 10px 6px 4px 6px;';
	$heartBroken = CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart-break.png');
	$heartEmpty = CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart-empty.png');
	$heartHalf = CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart-half.png');
	$heartFull = CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/heart.png', '', array('style'=>'margin-right: 6px;'));
?>
<h1><?= Yii::t('user','user') ?></h1>
<div class="span-22 box">
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','real name'), false); ?>:
		</div>
		<div class="span-14">
			<strong>
			<?php
				switch ($model->hisLove)
				{
					case 0: echo sprintf(Yii::t('user','you broke heart of'), $heartBroken , $model->realName ?: $model->username); break;
					case 3:echo sprintf(Yii::t('user','he loves you'), $model->realName ?: $model->username, $heartFull ); break;
				}
			?>
			</strong>
		</div>
		<div style="position: fixed; margin-left: 750px; margin-top: -20px; background-color: #fff; padding: 10px;">
			<?= CHtml::link($heartBroken, $this->createUrl('user/love/' . $model->id . '/0'), array(
				'title'=>Yii::t('user', 'user broke my heart'),
				'style'=>$imagePadding . ($model->myLove == 0 ? "background-color: #e5eCf9;" : ''),
			)) ?>
			<?= CHtml::link($heartEmpty, $this->createUrl('user/love/' . $model->id . '/1'), array(
				'title'=>Yii::t('user', 'I dont know this user'),
				'style'=>$imagePadding . ($model->myLove == 1 ? "background-color: #e5eCf9;" : ''),
			)) ?>
			<?= CHtml::link($heartHalf, $this->createUrl('user/love/' . $model->id . '/2'), array(
				'title'=>Yii::t('user', 'I like this user'),
				'style'=>$imagePadding . ($model->myLove == 2 ? "background-color: #e5eCf9;" : ''),
			)) ?>
			<?= CHtml::link($heartFull, $this->createUrl('user/love/' . $model->id . '/3'), array(
				'title'=>Yii::t('user', 'I love this user'),
				'style'=>$imagePadding . ($model->myLove == 3 ? "background-color: #e5eCf9;" : ''),
			)) ?>
		</div>
	</div>
	<div class="span-14 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','id'),false); ?>:
		</div>
		<div class="span-10 last">
			<?= $model->id ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','username'),false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->username ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','email'), false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->email ?>
		</div>
	</div>
	<div class="span-22">
		
	</div>
</div>
<?php if (count($needs) > 0) { ?>
<h3><?= Yii::t('global', 'needs') ?></h3>
<?php foreach($needs as $need) { ?>
<div class="box push-1 span-20 last">
	<?= CHtml::link($need['description'], $this->createUrl('need/view/' . $need['id'])) ?>
</div>	
<?php } ?>
<div class="clear"></div>
<?php } 
	if (count($shares) > 0) { 
?>
<h3><?= Yii::t('global', 'shares') ?></h3>
<?php foreach($shares as $share) { ?>
<div class="box push-1 span-20 last">
	<?= CHtml::link($share['description'], $this->createUrl('share/view/' . $share['id'])) ?>
</div>	
<?php } } ?>
