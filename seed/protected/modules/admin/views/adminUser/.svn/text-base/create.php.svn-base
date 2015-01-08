<?php
$this->breadcrumbs=array(
	'Admin Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AdminUser', 'url'=>array('index')),
	array('label'=>'Manage AdminUser', 'url'=>array('admin')),
);
?>

<h1>Create AdminUser</h1>

<?php
if(Yii::app()->user->name == 'admin')
echo $this->renderPartial('_form', array('model'=>$model));
?>