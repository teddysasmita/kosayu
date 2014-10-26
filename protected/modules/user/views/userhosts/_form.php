<?php
/* @var $this UserhostsController */
/* @var $model Userhosts */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'userhosts-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php
            echo $form->hiddenField($model, 'userlog');
            echo $form->hiddenField($model, 'datetimelog');
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iduser'); ?>
		<?php 
                    $data=Yii::app()->authdb->createCommand()->select()->
                       from('users')->where('active=:active',array(':active'=>'1'))->queryAll();
                    $users=CHtml::listdata($data, 'id', 'fullname');
                    echo $form->dropDownList($model,'iduser',$users, array('empty'=>'Harap Pilih')); 
                ?>
		<?php echo $form->error($model,'iduser'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'allowedip'); ?>
		<?php echo $form->textField($model,'allowedip',array('size'=>21,'maxlength'=>21)); ?>
		<?php echo $form->error($model,'allowedip'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->