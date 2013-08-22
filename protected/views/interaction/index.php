<script type="text/javascript">
	needCleaned = false;
	shareCleaned = false;
	
	function cleanNeed()
	{
		if (needCleaned)
			return;
		textArea = document.getElementById("needArea");
		textArea.value = "";
		textArea.style.color = 'black';
		needCleaned = true;
	}

	function cleanShare()
	{
		if (shareCleaned)
			return;
		textArea = document.getElementById("shareArea");
		textArea.value = "";
		textArea.style.color = 'black';
		shareCleaned = true;
	}
</script>
<div class="clear"></div>
<div class="span-9">
	<?= Yii::t('interaction','I need') . ':' ?>
	<img src="../images/icons/16x16/question-white.png" alt="?" title="<?= Yii::t('item','tag hint'); ?>"/>
	<br />
	<?php echo CHtml::beginForm($this->createUrl('interaction/submit')); ?>
		<?= CHtml::textArea('description',Yii::t('interaction','I need...'),array(
				'class'=>'span-9',
				'rows'=>'14',
				'maxlength'=>5000,
				'onfocus' => "cleanNeed()",
				'id' => 'needArea',
				'style' => 'color: grey',
				)) ?>
		<?= CHtml::hiddenField('shared','0') ?>
		<div class="span-7">
			<p>
				<?= Yii::t('interaction', 'for') ?>
				<?= CHtml::dropDownList('project', null, $projects, array('style' => 'width: 150px;')) ?>
				<br/>
				<a href="<?= Yii::app()->createUrl('need/new') ?>"><?= Yii::t('interaction','advanced') ?></a>
			</p>
		</div>
		<div class="right">
			<?php echo CHtml::submitButton(Yii::t('interaction','ask'), array('name'=>'save',
					'title'=>Yii::t('interaction', 'dont forget tags'))); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>
<div class="span-9">
	<?= Yii::t('interaction','I share') . ':' ?>
	<img src="../images/icons/16x16/question-white.png" alt="?" title="<?= Yii::t('item','tag hint'); ?>"/>
	<br />
	<?php echo CHtml::beginForm($this->createUrl('interaction/submit')); ?>
		<?= CHtml::textArea('description',Yii::t('interaction','I share...'),array(
				'class'=>'span-9',
				'rows'=>'14',
				'maxlength'=>5000,
				'onfocus' => "cleanShare()",
				'id' => 'shareArea',
				'style' => 'color: grey',
				)) ?>
		<?= CHtml::hiddenField('shared','1') ?>
		<div class="span-6">
			<p>
				<?= Yii::t('interaction', 'from') ?>
				<?= CHtml::dropDownList('project', null, $projects, array('style' => 'width: 150px;')) ?>
				<br />
				<a href="<?= Yii::app()->createUrl('share/new') ?>"><?= Yii::t('interaction','advanced') ?></a>
			</p>
		</div>
		<div class="right" style="text-align: right">
			<?= Yii::t('interaction','quantity'); ?>
			<?= CHtml::textField('quantity', 1, array('style'=>'width:20px', 'maxlength'=>'2')) ?>
			<br/>
			<?= CHtml::submitButton(Yii::t('interaction','share'), array('name'=>'save',
					'title'=>Yii::t('interaction', 'dont forget tags'))); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>
<div class="span-5 last">
	<br /><br /><br /><br />
	<a href="<?= Yii::app()->createUrl('./need') ?>"><?= Yii::t('interaction','solution') ?></a>
</div>