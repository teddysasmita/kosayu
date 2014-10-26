<?php
/* @var $this SalesinvoicesController */
/* @var $model Salesinvoices */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesinvoices-form',
	'enableAjaxValidation'=>false,
      'action'=>Yii::app()->createUrl("salesinvoices/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesinvoices-form',
	'enableAjaxValidation'=>false,
      'action'=>Yii::app()->createUrl("salesinvoices/update", array('id'=>$model->id))
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
		<?php echo $form->labelEx($model,'Tanggal'); ?>
		<?php 
            //echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
               'name'=>'Salesinvoices[idatetime]',
                  // additional javascript options for the date picker plugin
               'options'=>array(
                  'showAnim'=>'fold',
                  'dateFormat'=>'yy/mm/dd',
                  'defaultdate'=>$model->idatetime
               ),
               'htmlOptions'=>array(
                  'style'=>'height:20px;',
               ),
               'value'=>$model->idatetime,
            ));
            ?> 
		<?php echo $form->error($model,'idatetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idcustomer'); ?>
		<?php 
            $res=Yii::app()->db->createCommand("select id, concat(firstname,' ',lastname) as name from customers ".
               "order by firstname")
               ->queryAll();
            $datas=CHtml::listData($res,'id', 'name');
            $datas['-']='Harap Pilih';
            echo $form->dropDownList($model,'idcustomer',$datas, array('id'=>'idcustomer')); 
            ?>
            <?php echo $form->error($model,'idcustomer'); ?>
	</div>
      <div class="row">
		<?php echo $form->labelEx($model,'idinvoice'); ?>
		<?php 
            $res=Yii::app()->db->createCommand("select id, regnum from salesorders where idcustomer='$model->idcustomer'")
               ->queryAll();
            $datas=CHtml::listData($res,'id', 'regnum');
            $datas['-']='Harap Pilih';
            echo $form->dropDownList($model,'idinvoice',$datas, array('id'=>'idinvoice')); 
            ?>
            <?php echo $form->error($model,'idinvoice'); ?>
	</div>

<?php 

if (isset(Yii::app()->session['Detailsalesinvoices'])) {
   $rawdata=Yii::app()->session['Detailsalesinvoices'];
   $count=count($rawdata);
} else if (isset(Yii::app()->session['master'])){
   $count=Yii::app()->db->createCommand("select count(*) from detailsalesinvoices where id='$model->id'")->queryScalar();
   $sql="select * from detailsalesinvoices where id='$model->id'";
   $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
};
   
$dataProvider=new CArrayDataProvider($rawdata, array(
      'totalItemCount'=>$count,
));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
           array(
               'header'=>'Item Name',
               'name'=>'iditem',
               'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
           ),
          array(
              'header'=>'Sisa',
              'name'=>'lqty',
          ),
          array(
              'header'=>'Qty',
              'name'=>'qty',
          ),
          array(
              'header'=>'Price',
              'name'=>'price',
              'type'=>'number',
          ),
          array(
              'header'=>'Disc',
              'name'=>'discount',
              'type'=>'number',
          ),
          array(
              'class'=>'CButtonColumn',
              'buttons'=> array(
                  'delete'=>array(
                      'visible'=>'false'
                  )
              ),
              'deleteButtonUrl'=>"lookup::decodeDeleteDetailSalesInvoiceUrl(\$data)",
              'viewButtonUrl'=>"lookup::decodeViewDetailSalesInvoiceUrl(\$data)",
              'updateButtonUrl'=>"lookup::decodeUpdateDetailSalesInvoiceUrl(\$data)"
          )
      ),
));
 ?>      
         
	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo CHtml::label(number_format($model->total),'false',
                    array('class'=>'money')); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo CHtml::label(number_format($model->discount),'false', 
                    array('class'=>'money')); ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->