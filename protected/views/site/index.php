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
	<div >
		<table style="width: 90%; margin-left: auto; margin-right: auto;">
		<tbody>
			<tr>
				<td colspan="1" rowspan="2" style="text-align: center; ">
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/OccupyLove.mini.jpg'),
							sprintf('http://www.youtube.com/watch?hl=%s&v=4Z9WVZddH9w#t=90m05s', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							)); ?>					
				</td>
				<td colspan="1" rowspan="2" style="text-align: center; ">
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/project_earth.jpg'),
							sprintf('http://www.youtube.com/watch?hl=%s&v=4Z9WVZddH9w#t=90m05s', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							)); ?>					
				</td>
				<td >
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/TED_NicMarks.png'),
							sprintf('http://www.youtube.com/watch?hl=%s&v=4Z9WVZddH9w#t=90m05s', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							)); ?>
				</td>
			</tr>
			<tr>
				<td style="text-align: center; ">
					<?= CHtml::link(
							CHtml::image(Yii::app()->baseUrl . '/images/TED_PeterDiamandis.png'),
							sprintf('http://www.youtube.com/watch?hl=%s&v=4Z9WVZddH9w#t=90m05s', Yii::app()->user->getState('language')),
							array(
									'target'=>'_blank',
							));	?>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
	<div id="what" class="prepend-top span-23 last"><?= Yii::t('home', 'what?'); ?></div>
	<div id="register"  class="right last box"><a href="<?= Yii::app()->createUrl('register', array()) ?>"><?= Yii::t('home', 'register') ?></a></div>
</div>