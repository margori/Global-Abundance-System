<h1><?= Yii::t('item','share edit') ?></h1>
<script type="text/javascript" >
function toggle(id)	
	{
		element = document.getElementById(id);
		if (element.style.display == 'none')
			element.style.display = 'inline';
		else
			element.style.display = 'none';
	}
</script>
<div class="span-22 box">
<?php 
	$form = $this->beginWidget('CActiveForm', array(
		'id'=>'item-form',
		'enableAjaxValidation'=>true,
		'enableClientValidation'=>true,
			'action'=>$this->createUrl('share/edit/' . $model->id),
	)); 
?>
	<div class="row">
		<?= Yii::t('item','description'); ?>
		<img src="../../../images/icons/16x16/question-small-white.png" alt="?" 
				 onmouseover="toggle('descriptionHint')"
				 onmouseout="toggle('descriptionHint')"/>
		<span id="descriptionHint" style="display: none">
			<?= Yii::t('item','need description hint'); ?>
		</span>
		<?= $form->textArea($model,'description', array('class' => 'span-22','rows'=>'10', 'maxlength'=>5000)); ?>
	</div>
	<div class="row">
		<?= Yii::t('item','quantity'); ?>
		<?= $form->textField($model,'quantity'); ?>
		<img src="../../../images/icons/16x16/question-small-white.png" alt="?" onmouseover="toggle('quantityHint')"
				onmouseout="toggle('quantityHint')" />
		<span id="quantityHint" style="display: none">
			<?= Yii::t('item','quantity hint'); ?>
		</span>
	</div>
	<div class="row">
		<?= Yii::t('item','expiration date'); ?>
		<?= $form->textField($model,'expiration_date'); ?>
		<img src="../../../images/icons/16x16/question-small-white.png" alt="?" onmouseover="toggle('expirationHint')"
				 onmouseout="toggle('expirationHint')"/>
		<span id="expirationHint" style="display: none">
			<?= Yii::t('item','need expiration hint'); ?>
		</span>
	</div>
	<div class="row">
		<?= Yii::t('interaction', 'from') ?>
		<?= CHtml::dropDownList('project', $model->project_id, $projects) ?>
	</div>

	<div class="row submit">
			<?php echo CHtml::submitButton(Yii::t('item','save'), array('name'=>'save')); ?>
			<?php echo CHtml::submitButton(Yii::t('global','cancel'), array('name'=>'cancel')); ?>
	</div>
 
<?php $this->endWidget(); ?>
</div>

