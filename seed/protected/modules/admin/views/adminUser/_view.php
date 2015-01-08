<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('adminId')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->adminId), array('view', 'id'=>$data->adminId)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('token')); ?>:</b>
	<?php echo CHtml::encode($data->token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endTime')); ?>:</b>
	<?php echo CHtml::encode($data->endTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deleteFlag')); ?>:</b>
	<?php echo CHtml::encode($data->deleteFlag); ?>
	<br />


</div>