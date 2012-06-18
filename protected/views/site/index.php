<div id="home">
	<div id="welcome" class="span-22 last"><h1><?= Yii::t('home', 'welcome') ?></h1></div>
	<div id="why" class="prepend-top span-18 last"><?= Yii::t('home', 'why?'); ?></div>
	<div id="how" class="prepend-top span-12 prepend-4 last">
		<?= Yii::t('home', 'how?') ?><br /><br />
		<a href="http://www.youtube.com/watch?hl=<?= Yii::app()->user->getState('language') ?>&v=4Z9WVZddH9w#t=90m05s" target="_blank" alt="YouTube"><img src="<?= Yii::app()->baseUrl . '/images/youtube.jpg'?>" /></a>
	</div>
	<div id="what" class="prepend-top span-18 prepend-4 last"><?= Yii::t('home', 'what?') ?></div>
	<div id="register"  class="span-5 prepend-18"><a href="<?= Yii::app()->createUrl('register', array()) ?>"><?= Yii::t('home', 'register') ?></a></div>
</div>