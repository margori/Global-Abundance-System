<h1><?= Yii::t('global', 'register') ?></h1>
<?php 
	$form = $this->beginWidget('CActiveForm', array(
		'id'=>'register-form',
		'enableAjaxValidation'=>true,
		'enableClientValidation'=>true,
			'action'=>$this->createUrl('register/register#form'),
	)); 
?>
<?php 
	$count = 1;
	$s = Yii::t('register','clause'.$count);
	while($s != 'clause'.$count)
	{	
?>
<div class="span-22 box">
	<?= $count <= 2 ? '<b>'.$s.'</b>' : $s ?>
</div>
<?php	
		$count++;
		$s = Yii::t('register','clause'.$count);
	} ?>	

<div class="span-22 box form">
	<a name="form"></a>
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
	<div class="span-21" style="text-align: right;">
		<?= Yii::t('register','email') ?>&nbsp;
		<?= CHtml::textField('email', $model->email, array('class'=>'span-7 right', 'maxlength'=>50)) ?>
	</div>
	<div class="span-21" style="text-align: right;">
		<?php if (isset($message)) { ?>
			<span class="errorMessage">
				<?= $message ?>
			</span>
		<?php }?>
		<?php 
			echo CHtml::hiddenField('language', Yii::app()->language);			
			echo CHtml::submitButton(Yii::t('register','register'), array('name'=>'register')); 
		?>	
	</div>
<?php $this->endWidget(); ?>
</div>

