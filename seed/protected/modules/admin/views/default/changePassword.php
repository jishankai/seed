<?php
//$this->pageTitle=Yii::app()->name . ' - Change Password';
//$this->breadcrumbs=array(
//	'Change Password',
//);
?>

<h1>Change Password</h1>

<p>Your username is: <?php echo Yii::app()->user->name;?></p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'oldPassword'); ?>
		<?php echo $form->passwordField($model,'oldPassword'); ?>
		<?php echo $form->error($model,'oldPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newPassword'); ?>
		<?php echo $form->passwordField($model,'newPassword'); ?>
		<?php echo $form->error($model,'newPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newPasswordAgain'); ?>
		<?php echo $form->passwordField($model,'newPasswordAgain'); ?>
		<?php echo $form->error($model,'newPasswordAgain'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
