<?php
/* @var $this PaymentsController */
/* @var $model Payments */
/* @var $form CActiveForm */
?>

<div class="form">
<?php 
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'payments-form',
	'enableAjaxValidation'=>false,
      'action'=>Yii::app()->createUrl("payments/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'payments-form',
	'enableAjaxValidation'=>false,
      'action'=>Yii::app()->createUrl("payments/update", array('id'=>$model->id))
      ));
   ?>
   
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

      <?php
         echo $form->hiddenfield($model,'id');   
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo $form->hiddenField($model,'status');
         
         echo CHtml::hiddenField('command');
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'regnum'); ?>
		<?php echo $form->textField($model,'regnum',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'regnum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
		<?php echo $form->textField($model,'idatetime',array('size'=>19,'maxlength'=>19)); ?>
		<?php echo $form->error($model,'idatetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idsupplier'); ?>
		<?php echo $form->textField($model,'idsupplier',array('size'=>21,'maxlength'=>21)); ?>
		<?php echo $form->error($model,'idsupplier'); ?>
	</div>
      
            <?php 

if (isset(Yii::app()->session['Detailpayments'])) {
   $rawdata=Yii::app()->session['Detailpayments'];
   $count=count($rawdata);
} else {
   $count=Yii::app()->db->createCommand("select count(*) from detailpayments where id='$model->id'")->queryScalar();
   $sql="select * from detailpayments where id='$model->id'";
   $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
}
$dataProvider=new CArrayDataProvider($rawdata, array(
      'totalItemCount'=>$count,
));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
           array(
               'header'=>'Invoice Num',
               'name'=>'idinvoice',
               'value'=>"lookup::PurchasesInvoiceNumFromInvoiceID(\$data['idinvoice'])"
           ),
          array(
              'header'=>'Price',
              'name'=>'price',
          ),
          array(
              'header'=>'Disc',
              'name'=>'discount',
          ),
          array(
              'class'=>'CButtonColumn',
              'buttons'=> array(
                  'delete'=>array(
                      'visible'=>'false'
                  )
              ),
              'deleteButtonUrl'=>"lookup::decodeDeleteDetailPaymentUrl(\$data)",
              'viewButtonUrl'=>"lookup::decodeViewDetailPaymentUrl(\$data)",
              'updateButtonUrl'=>"lookup::decodeUpdateDetailPaymentUrl(\$data)"
          )
      ),
));
 ?>

	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total'); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo $form->textField($model,'discount'); ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->