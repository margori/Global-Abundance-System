<h1><?= Yii::t('server', 'configuration') ?></h1>
<div class="span-22 box">
	<?php echo CHtml::beginForm($this->createUrl('server/configure')) ?>
	<div class="span-20">
		<?= CHtml::label(Yii::t('server','app_title'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('app_title', $configuration->app_title,array('class'=>'span-8') ) ?>
		<?= Yii::t('server','also server name') ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','default_language'),false,array('class'=>'span-4')); ?>
		<?= CHtml::dropDownList('default_language', $configuration->default_language, $languages) ?>
	</div>
	<div class="span-20">
		<?= CHtml::label(Yii::t('server','development_url'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('development_url', $configuration->development_url,array('class'=>'span-14') ) ?>
	</div>
	<div class="span-20">
		<?= CHtml::label(Yii::t('server','blog_url'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('blog_url', $configuration->blog_url,array('class'=>'span-14') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','contact_email'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('contact_email', $configuration->contact_email,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','host_name'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('host_name', $configuration->host_name,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','host_url'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('host_url', $configuration->host_url,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','send_emails'),false,array('class'=>'span-4')); ?>
		<?= CHtml::checkBox('send_emails', $configuration->send_emails ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','include_title_in_email'),false,array('class'=>'span-4')); ?>
		<?= CHtml::checkBox('include_title_in_email', $configuration->include_title_in_email) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','smtp_server'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('smtp_server', $configuration->smtp_server,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','stmp_port'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('stmp_port', $configuration->stmp_port,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','smtp_username'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('smtp_username', $configuration->smtp_username,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','smtp_password'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('smtp_password', $configuration->smtp_password,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','smtp_secure'),false,array('class'=>'span-4')); ?>
		<?= CHtml::dropDownList('smtp_secure', $configuration->smtp_secure,array('','ssl','tls'),array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','smtp_timeout'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('smtp_timeout', $configuration->smtp_timeout,array('','ssl','tls'),array('class'=>'span-8') ) ?>
		<?= Yii::t('server','seconds') ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','smtp_from_email'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('smtp_from_email', $configuration->smtp_from_email,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','smtp_from_name'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('smtp_from_name', $configuration->smtp_from_name,array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','default_latitude'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('default_latitude', $configuration->default_latitude,array('','ssl','tls'),array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= CHtml::label(Yii::t('server','default_longitude'),false,array('class'=>'span-4')); ?>
		<?= CHtml::textField('default_longitude', $configuration->default_longitude,array('','ssl','tls'),array('class'=>'span-8') ) ?>
	</div>
	<div class="span-14">
		<?= $message ?>
	</div>
	<div class="span-14">
		<?= CHtml::submitButton(Yii::t('global','save'), array('name'=>'save')) ?>
		<?= CHtml::submitButton(Yii::t('global','cancel'), array('name'=>'cancel')) ?>
	</div>
	<?php echo CHtml::endForm(); ?>
</div>
