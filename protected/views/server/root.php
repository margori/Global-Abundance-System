<h1><?= Yii::t('server', 'install') ?></h1>
<h2><?= Yii::t('server', 'root') ?></h2>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('server/root')) ?>
	<p><?= Yii::t('server', 'explain') ?></p>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','username'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('username', '',array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','password'),false,array('class'=>'span-4')); ?>
		<?= CHtml::passwordField('password', '',array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= $message ?>
	</div>
	<div class="span-14">
		<?= CHtml::submitButton(Yii::t('server','continue'), array('name'=>'next')) ?>
	</div>
	<?php echo CHtml::endForm(); ?>
</div>
