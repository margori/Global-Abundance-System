<h1><?= Yii::t('project','project') ?></h1>
<div class="span-22 box last">
	<div class="span-18">		
		<?= '<strong>' . CHtml::link($project->username, $this->createUrl('user/' . $project->user_id)) . '</strong> ' . Yii::t('project', 'user projects'); ?>
		&nbsp;&nbsp;&nbsp;
		<?php if (Yii::app()->user->getState('user_id') == $project->user_id) { ?>
		<a href="<?= $this->createUrl('project/edit/' . $project->id) ?>"><img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/pencil.png" alt="-" /></a>
		<span id="deleteU<?= $project->id ?>" style="display: inline">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/cross-button.png" alt="-" 
					 onclick="toggle('deleteU<?= $project->id ?>');toggle('confirmationU<?= $project->id ?>');"/>
		</span>			 
		<span id="confirmationU<?= $project->id ?>" style="display: none">
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/slash-button.png" alt="N"
				onclick="toggle('deleteU<?= $project->id ?>');toggle('confirmationU<?= $project->id ?>');"/>
			<?= Yii::t('global', 'sure?') ?>
			<a href="<?= $this->createUrl('project/delete/' . $project->id) ?>" >
				<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/tick-button.png" alt="Y"/>
			</a>
		</span>
		<?php } ?>
	</div>
	<div id="currentDescription" class="span-22">
		<?= '<strong>'. $project->name . ':</strong> ' . $project->description ?>
	</div>
</div>
<?php if (count($needs) > 0) { ?>
<h3><?= Yii::t('global', 'needs') ?></h3>
<?php foreach($needs as $need) { ?>
<div class="box push-1 span-20 last">
	<?= CHtml::link($need['description'], $this->createUrl('need/view/' . $need['id'])) ?>
</div>	
<?php } } ?>
<?php if (Yii::app()->user->getState('user_id') == $project->user_id) { ?>
	<div class="push-1 span-21 append-bottom last">
		<a href="<?= $this->createUrl('need/new?project_id=' . $project->id) ?>" >
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/exclamation.png" alt="-" />
			<?= Yii::t('project','new need') ?>
		</a>
	</div>
<?php } ?>
<div class="clear"></div>
<?php if (count($shares) > 0) { ?>
<h3><?= Yii::t('global', 'shares') ?></h3>
<?php foreach($shares as $share) { ?>
<div class="box push-1 span-20 last">
	<?= CHtml::link($share['description'], $this->createUrl('share/view/' . $share['id'])) ?>
</div>	
<?php } } ?>
<?php if (Yii::app()->user->getState('user_id') == $project->user_id) { ?>
	<div class="push-1 span-21 append-bottom last">
		<a href="<?= $this->createUrl('share/new?project_id=' . $project->id) ?>" >
			<img src="<?= Yii::app()->baseUrl ?>/images/icons/16x16/exclamation.png" alt="-" />
			<?= Yii::t('project','new share') ?>
		</a>
	</div>
<?php } ?>
