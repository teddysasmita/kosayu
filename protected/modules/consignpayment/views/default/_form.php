   <?php
/* @var $this ConsignpaymentsController */
/* @var $model Consignpayments */
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
	$returlist = CJSON::encode(Yii::app()->session['Detailconsignpayments2']);
	
	$supplierScript=<<<EOS
      var supplierids=$supplierids;
      var suppliernames=$suppliernames;
      $('#Consignpayments_suppliername').change(function() {
         var activename=$('#Consignpayments_suppliername').val();
         $('#Consignpayments_idsupplier').val(
            supplierids[suppliernames.indexOf(activename)]);
      });
   	
      $('#searchUnsettledPO').click(
         function(event) {
            $('#command').val('setSupplier');
            mainform=$('#consignpayments-form');
            mainform.submit();
            event.preventDefault();
         }
      );   
			
		$(".updateDetail").click(
			function(event) {
			$("#command").val("adddetail");
            mainform=$('#consignpayments-form');
            mainform.submit();
		});
	
		$(".updateDetail2").click(
			function(event) {
			$("#command").val("adddetail2");
            mainform=$('#consignpayments-form');
            mainform.submit();
		});
	
	$("#Consignpayments_discount").change(function() {
		var disc = $("#Consignpayments_discount").val();
		var labelcost = $("#Consignpayments_labelcost").val();
		var total = $("#total").val();
		if ( disc < 0 ) {
			
			disc = - disc * total / 100;
			$("#Consignpayments_discount").val(disc);
			
		}
		$("#Consignpayments_total").val(total - disc - labelcost);
		$("#labeltotal").html(total - disc - labelcost);
		$("#labeltotal").addClass("money");
	});
	
	$("#Consignpayments_labelcost").change(function() {
		var disc = $("#Consignpayments_discount").val();
		var labelcost = $("#Consignpayments_labelcost").val();
		var total = $("#total").val();
		if ( disc < 0 ) {
			disc = - disc * total / 100;
			$("#Consignpayments_discount").val(disc);
			$("#Consignpayments_total").val(total - disc - labelcost);
		}
		$("#Consignpayments_total").val(total - disc - labelcost);
		$("#labeltotal").html(total - disc - labelcost);
		$("#labeltotal").addClass("money");
	});
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consignpayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/consignpayment/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consignpayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/consignpayment/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo CHtml::hiddenField('commandinfo', '', array('id'=>'commandinfo'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'idsupplier');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'status');
        echo $form->hiddenField($model, 'regnum');
      ?>
        
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
             'name'=>'Consignpayments_suppliername',
             'source'=>$suppliername,
           'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
         ));   
      ?>
		<?php echo $form->error($model,'idsupplier'); ?>
	</div>
   
   <div class="row">
		<?php echo $form->labelEx($model,'ldatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Consignpayments[ldatetime]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->ldatetime
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->ldatetime,
               ));
				//CHtml::tag('span', array('id'=>'ldatetime', 'class'=>'money'), $model->ldatetime);
            ?>
		<?php echo $form->error($model,'ldatetime'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Consignpayments[idatetime]',
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
               echo CHtml::button('Cari Nota & Retur', array( 'id'=>'searchUnsettledPO'));
         ?>
		<?php echo $form->error($model,'idatetime'); ?>
	</div>
	
   <div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
         <?php 
            echo $form->textArea($model, 'remark', array('rows'=>6, 'cols'=>50));
         ?>
         <?php echo $form->error($model,'remark'); ?>
	</div>
	
	 <div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
 		<?php echo CHtml::label(lookup::paymentStatus($model->status), false);?>
         <?php echo $form->error($model,'status'); ?>
	</div>
      
<?php 
    if (isset(Yii::app()->session['Detailconsignpayments'])) {
       $rawdata=Yii::app()->session['Detailconsignpayments'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailconsignpayments where id='$model->id'")
            ->queryScalar();
       $sql="select * from detailconsignpayments where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
            	array(
					'header'=>'Nomor Batch',
					'name'=>'batchcode',
				),
				array(
					'header'=>'Nama Barang',
					'name'=>'iditem',
					'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])",
				),
				array(
					'header'=>'Terjual',
					'type'=>'number',
					'name'=>'soldqty',
				),
				array(
					'header'=>'Retur',
					'type'=>'number',
					'name'=>'returqty',	
				),
				array(
					'header'=>'Harga Beli',
					'type'=>'number',
					'name'=>'buyprice',
				),
				array(
					'header'=>'Total',
					'type'=>'number',
					'name'=>'total',
				),
               /*array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                     'delete'=>array(
                        'visible'=>'false'
                      ),
                     'view'=>array(
                        'visible'=>'false'
                     )
                  ),
                  	'updateButtonUrl'=>"Action::decodeUpdateDetailConsignPaymentUrl(\$data)",
               		'updateButtonOptions'=>array('class'=>"updateDetail"),
               )*/
          ),
    ));
    
?>
	<div class="row">
      <?php echo CHtml::label('SubTotal', 'false'); ?>
      <?php 
         echo CHtml::label(number_format($model->total + $model->discount + $model->labelcost),'false', 
            array('class'=>'money'));
         echo CHtml::hiddenField('total', $model->total + $model->discount + $model->labelcost,
         		array('id'=>'total'));
      ?>
   </div>
	
   <div class="row">
      <?php echo $form->labelEx($model,'discount'); ?>
      <?php echo $form->textField($model, 'discount'); 
      ?>
      <?php echo $form->error($model,'discount'); ?>
   </div>
	
	<div class="row">
      <?php echo $form->labelEx($model,'labelcost'); ?>
      <?php echo $form->textField($model, 'labelcost'); ?>
      <?php echo $form->error($model,'labelcost'); ?>
   </div>
   
	<div class="row">
      <?php echo $form->labelEx($model,'total'); ?>
      <?php 
         echo CHtml::label(number_format($model->total),'false', 
            array('class'=>'money', 'id'=>'labeltotal')); 
         echo $form->hiddenfield($model, 'total');
      ?>
      <?php echo $form->error($model,'total'); ?>
   </div>
<?php 
    if (isset(Yii::app()->session['Detailconsignpayments2'])) {
       $rawdata3=Yii::app()->session['Detailconsignpayments2'];
       $count=count($rawdata3);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from payments where idtransaction='$model->id'")
            ->queryScalar();
       $sql="select * from payments where idtransaction='$model->id'";
       $rawdata3 = Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata3, array(
          'totalItemCount'=>$count,
    	'pagination'=>false,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
               	array(
					'header'=>'Metode',
					'name'=>'method',
               		'value'=>"lookup::getMethod(\$data['method'])"
				),
				array(
					'header'=>'Jumlah',
					'type'=>'number',
					'name'=>'amount',
				),
            	array(
            		'class'=>'CButtonColumn',
            		'buttons'=> array(
            			'view'=>array(
            				'visible'=>'false'
            			)
            		),
            		'updateButtonUrl'=>"Action::decodeUpdateDetailConsignPayment2Url(\$data)",
            		'deleteButtonUrl'=>"Action::decodeDeleteDetailConsignPayment2Url(\$data)",
            	)
          ),
    ));
    
?>
   
   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->