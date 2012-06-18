<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<title><?= Yii::t('global', 'title') ?></title>
	<script type="text/javascript">
		function toggle(id)	
		{
			element = document.getElementById(id);
			if (element.style.display == 'none')
				element.style.display = 'inline';
			else
				element.style.display = 'none';
		}
	</script>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div class="span-12">
			<div id="logo" >
				<a href="<?= Yii::app()->createUrl('site', array()) ?>"><?= Yii::t('global', 'title') ?></a>			
			</div>
			<div id="mainmenu" class="span-12">
				<ul>
					<li>
						<a href="<?= Yii::app()->createUrl('./interaction', array()) ?>"><?= Yii::t('global', 'home') ?></a>			
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('./need?p=1', array()) ?>"><?= Yii::t('global', 'needs') ?></a>			
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('./share?p=1', array()) ?>"><?= Yii::t('global', 'shares') ?></a>					
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('./user?p=1', array()) ?>"><?= Yii::t('global', 'users') ?></a>					
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('./archive?p=1', array()) ?>"><?= Yii::t('global', 'archive') ?></a>					
					</li>
				</ul>
			</div>
		</div>

		<div class="span-12 last">
			<?php if (Yii::app()->user->isGuest) { ?>
			<div class="right append-1">
				<?= CHtml::beginForm($this->createUrl('site/login')) ?>
				<?= CHtml::label(Yii::t('register','username'),false,array('class'=>'span-2')); ?>
				<?= CHtml::textField('username','',array('class'=>'span-2', 'maxlength'=>50)) ?>
				<?= CHtml::label(Yii::t('register','password'),false,array('class'=>'span-2')); ?>
				<?= CHtml::passwordField('password','',array('class'=>'span-2', 'maxlength'=>50)) ?>
				<?= CHtml::submitButton(Yii::t('global','Login')); ?>
				<?= CHtml::endForm() ?>
			</div>
			<?php } else { ?>
			<div class="right append-1">
					<?= Yii::t('global','welcome') ?> <a href="<?= $this->createUrl('user/myAccount') ?>"><?= Yii::app()->user->getName() ?></a>
					<a href="<?= $this->createUrl('./need?o=mine') ?>"><?= Yii::t('items','my needs') ?></a>					
					<a href="<?= $this->createUrl('./share?o=mine') ?>"><?= Yii::t('items','my shares') ?></a>					
			</div>
			<?php } ?>
		</div>
		<div class="span-12 last">
			<div class="span-8 last">
				<div id="languages" class="right" style="display: none" >
					<?php foreach (Yii::app()->params['languages'] as $iso => $language)
							echo CHtml::link($language, Yii::app()->createUrl($iso)) . ' '; ?>
				</div>
			</div>
			<div class="right append-1" >
				<span onclick="toggle('languages')">
					<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/locale.png" />
					&nbsp;
				</span>
				<a href="<?= Yii::app()->user->isGuest ? Yii::app()->createUrl('register', array()) : Yii::app()->createUrl('site/logout', array()) ?>">
					<?= Yii::app()->user->isGuest ? Yii::t('global', 'register') : Yii::t('global', 'logout') ?>
				</a> 
			</div>
		</div>	
	</div><!-- header -->
	
	<div class="clear"></div>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		<div class="span-7">
			<?= Yii::t('global','terms') ?>
		</div>
		<div class="span-7 prepend-2 append-bottom">
			Copyleft &copy; <?php echo date('Y'); ?> by <a href="http://www.margori.com.ar/" target="_blank" >Margori</a>.<br/>
			All Wrongs Reserved.<br/>
			<?= Yii::t('global', 'powered by') ?>
			<a href="http://www.yiiframework.com/" target="_blank">Yii framework</a>
			<?= Yii::t('global', 'and love') ?>
		</div>
		<div class="span-4 prepend-2 last">
			<p>
				<a target="_blank" href="<?= Yii::app()->params['development url'] ?>"><?= Yii::t('global', 'development') ?></a>
				<a target="_blank"href="<?= Yii::app()->params['blog url'] ?>">Blog</a>
				<a href="mailto:<?= Yii::app()->params['contact email'] ?>"><?= Yii::t('global', 'contact') ?></a>
			</p>
		</div>
	</div><!-- footer -->

	<div class="clear"></div>
</div><!-- page -->

</body>
</html>
