   <?php
/* @var $this PurchasesretursController */
/* @var $model Purchasesreturs */
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
      
      $('#searchUnsettledPO').click(function() {
         var activename=$('#Purchasesreturs_suppliername').val();
         $('#Purchasesreturs_idsupplier').val(
            supplierids[suppliernames.indexOf(activename)]);
         $.getJSON('index.php?r=LookUp/getUnsettledPO',{ idsupplier: $('#Purchasesreturs_idsupplier').val() },
            function(data) {
               $('#Purchasesreturs_idpurchaseorder').html('');
               var ct=0;
               $('#Purchasesreturs_idpurchaseorder').append(
                  "<option value=''>Harap Pilih</option>"
               );
               while(ct < data.length) {
                  if (data[ct].id !== '') {
                     $('#Purchasesreturs_idpurchaseorder').append(
                        '<option value='+data[ct].id+'>'+unescape(data[ct].regnum)+'</option>'
                     );
                  };
                  ct++;
               };
            });
      });
      
      $('#Purchasesreturs_idpurchaseorder').change(
         function(event) {
            $('#command').val('setPO');
            mainform=$('#purchasesreturs-form');
            mainform.submit();
            event.preventDefault();
         }
      );   
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchasesreturs-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchasesretur/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchasesreturs-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/purchasesretur/default/update", array('id'=>$model->id))
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
        echo $form->hiddenField($model, 'regnum');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
        <?php
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'Purchasesreturs[idatetime]',
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
             'name'=>'Purchasesreturs_suppliername',
             'source'=>$suppliername,
           'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
         ));
         echo CHtml::Button('Cari PO', array( 'id'=>'searchUnsettledPO'));   
      ?>
		<?php echo $form->error($model,'idsupplier'); ?>
	</div>

   <div class="row">
		<?php echo $form->labelEx($model,'idpurchaseorder'); ?>
		<?php    
         echo $form->dropDownList($model,'idpurchaseorder',
            array($model->idpurchaseorder=>lookup::PurchasesOrderNumFromID($model->idpurchaseorder)), 
            array('empty'=>'Harap Pilih')
         );
      ?>
		<?php echo $form->error($model,'idpurchaseorder'); ?>
      </div>
   
   <div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
         <?php 
            echo $form->textArea($model, 'remark', array('rows'=>6, 'cols'=>50));
         ?>
         <?php echo $form->error($model,'remark'); ?>
	</div>
      
<?php 
    if (isset(Yii::app()->session['Detailpurchasesreturs'])) {
       $rawdata=Yii::app()->session['Detailpurchasesreturs'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailpurchasesreturs where id='$model->idpurchaseorder'")
            ->queryScalar();
       $sql="select * from detailpurchasesreturs where id='$model->idpurchaseorder'";
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
                  'header'=>'Diterima',
                  'type'=>'number',
                  'name'=>'receivedqty',
               ),
               array(
                  'header'=>'Harga Akhir',
                  'type'=>'number',
                  'name'=>'prevprice',
               ), 
               array(
                  'header'=>'Harga Baru',
                  'type'=>'number',
                  'name'=>'price',
               ), 
               array(
                  'header'=>'Biaya 1',
                  'type'=>'number',
                  'name'=>'cost1',
               ),
				array(
					'header'=>'Biaya 2',
					'type'=>'number',
					'name'=>'cost2',
				), 
               	array(
					'header'=>'Dikembalikan',
					'type'=>'number',
					'name'=>'qty',
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
                  'updateButtonUrl'=>"Action::decodeUpdateDetailPurchaseMemoUrl(\$data)",
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