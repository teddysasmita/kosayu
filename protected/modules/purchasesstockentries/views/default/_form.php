   <?php
/* @var $this PurchasesstockentriesController */
/* @var $model Purchasesstockentries */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
	$suppliers=Yii::app()->db->createCommand()
	->select('id, firstname, lastname')
	->from('suppliers')
	->order('firstname, lastname')
	->queryAll();
	foreach($suppliers as $row) {
		$supplierids[]=$row['id'];
		$suppliernames[]=$row['firstname'].' '.$row['lastname'];
	}
	$supplierids=CJSON::encode($supplierids);
	$suppliernames=CJSON::encode($suppliernames);
	$supplierScript=<<<EOS
      var supplierids=$supplierids;
      var suppliernames=$suppliernames;
      $('#Purchasesstockentries_suppliername').change(function() {
         var activename=$('#Purchasesstockentries_suppliername').val();
         $('#Purchasesstockentries_idsupplier').val(
            supplierids[suppliernames.indexOf(activename)]);
      });
EOS;
	Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);
	
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchasesstockentries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchasesstockentries/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchasesstockentries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchasesstockentries/default/update", array('id'=>$model->id))
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
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
             	$this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Purchasesstockentries[idatetime]',
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
		<?php echo $form->labelEx($model,'regnum'); ?>
		<?php 
         	echo $form->textField($model,'regnum', array('maxlength'=>30)); 
      	?>
		<?php echo $form->error($model,'regnum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idsupplier'); ?>
		<?php 
         $suppliers=Yii::app()->db->createCommand()
            ->select("id,firstname,lastname")
            ->from("suppliers")
            ->order("firstname, lastname")   
            ->queryAll();
         foreach($suppliers as $row) {
            $suppliername[]=$row['firstname'].' '.$row['lastname'];
         }
         $this->widget("zii.widgets.jui.CJuiAutoComplete", array(
             'name'=>'Purchasesstockentries_suppliername',
             'source'=>$suppliername,
           'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
         ));
      ?>
		<?php echo $form->error($model,'idsupplier'); ?>
	</div>

      <div class="row">
		<?php echo $form->labelEx($model,'ponum'); ?>
		<?php 
         	echo $form->textField($model,'ponum'); 
      	?>
		<?php echo $form->error($model,'ponum'); ?>
	</div>
      
	<div class="row">
		<?php echo $form->labelEx($model,'sjnum'); ?>
        <?php 
           echo $form->textField($model, 'sjnum'); 
        ?>
        <?php echo $form->error($model,'sjnum');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
        <?php 
           echo $form->textArea($model, 'remark', array('COLS'=>40, 'ROWS'=>5)); 
        ?>
        <?php echo $form->error($model,'remark');?> 
	</div>
      
<?php 
    if (isset(Yii::app()->session['Detailpurchasesstockentries'])) {
       $rawdata=Yii::app()->session['Detailpurchasesstockentries'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailpurchasesstockentries where id='$model->id'")->queryScalar();
       $sql="select * from detailpurchasesstockentries where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
               array(
                   'header'=>'Nama Barang',
                   'name'=>'iditem',
                   'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
               ),
				array(
                   'header'=>'Qty',
                   'name'=>'qty',
                   'type'=>'number'               
				),
				array(
                   'header'=>'Harga Beli',
                   'name'=>'buyprice',
                   'type'=>'number'               
				),
				array(
					'header'=>'Harga Jual',
					'name'=>'sellprice',
					'type'=>'number'
				),
				array(
						'header'=>'Catatan',
						'name'=>'remark',
				),
              array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                      /*'delete'=>array(
                       'visible'=>'false'
                      ),*/
                     'view'=>array(
                        'visible'=>'false'
                     ),
					/*'update'=>array(
						'visible'=>'false'
					)*/
                  ),
					'deleteButtonUrl'=>"Action::decodeDeleteDetailPurchasesStockEntryUrl(\$data)",
					'updateButtonUrl'=>"Action::decodeUpdateDetailPurchasesStockEntryUrl(\$data)"
              )
          ),
    ));
    
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->