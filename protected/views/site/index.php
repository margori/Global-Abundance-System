<div id="home">
	<div id="welcome" class="span-23 last">
		<h2><?= Yii::t('home', 'welcome') ?></h2>
	</div>
	<div id="why" class="span-23 append-bottom last">
		<b><?= Yii::t('home', 'why?'); ?></b>
	</div>
	<div class="span-18 last">
		<?= Yii::t('home', 'how?') ?><br /><br />
	</div>
	<div class="prepend-1 span-9">
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/project_earth.jpg','',array(
									'style'=>'margin: 10px 0px 0px 20px'
							)),
							sprintf('http://www.youtube.com/watch?hl=%s&v=4Z9WVZddH9w#t=90m05s', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							)); ?>					
	</div>
	<div class="prepend-1 span-4">
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/TED_NicMarks.png'),
							sprintf('http://www.ted.com/talks/lang/%s/nic_marks_the_happy_planet_index.html', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							)); ?>
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/TED_PeterDiamandis.png','',array(
									'style'=>'margin: 10px 0px 0px 30px'
							)),
							sprintf('http://www.ted.com/talks/lang/%s/peter_diamandis_abundance_is_our_future.html', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							));	?>
	</div>
	<div class="prepend-1 span-6">
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/OccupyLove.mini.jpg','',array(
									'style'=>'margin: 40px 0px 0px 10px'
							)),
							sprintf('http://www.youtube.com/watch?hl=%s&v=ZQ00xcuN2hI', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							)); ?>					
	</div>
	<div id="what" class="prepend-top span-23 last"><?= Yii::t('home', 'what?'); ?></div>
	<div id="register"  class="right last box"><a href="<?= Yii::app()->createUrl('register', array()) ?>"><?= Yii::t('home', 'register') ?></a></div>
</div>