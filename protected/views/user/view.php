<h1><?= Yii::t('user','user') ?></h1>
<div class="span-22 box">
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','real name'), false); ?>:
		</div>
		<div class="span-16 last">
			<strong><?= $model->realName ?></strong>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','id'),false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->id ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','username'),false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->username ?>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','email'), false); ?>:
		</div>
		<div class="span-16 last">
			<a href="mailto: <?= $model->email ?>"><?= $model->email ?></a>
		</div>
	</div>
	<div class="span-22 append-bottom">
		<div class="span-4">
			<?= CHtml::label(Yii::t('user','default tags'), false); ?>:
		</div>
		<div class="span-16 last">
			<?= $model->defaultTags ?>
		</div>
	</div>
</div>
