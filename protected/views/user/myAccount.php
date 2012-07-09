<h1><?= Yii::t('user','my account') ?></h1>
<?php echo CHtml::beginForm($this->createUrl('user/save')) ?>
<div class="span-22 box">
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','username') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('username', $model->username, array('style'=>'width: 658px')) ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','password') ?>
		</div>
		<div class="span-16 last">
		<?= CHtml::passwordField('password', '', array('style'=>'width: 658px')) ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','confirmation') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::passwordField('confirmation', $model->confirmation, array('style'=>'width: 658px')) ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','real name') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('realName', $model->realName, array('style'=>'width: 658px')) ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','email') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('email', $model->email, array('style'=>'width: 658px')) ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','default tags') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::textField('defaultTags', $model->defaultTags, array('style'=>'width: 658px')) ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= Yii::t('user','language') ?>
		</div>
		<div class="span-16 last">
			<?= CHtml::dropDownList('language', $model->language, $languages) ?>
		</div>
	</div>
	<div class="span-22">
		<?= CHtml::submitButton(Yii::t('global','save'), array('name' => 'save')) ?>
		<?= CHtml::submitButton(Yii::t('global','cancel'), array('name' => 'cancel')) ?>
		<?php if (isset($message)) { ?>
			<span class="errorMessage">
				<?= $message ?>
			</span>
		<?php }?>
	</div>
</div>
<?php echo CHtml::endForm(); ?>
<div class="prepend-1 span-22 append-bottom">
	<?= Yii::t('user', 'delete account') ?>
	<span id="deleteU" style="display: inline">
		<img src="../../images/icons/16x16/cross-button.png" alt="-" 
				 onclick="toggle('deleteU');toggle('confirmationU');"/>
	</span>			 
	<span id="confirmationU" style="display: none">
		<img src="../../images/icons/16x16/slash-button.png" alt="N"
		  onclick="toggle('deleteU');toggle('confirmationU');"/>
		<?= Yii::t('global', 'sure?') ?>
		<a href="<?= $this->createUrl('user/delete/'.$model->id) ?>" >
			<img src="../../images/icons/16x16/tick-button.png" alt="Y"/>
		</a>
	</span>
</div>
