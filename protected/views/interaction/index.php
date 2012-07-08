<div class="clear"></div>
<div class="span-9">
	<?= Yii::t('interaction','I need:') ?>
	<img src="../images/icons/16x16/question-white.png" alt="?" title="<?= Yii::t('items','tag hint'); ?>"/>
	<br />
	<?php echo CHtml::beginForm($this->createUrl('interaction/submit')); ?>
		<?= CHtml::textArea('description',$defaultTags,array(
				'class'=>'span-9',
				'rows'=>'14',
				'maxlength'=>5000,
				)) ?>
		<?= CHtml::hiddenField('shared','0') ?>
		<div class="span-7">
			<p>
				<a href="<?= Yii::app()->createUrl('need/new') ?>"><?= Yii::t('interaction','advanced') ?></a><br/>
				<a href="<?= Yii::app()->createUrl('need/') ?>"><?= Yii::t('interaction','browse') ?></a>
			</p>
		</div>
		<div class="right">
			<?php echo CHtml::submitButton(Yii::t('interaction','ask'), array('name'=>'save',
					'title'=>Yii::t('interaction', 'dont forget tags'))); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>
<div class="span-9">
	<?= Yii::t('interaction','I give:') ?>
	<img src="../images/icons/16x16/question-white.png" alt="?" title="<?= Yii::t('items','tag hint'); ?>"/>
	<br />
	<?php echo CHtml::beginForm($this->createUrl('interaction/submit')); ?>
		<?= CHtml::textArea('description',$defaultTags,array(
				'class'=>'span-9',
				'rows'=>'14',
				'maxlength'=>5000,
				)) ?>
		<?= CHtml::hiddenField('shared','1') ?>
		<div class="span-4">
			<p>
				<a href="<?= Yii::app()->createUrl('share/new') ?>"><?= Yii::t('interaction','advanced') ?></a><br />
				<a href="<?= Yii::app()->createUrl('share/') ?>"><?= Yii::t('interaction','browse') ?></a>
			</p>
		</div>
		<div class="right">
			<?= Yii::t('items','quantity'); ?>
			<?= CHtml::textField('quantity', 1, array('style'=>'width:20px', 'maxlength'=>'2')) ?>
			<?= CHtml::submitButton(Yii::t('interaction','share'), array('name'=>'save',
					'title'=>Yii::t('interaction', 'dont forget tags'))); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>
<div class="span-5 last">
	<br /><br /><br /><br />
	<a href="<?= Yii::app()->createUrl('./need') ?>"><?= Yii::t('interaction','solution') ?></a>
</div>