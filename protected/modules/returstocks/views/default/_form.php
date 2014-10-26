   <?php
/* @var $this ReturstocksController */
/* @var $model Returstocks */
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
      $('#Returstocks_suppliername').change(function() {
         var activename=$('#Returstocks_suppliername').val();
         $('#Returstocks_idsupplier').val(
            supplierids[suppliernames.indexOf(activename)]);
      });
EOS;
	Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);
	
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'returstocks-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/returstocks/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'returstocks-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/returstocks/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">
		Fields with <span class="required">*</span> are required.
	</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'idsupplier');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'datetimelog');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Returstocks[idatetime]',
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
             'name'=>'Returstocks_suppliername',
             'source'=>$suppliername,
           'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
         ));
      ?>
		<?php echo $form->error($model,'idsupplier'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
        <?php 
           echo $form->textArea($model, 'remark', array('COLS'=>40, 'ROWS'=>5)); 
        ?>
        <?php echo $form->error($model,'remark');?> 
	</div>
      
<?php 
    if (isset(Yii::app()->session['Detailreturstocks'])) {
       $rawdata=Yii::app()->session['Detailreturstocks'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailreturstocks where id='$model->id'")->queryScalar();
       $sql="select * from detailreturstocks where id='$model->id'";
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
					'header'=>'Gudang',
					'name'=>'idwarehouse',
					'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
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
					'deleteButtonUrl'=>"Action::decodeDeleteDetailReturStockUrl(\$data)",
					'updateButtonUrl'=>"Action::decodeUpdateDetailReturStockUrl(\$data)"
              )
          ),
    ));
    
?>

<?php 
	$rawdata = FALSE;
    if (isset(Yii::app()->session['Detailreturstocks2'])) {
       $rawdata=Yii::app()->session['Detailreturstocks2'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailreturstocks2 where id='$model->id'")->queryScalar();
       $sql="select * from detailreturstocks2 where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
if (($rawdata !== FALSE) && count($rawdata) > 0) {
	$dataProvider = new CArrayDataProvider ( $rawdata, array (
			'totalItemCount' => $count 
	) );
	$this->widget ( 'zii.widgets.grid.CGridView', array (
			'dataProvider' => $dataProvider,
			'columns' => array (
					array (
							'header' => 'Item Name',
							'name' => 'iditem',
							'value' => "lookup::ItemNameFromItemID(\$data['iditem'])" 
					),
					array (
							'header' => 'Nomor Seri',
							'name' => 'serialnum' 
					),
					array (
							'header' => 'Catatan',
							'name' => 'remark' 
					),
					array (
							'class' => 'CButtonColumn',
							'buttons' => array (
									'delete' => array (
											'visible' => 'false' 
									),
									'view' => array (
											'visible' => 'false' 
									) 
							),
							'updateButtonOptions' => array (
									"class" => 'updateButton' 
							),
							'updateButtonUrl' => "Action::decodeUpdateDetailReturStock2Url(\$data)" 
					) 
			) 
	) );
} else {
	echo "Data nomor serial tidak ditemukan.";
	}
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div>
<!-- form -->