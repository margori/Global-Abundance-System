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
<?php if ($message != '') { ?>
<div class="span-16 prepend-2 last append-bottom">
	<?= $message ?>
</div>
<? } ?>
<div class="clear"></div>
<div class="span-9">
	<?= Yii::t('interaction','I need:') ?>
	<img src="../images/icons/16x16/question-small-white.png" alt="?" onmouseover="toggle('needHint')"
				onmouseout="toggle('needHint')"/>
	<span id="needHint" style="display: none">
		<?= Yii::t('items','tag hint'); ?>
	</span>
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
		<div class="span-1">
			<?php echo CHtml::submitButton(Yii::t('interaction','ask'), array('name'=>'save')); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>
<div class="span-9">
	<?= Yii::t('interaction','I give:') ?>
	<img src="../images/icons/16x16/question-small-white.png" alt="?" onmouseover="toggle('shareHint')"
				onmouseout="toggle('shareHint')"/>
	<span id="shareHint" style="display: none">
		<?= Yii::t('items','tag hint'); ?>
	</span>
	<br />
	<?php echo CHtml::beginForm($this->createUrl('interaction/submit')); ?>
		<?= CHtml::textArea('description',$defaultTags,array(
				'class'=>'span-9',
				'rows'=>'14',
				'maxlength'=>5000,
				)) ?>
		<?= CHtml::hiddenField('shared','1') ?>
		<div class="span-7">
			<p>
				<a href="<?= Yii::app()->createUrl('share/new') ?>"><?= Yii::t('interaction','advanced') ?></a><br />
				<a href="<?= Yii::app()->createUrl('share/') ?>"><?= Yii::t('interaction','browse') ?></a>
			</p>
		</div>
		<div class="span-1">
			<?php echo CHtml::submitButton(Yii::t('interaction','share'), array('name'=>'save')); ?>
		</div>
	<?php echo CHtml::endForm(); ?>
</div>
<div class="span-5 last">
	<br /><br /><br /><br />
	<a href="<?= Yii::app()->createUrl('./need') ?>"><?= Yii::t('interaction','solution') ?></a>
</div>