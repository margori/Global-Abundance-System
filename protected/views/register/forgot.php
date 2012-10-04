<h1><?= Yii::t('register', 'forgot') ?></h1>
<div class="prepend-4 span-16" style="text-align: center">
	<div class="box">
		<?= CHtml::beginForm($this->createUrl('register/forgot')) ?>
		<?= CHtml::label(Yii::t('register','your email'),false); ?>
		<?= CHtml::textField('email','',array('style'=>'width: 200px', 'maxlength'=>50)) ?>
		<?= CHtml::submitButton(Yii::t('register','send mail'), array('name'=>'remember')); ?>
		<?= $message != '' ? "<br /><span>$message</span>" : '' ?>
		<?= CHtml::endForm() ?>	
	</div>
</div>