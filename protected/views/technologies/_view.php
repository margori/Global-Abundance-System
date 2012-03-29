<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('TechnologyId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->TechnologyId), array('view', 'id'=>$data->TechnologyId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Name')); ?>:</b>
	<?php echo CHtml::encode($data->Name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Description')); ?>:</b>
	<?php echo CHtml::encode($data->Description); ?>
	<br />


</div>