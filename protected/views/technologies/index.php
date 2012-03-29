<?php
$this->breadcrumbs=array(
	Yii::t('technologies', 'Technologies')=>array('index'),
	Yii::t('global', 'Browse'),
);

$this->menu=array(
	array('label'=>_('New technology'), 'url'=>array('new')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('technologies-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Yii::t('technologies', 'Technologies')</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'technologies-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'Name',
		'Description',
		array(
			'class'=>'CButtonColumn',
                        'template' => '{update} {delete}',
                        'updateButtonUrl' => 'Yii::app()->controller->createUrl("edit",array("id"=>$data->primaryKey))',
                        'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->primaryKey))'
		),
	),
)); ?>
