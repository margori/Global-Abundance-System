<?php
$this->breadcrumbs=array(
	'Technologies'=>array('index'),
	$model->Name=>array('view','id'=>$model->TechnologyId),
	'Update',
);

$this->menu=array(
	array('label'=>'Browse', 'url'=>array('index')),
	array('label'=>'New', 'url'=>array('create')),
);
?>

<h1>Update Technologies <?php echo $model->TechnologyId; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>