<?php
$this->breadcrumbs=array(
	'Technologies'=>array('index'),
	$model->Name,
);

$this->menu=array(
	array('label'=>Yii::t('global', 'Browse'), 'url'=>array('browse')),
	array('label'=>Yii::t('global', 'New'), 'url'=>array('create')),
	array('label'=>Yii::t('global', 'Edit'), 'url'=>array('update', 'id'=>$model->TechnologyId)),
	array('label'=>Yii::t('global', 'Delete'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->TechnologyId),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Technologies #<?php echo $model->TechnologyId; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(		
		'Name'=>CHtml::link(CHtml::encode($model->Name),
                                 array('technologies/view','id'=>$model->TechnologyId)),
		'Description',
	),
)); ?>
