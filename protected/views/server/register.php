<h1><?= Yii::t('server', 'server register') ?></h1>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('server/register')) ?>
	<div class="span-20">
		<?= CHtml::label(Yii::t('server','url'),false,array('class'=>'span-2')); ?>
		<?= CHtml::textField('url', null, array('class'=>'span-17') ) ?>
	</div>
	<div class="span-14">
		<?= $message ?>
	</div>
	<div class="span-14">
		<?= CHtml::submitButton(Yii::t('global','save'), array('name'=>'save')) ?>
		<?= CHtml::submitButton(Yii::t('global','cancel'), array('name'=>'cancel')) ?>
	</div>
	<?php echo CHtml::endForm(); ?>
</div>
