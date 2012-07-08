<h1><?= Yii::t('user','user') ?></h1>
<div class="span-22 box">
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','real name'), false); ?>:
		</div>
		<div class="span-16 last">
			<strong><?= $model->realName ?></strong>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','id'),false); ?>:
		</div>
		<div class="span-16 last">
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
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','default tags'), false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->defaultTags ?>
		</div>
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
