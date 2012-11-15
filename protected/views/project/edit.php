<h1><?= Yii::t('project','project edit') ?></h1>
<div class="span-22 box">
<?php 
	$form = $this->beginWidget('CActiveForm', array(
		'id'=>'project-form',
		'enableAjaxValidation'=>true,
		'enableClientValidation'=>true,
			'action'=>$this->createUrl('project/edit/' . $model->id),
	)); 
?>
	<div class="row">
		<?= Yii::t('project','name'); ?>
			<?= CHtml::textField('project_name', $model->name, array('style'=>'width: 600px')) ?>
	</div>
	<div class="row">
		<?= Yii::t('project','description'); ?>
		<?= $form->textArea($model,'description', array('class' => 'span-22','rows'=>'10', 'maxlength'=>5000)); ?>
	</div>

	<div class="row submit">
			<?php echo CHtml::submitButton(Yii::t('project','save'), array('name'=>'save')); ?>
			<?php echo CHtml::submitButton(Yii::t('global','cancel'), array('name'=>'cancel')); ?>
	</div>
 
<?php $this->endWidget(); ?>
</div>

