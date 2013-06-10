<h1><?= Yii::t('server','local server install') ?></h1>
<?php echo CHtml::beginForm($this->createUrl('server/install')) ?>
<div class="span-22 box">
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('server','address') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('username', $model->username, array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','real name') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('realName', $model->realName, array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','email') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('email', $model->email, array('style'=>'width: 600px')) ?>
		</div>
	</div>
	<div class="prepend-1 span-21 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','language') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::dropDownList('language', $model->language, $languages) ?>
		</div>
	</div>
	<div class="prepend-1 span-21">
		<?= CHtml::submitButton(Yii::t('global','save'), array('name' => 'save')) ?>
		<?= CHtml::submitButton(Yii::t('global','cancel'), array('name' => 'cancel')) ?>
		<?php if (isset($messageSave)) { ?>
			<span class="errorMessage">
				<?= $messageSave ?>
			</span>
		<?php }?>
	</div>
</div>
<?php echo CHtml::endForm(); ?>
