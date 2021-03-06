<?php
/* @var $this PurchasesController */
/* @var $model Purchases */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $suppliers=Yii::app()->db->createCommand()
      ->select('id, code, firstname, lastname')
      ->from('suppliers')
      ->order('firstname, lastname')
      ->queryAll();
   foreach($suppliers as $row) {
      $supplierids[]=$row['id'];
      $suppliernames[]=$row['code'].'-'.$row['firstname'].' '.$row['lastname'];
   }
   $supplierids=CJSON::encode($supplierids);
   $suppliernames=CJSON::encode($suppliernames);
   $supplierScript=<<<EOS
      var supplierids=$supplierids;
      var suppliernames=$suppliernames;
      $('#suppliername').change(function() {
         var activename=$('#suppliername').val();
         $('#Purchases_idsupplier').val(
            supplierids[suppliernames.indexOf(activename)]);
      });
   
		$('#Purchases_idorder').change(function() {
         	$('#command').val('setPO');
   			$('#purchases-form').submit();
      	});
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchases-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchases/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchases-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchases/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'idsupplier');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'status');
        echo $form->hiddenField($model, 'paystatus');
        echo $form->hiddenField($model, 'regnum');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Purchases[idatetime]',
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
		<?php echo $form->labelEx($model,'idorder'); ?>
		 <?php
		 	echo $form->textField($model,'idorder',array('maxlength'=>20)); 
            ?>
		<?php echo $form->error($model,'idorder'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idsupplier'); ?>
		<?php 
               $suppliers=Yii::app()->db->createCommand()
                  ->select("id,code,firstname,lastname")
                  ->from("suppliers")
                  ->order("firstname, lastname")   
                  ->queryAll();
               foreach($suppliers as $row) {
                  $suppliername[]=$row['code'].'-'.$row['firstname'].' '.$row['lastname'];
               }
               $this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                   'name'=>'suppliername',
                   'source'=>$suppliername,
                 'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
               ));
            ?>
		<?php echo $form->error($model,'idsupplier'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'pdatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Purchases[pdatetime]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->idatetime
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->pdatetime,
               ));
            ?>
		<?php echo $form->error($model,'idatetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

<?php 
    if (isset(Yii::app()->session['Detailpurchases'])) {
       $rawdata=Yii::app()->session['Detailpurchases'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailpurchases where id='$model->id'")->queryScalar();
       $sql="select * from detailpurchases where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
    	'keyField'=>'iddetail'
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
			array(
            	'header'=>'Kode Batch',
            	'name'=>'batchcode',
            ),
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
               'header'=>'Harga @',
               'name'=>'price',
               'type'=>'number'
            ),
            array(
               'header'=>'Disc',
               'name'=>'discount',
               'type'=>'number'
            ),
			array(
            	'header'=>'Hrg Jual @',
            	'name'=>'sellprice',
            	'type'=>'number'
            ),
            array(
               'class'=>'CButtonColumn',
               'buttons'=> array(
                  'view'=>array(
                     'visible'=>'false'
                  )
               ),
            	'deleteButtonUrl'=>"Action::decodeDeleteDetailPurchasesUrl(\$data, $model->regnum)",
               'updateButtonUrl'=>"Action::decodeUpdateDetailPurchasesUrl(\$data, $model->regnum)",
            )
          ),
    ));
?> 
   
      
   <div class="row">
      <?php echo $form->labelEx($model,'total'); ?>
      <?php 
         echo CHtml::label(number_format($model->total),'false', 
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