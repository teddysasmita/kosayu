<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php 
   
   $customers=Yii::app()->db->createCommand()
      ->select('id, firstname, lastname')
      ->from('customers')
      ->order('firstname, lastname')
      ->queryAll();
   foreach($customers as $row) {
      $customerids[]=$row['id'];
      $customernames[]=$row['firstname'].' '.$row['lastname'];
   }
   $customerids=CJSON::encode($customerids);
   $customernames=CJSON::encode($customernames);
   $customerScript=<<<EOS
      var customerids=$customerids;
      var customernames=$customernames;
      $('#Salesorders_customername').change(function() {
         var activename=$('#Salesorders_customername').val();
         $('#Salesorders_idcustomer').val(
            customerids[customernames.indexOf(activename)]);
      });
EOS;
   Yii::app()->clientScript->registerScript("customerScript", $customerScript, CClientscript::POS_READY);
   
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesorders-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/salesorder/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesorders-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/salesorder/default/update", array('id'=>$model->id))
      ));
   ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

      <?php
         echo $form->hiddenfield($model,'id');   
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo $form->hiddenField($model,'status');
         echo $form->hiddenField($model,'idcustomer');
         echo $form->hiddenField($model,'regnum');
         
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
         <?php echo $form->labelEx($model,'idcustomer'); ?>
         <?php 
            $customers=Yii::app()->db->createCommand()
               ->select("id,firstname,lastname")
               ->from("customers")
               ->order("firstname, lastname")   
               ->queryAll();
            foreach($customers as $row) {
               $customername[]=$row['firstname'].' '.$row['lastname'];
            }
            $this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Salesorders_customername',
                'source'=>$customername,
              'value'=>lookup::CustomerNameFromCustomerID($model->idcustomer)
            ));
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
             'type'=>'number'
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
                  ),
                  'view'=>array(
                     'visible'=>'false'
                  )
              ),
              'updateButtonUrl'=>"Action::decodeUpdateDetailSalesOrderUrl(\$data)",
          )
      ),
));
 ?>
	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo CHtml::label(number_format($model->total),'false',
                    array('class'=>'money')); 
                  echo $form->hiddenfield($model, 'total');
            ?>
         
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo CHtml::label(number_format($model->discount),'false', 
                    array('class'=>'money')); 
                  echo $form->hiddenfield($model, 'discount');
            ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->