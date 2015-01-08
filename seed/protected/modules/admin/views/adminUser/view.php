<?php
$this->breadcrumbs=array(
	'Admin Users'=>array('index'),
	$model->adminId,
);

$this->menu=array(
	array('label'=>'List AdminUser', 'url'=>array('index')),
	array('label'=>'Create AdminUser', 'url'=>array('create')),
	array('label'=>'Update AdminUser', 'url'=>array('update', 'id'=>$model->adminId)),
	array('label'=>'Delete AdminUser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->adminId),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AdminUser', 'url'=>array('admin')),
);
?>

<h1>View AdminUser #<?php echo $model->adminId; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'adminId',
		'username',
		'password',
		'token',
		'endTime',
		'deleteFlag',
	),
)); ?>
