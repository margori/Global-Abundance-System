<?php
$this->breadcrumbs=array(
	'Technologies'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>Yii::t('global','Browse'), 'url'=>array('index')),
);
?>

<h1><?= Yii::t('technologies', 'Title new') ?>New</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>