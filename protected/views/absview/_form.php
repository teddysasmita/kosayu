<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesorders-form',
	'enableAjaxValidation'=>false,
      'action'=>Yii::app()->createUrl("salesorders/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesorders-form',
	'enableAjaxValidation'=>false,
      'action'=>Yii::app()->createUrl("salesorders/update", array('id'=>$model->id))
      ));
   ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

      <?php
         /*
          $idmaker=new idmaker();
         echo $form->hiddenfield($model,'id',array('value'=>$id));   
         echo $form->hiddenField($model,'userlog',array('value'=>Yii::app()->user->id));
         echo $form->hiddenField($model,'datetimelog',array('value'=>$idmaker->getDateTime()));
         */
         echo $form->hiddenfield($model,'id');   
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo $form->hiddenField($model,'status');
         
         echo CHtml::hiddenField('command');
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'Tanggal'); ?>
		<?php 
            //echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
               'name'=>'Salesorders[idatetime]',
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
		<?php echo $form->labelEx($model,'Nama Pelanggan'); ?>
		<?php 
            $res=Yii::app()->db->createCommand("select id, concat(firstname,' ',lastname) as name from customers")
               ->queryAll();
            $datas=CHtml::listData($res,'id', 'name');
            echo $form->dropDownList($model,'idcustomer',$datas); 
            ?>
		<?php echo $form->error($model,'idcustomer'); ?>
	</div>

      <?php 

if (isset(Yii::app()->session['Detailsalesorders'])) {
   $rawdata=Yii::app()->session['Detailsalesorders'];
   $count=count($rawdata);
} else {
   $count=Yii::app()->db->createCommand("select count(*) from detailsalesorders where id='$model->id'")->queryScalar();
   $sql="select * from detailsalesorders where id='$model->id'";
   $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
}
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
              'header'=>'Qty',
              'name'=>'qty',
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
              'deleteButtonUrl'=>"idmaker::decodeDeleteDetailSalesOrderUrl(\$data)",
              'viewButtonUrl'=>"idmaker::decodeViewDetailSalesOrderUrl(\$data)",
              'updateButtonUrl'=>"idmaker::decodeUpdateDetailSalesOrderUrl(\$data)"
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