<h1><?= Yii::t('global', 'register') ?></h1>
<?php 
	$form = $this->beginWidget('CActiveForm', array(
		'id'=>'register-form',
		'enableAjaxValidation'=>true,
		'enableClientValidation'=>true,
			'action'=>$this->createUrl('register/register'),
	)); 
?>
<?php 
	$count = 1;
	$s = Yii::t('register','clause'.$count);
	while($s != 'clause'.$count)
	{	
		$count++;
?>
<div class="span-22 box">
	<?= $s ?>
</div>
<?php	
		$s = Yii::t('register','clause'.$count);
	} ?>	

<div class="span-22 box form">
	<div class="span-2">
		<?= Yii::t('register','username') ?>
	</div>
	<div class="span-5">
		<?= CHtml::textField('username', $model->username, array('class'=>'span-5', 'maxlength'=>50)) ?>
	</div>
	<div class="span-2">
		<?= Yii::t('register','password') ?>
	</div>
	<div class="span-5">
	<?= CHtml::passwordField('password', '', array('class'=>'span-5', 'maxlength'=>50)) ?>
	</div>
	<div class="span-2">
		<?= Yii::t('register','confirmation') ?>	
	</div>
	<div class="span-5 last">
		<?= CHtml::passwordField('confirmation', $model->confirmation, array('class'=>'span-5', 'maxlength'=>50)) ?>
	</div>
	<div class="span-15">
			<?php echo CHtml::submitButton(Yii::t('register','register'), array('name'=>'register')); ?>
			<?php if (isset($message)) { ?>
				<span class="errorMessage">
					<?= $message ?>
				</span>
			<?php } ?>
	</div>
<?php $this->endWidget(); ?>
</div>

