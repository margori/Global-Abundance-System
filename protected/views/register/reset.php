<h1><?= Yii::t('register', 'reset password') ?></h1>
<div class="prepend-4 span-16" style="text-align: center">
	<div class="box">
		<?= CHtml::beginForm($this->createUrl("register/reset/$code")) ?>
		<?= CHtml::label(Yii::t('register','new password'),false); ?>
		<?= CHtml::passwordField('password','',array('style'=>'width: 150px', 'maxlength'=>50)) ?>
		<?= CHtml::label(Yii::t('register','confirm password'),false); ?>
		<?= CHtml::passwordField('confirmation','',array('style'=>'width: 150px', 'maxlength'=>50)) ?>
		<br />
		<?= CHtml::submitButton(Yii::t('register','reset'), array('name'=>'reset')); ?>
		<?= $message != '' ? "<br /><span>$message</span>" : '' ?>
		<?= CHtml::endForm() ?>	
	</div>
</div>