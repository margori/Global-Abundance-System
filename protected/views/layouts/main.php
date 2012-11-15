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
	<link rel="stylesheet" type="text/css" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" />
	<title><?= Yii::t('global', 'title') ?></title>
	<link rel="shortcut icon" href="<?= Yii::app()->baseUrl ?>/images/favicon.ico" />
	<script type="text/javascript">
		function toggle(id)	
		{
			element = document.getElementById(id);
			if (element.style.display == 'none')
				element.style.display = 'inline';
			else
				element.style.display = 'none';
		}
		function show(id)	
		{
			element = document.getElementById(id);
			element.style.display = 'inline';
		}
		function hide(id)	
		{
			element = document.getElementById(id);
			element.style.display = 'none';
		}
		
	</script>
</head>

	<body >
<div class="container" id="page">
	<div id="header">
		<div class="span-15 last">
			<div id="logo" >
				<a href="<?= Yii::app()->createUrl('site', array()) ?>" ><?php 
					if (Yii::app()->params['custom title'] == '')
						echo Yii::t('global', 'title');
					else
						echo Yii::app()->params['custom title']; ?></a>	<span style="
    position: absolute;
    margin-top: -6px;
    margin-left: 3px;
    font-size: 18px; "><?= Yii::t('global', 'subtitle') ?></span>		
			</div>
			<div id="mainmenu" class="span-24">
				<ul>
					<?php if (!Yii::app()->user->isGuest) { ?>
					<li>
						<?= CHtml::link(Yii::t('global', 'interaction'), Yii::app()->createUrl('./interaction'),array('title'=>Yii::t('global', 'interaction hint'))) ?>
					</li>
					<?php } ?>
					<li>
						<?= CHtml::link(Yii::t('global', 'needs'), Yii::app()->createUrl('./need?p=1&o='),array('title'=>Yii::t('global', 'needs hint'))) ?>
					</li>
					<li>
						<?= CHtml::link(Yii::t('global', 'shares'), Yii::app()->createUrl('./share?p=1&o='),array('title'=>Yii::t('global', 'shares hint'))) ?>						
					</li>
					<li>
						<?= CHtml::link(Yii::t('global', 'projects'), Yii::app()->createUrl('./project?p=1&o='),array('title'=>Yii::t('global', 'projects hint'))) ?>						
					</li>
					<?php
						if (!Yii::app()->user->isGuest) {
					?>
					<li>
						<?= CHtml::link(Yii::t('item', 'my needs'), Yii::app()->createUrl('./need?o=mine'),array('title'=>Yii::t('global', 'my needs hint'))) ?>
					</li>
					<li>
						<?= CHtml::link(Yii::t('item', 'my shares'), Yii::app()->createUrl('./share?o=mine'),array('title'=>Yii::t('global', 'my shares hint'))) ?>
					</li>
					<li>
						<?= CHtml::link(Yii::t('project', 'my projects'), Yii::app()->createUrl('./project?o=mine'),array('title'=>Yii::t('global', 'my projects hint'))) ?>
					</li>
					<?php
							$comments = UserForm::newComments();
							if (count($comments) > 0) {
					?>
					<li onclick="toggle('newComments');hide('newSolutions');">
						<div id="newComments" class="popup" style="	display: none;position: fixed; margin-top: 20px; padding: 5px;">
							<?php
								foreach($comments as $comment)
									echo '<div style="padding: 5px;">' . CHtml::link(
										$comment['user_name'] . ' ' . 
										Yii::t('item', 'made a comment'),
										($comment['shared'] == 0 ?	
											Yii::app()->createUrl('need/view/' . $comment['item_id']):
											Yii::app()->createUrl('share/view/' . $comment['item_id'])
										),
										array( 'style'=>"color: #000;font-weight: normal; padding: 5px;")
										) . '</div>';
							?>
						</div>
						<?php
							echo CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/balloon.png','',array(
								'style'=>' margin-bottom: -4px;',
								));
							echo CHtml::image(Yii::app()->baseUrl . "/images/icons/16x16/exclamation-small.png", '', array(
								'style'=>'margin: -4px 0px 0px -12px; position: absolute;'
								));
						?>					
					</li>
					<?php 						
							} 
							$solutions = UserForm::newSolutions();
							if (count($solutions) > 0) {
					?>				
					<li onclick="toggle('newSolutions');hide('newComments');">
						<div id="newSolutions" class="popup" style="display: none;position: fixed; margin-top: 20px; padding: 5px;">
							<?php
								foreach($solutions as $solution)
									echo '<div style="padding: 5px;">'.CHtml::link(
										$solution['user_name'] . ' ' . Yii::t('item', 'completed a solution'),
										Yii::app()->createUrl('need/view/' . $solution['item_id'])
										, array( 'style'=>"color: #000;font-weight: normal; padding: 5px;")
										) . '</div>';
							?>
						</div>
						<?= CHtml::image(Yii::app()->baseUrl . '/images/icons/16x16/light-bulb--exclamation.png','',array(
								'style'=>' margin-bottom: -4px;',
								)) ?>
					</li>
					<?php } } ?>
					<li style="float: right; margin-top: 1px;">
						<?= CHtml::link(Yii::t('global', 'users'), Yii::app()->createUrl('./user?p=1'),array('title'=>Yii::t('global', 'users hint'))) ?>
					</li>
					<li style="float: right;margin-top: 1px;">
						<?= CHtml::link(Yii::t('global', 'archive'), Yii::app()->createUrl('./archive?p=1'),array('title'=>Yii::t('global', 'archive hint'))) ?>
					</li>
				</ul>
			</div>
		</div>

		<div class="right" style="margin: <?= Yii::app()->user->isGuest ? '0' : '5px' ?> 5px 0 0; text-align: right;">
			<div>
			<?php if (Yii::app()->user->isGuest) { ?>
				<?= CHtml::beginForm($this->createUrl('site/login')) ?>
				<?= CHtml::label(Yii::t('register','username'),false); ?>
				<?= CHtml::textField('username','',array('style'=>'width: 70px', 'maxlength'=>50)) ?>
				<?= CHtml::label(Yii::t('register','password'),false); ?>
				<?= CHtml::passwordField('password','',array('style'=>'width: 70px', 'maxlength'=>50)) ?>
				<?= CHtml::submitButton(Yii::t('global','Login')); ?>
				<?= CHtml::endForm() ?>
			<?php } else { ?>
				<?= Yii::t('global','welcome') ?> <a href="<?= $this->createUrl('user/myAccount') ?>"><?= Yii::app()->user->getState('user_real_name'); ?></a>
				<?php	if (!Yii::app()->user->getState('user_email'))
						echo CHtml::link (CHtml::image( Yii::app()->baseUrl . "/images/icons/16x16/exclamation-small.png"
							, '', array('title'=>Yii::t('user', 'suggest email'), 'style'=>'margin: -4px;')), $this->createUrl('user/myAccount') );
				?>
				<a href="<?= Yii::app()->createUrl('site/logout', array()) ?>">
				<?= Yii::t('global', 'logout') ?></a>
			</div>
			<?php } ?>
			<div>					
			<?php if (Yii::app()->user->isGuest) { ?>
				<a href="<?= Yii::app()->createUrl('register', array()) ?>">	<?= Yii::t('global', 'register') ?></a>
				<a href="<?= Yii::app()->createUrl('register/forgot', array()) ?>">	<?= Yii::t('register', 'forgot') ?></a>
			<?php } ?>
				<span onclick="toggle('languages')">
					<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/locale-alternate.png" />
					<?php
						$currentLanguage = Yii::app()->language;
						foreach (Yii::app()->params['languages'] as $iso => $language) 
						{
							if ($iso == $currentLanguage)
								echo $language;
						}
					?>						
						<div style="display: inline;">		
										<div id="languages" class="popup"  style="text-align: left; width: 90px; display: none; position: fixed; margin-left: -110px; margin-top: 1.5em; ">
					<?php 
						foreach (Yii::app()->params['languages'] as $iso => $language) 
							echo CHtml::link($language, Yii::app()->createUrl('site/language/'.$iso)) . '<br />'; 
					?>
				</div>
						</span></div>
			</div>
		</div>	
	</div><!-- header -->
	
	<div class="clear"></div>

	<?php echo $content; ?>

	<div class="clear"></div>
	<div id="footer">
		<div>
			<?= sprintf(Yii::t('global','terms'), Yii::app()->createUrl('site/suggestions')) ?>
		</div>
		<div>
			<?= CHtml::link('Copyleft', Yii::app()->createUrl('../LICENSE')) ?> &copy; <?php echo date('Y'); ?> by <a href="http://www.margori.com.ar/" target="_blank" >Margori</a>
			<?= CHtml::link(Yii::t('global', 'and many more'), Yii::app()->createUrl('../CREDITS')) ?>.
			<?= Yii::t('global', 'all wrongs reserved') ?>.
		</div>
		<div>
			<?= sprintf(Yii::t('global', 'powered by'),
							CHtml::link('Yii framework', 'http://www.yiiframework.com/', array('target'=>'_blank')),
							CHtml::link(Yii::app()->params['host name'], Yii::app()->params['host url'], array('target'=>'_blank')),
							Yii::app()->createUrl('site/love')
							) ?>
		</div>
		<div>
			<?php if (Yii::app()->user->getState('user_id') == Yii::app()->params['root user id']) { ?>
				<a href="<?= Yii::app()->createUrl('site/backup') ?>">BackUp</a>
			<?php } ?>
			<a target="_blank" href="<?= Yii::app()->createUrl('../source.zip') ?>"><?= Yii::t('global', 'source') ?></a>
			<a target="_blank" href="<?= Yii::app()->params['development url'] ?>"><?= Yii::t('global', 'development') ?></a>
			<a target="_blank"href="<?= Yii::app()->params['blog url'] ?>">Blog</a>
			<a href="mailto:<?= Yii::app()->params['contact email'] ?>"><?= Yii::t('global', 'contact') ?></a>
		</div>
	</div><!-- footer -->

	<div class="clear"></div>
</div><!-- page -->

</body>
</html>
