<?php
/* @var $this OrderretrievalsController */
/* @var $model Orderretrievals */
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
      $('#Orderretrievals_suppliername').change(function() {
         var activename=$('#Orderretrievals_suppliername').val();
         $('#Orderretrievals_idsupplier').val(
            supplierids[suppliernames.indexOf(activename)]);
      });
		$('#Orderretrievals_invnum').change(function() {
   			$('#command').val('loadInvoice');
   			$('#orderretrievals-form').submit();
		});
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#orderretrievals-form').submit();
   			//evt.preventDefault();
		});  
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'orderretrievals-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/orderretrievals/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'orderretrievals-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/orderretrievals/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'status');
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'receivername');
        echo $form->hiddenField($model, 'receiveraddress');
        echo $form->hiddenField($model, 'receiverphone');
      ?>
      
	<div class='error'>
		<?php echo CHtml::label($form_error, FALSE)?>
	</div>
      
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Orderretrievals[idatetime]',
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
	
	<div class='row'>
		<?php echo CHtml::tag('span', array('id'=>'info', 'class'=>'errorMessage'), 
			'Utk Ganti Barang, beri awalan G, tanpa angka 0 didepan'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'invnum'); ?>
         <?php 
            /*$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Orderretrievalsnt_receivername',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getReceiverinfobyname'),
              'value'=>$model->receivername
            ));*/
			echo $form->textField($model, 'invnum', array('size'=>30));
         ?>
         <?php echo $form->error($model,'invnum'); ?>
	</div>

	<div class="row">
         <?php echo $form->labelEx($model,'receivername'); ?>
         <?php 
            /*$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Orderretrievalsnt_receivername',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getReceiverinfobyname'),
              'value'=>$model->receivername
            ));*/
			//echo $form->textField($model, 'receivername', array('size'=>50));
			echo CHtml::label($model->receivername, FALSE);
         ?>
         <?php echo $form->error($model,'receivername'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'receiveraddress'); ?>
         <?php
         	echo CHtml::label($model->receiveraddress, FALSE); 
			//echo $form->textField($model, 'receiveraddress', array('size'=>50));
         ?>
         <?php echo $form->error($model,'receiveraddress'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'receiverphone'); ?>
         <?php 
			//echo $form->textField($model, 'receiverphone');
         echo CHtml::label($model->receiverphone, FALSE);
         ?>
         <?php echo $form->error($model,'receiverphone'); ?>
	</div>
	
<?php 
   
	if (isset(Yii::app()->session['Detailorderretrievals2'])) {
       $rawdata=Yii::app()->session['Detailorderretrievals2'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailorderretrievals2 where id='$model->id'")->queryScalar();
       $sql="select * from detailorderretrievals2 where id='$model->id'";
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
				'header'=>'Jmlh Faktur',
				'name'=>'invqty',
			),
            array(
               'header'=>'Sisa',
               'name'=>'leftqty',
            ),
            array(
				'header'=>'Kirim',
				'name'=>'qty',
			),
          ),
    ));

    if (isset(Yii::app()->session['Detailorderretrievals'])) {
       $rawdata=Yii::app()->session['Detailorderretrievals'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailorderretrievals where id='$model->id'")->queryScalar();
       $sql="select * from detailorderretrievals where id='$model->id'";
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
					'header'=>'Jmlh',
					'name'=>'qty',
				),
				array(
					'header'=>'Gudang',
					'name'=>'idwarehouse',
					'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
				),
				array(
					'class'=>'CButtonColumn',
					'buttons'=> array(
						'view'=>array(
							'visible'=>'false'
						),
					),
					'updateButtonOptions'=>array("class"=>'updateButton'),
					'updateButtonUrl'=>"Action::decodeUpdateDetailOrderRetrievalUrl(\$data)",
					'deleteButtonUrl'=>"Action::decodeDeleteDetailOrderRetrievalUrl(\$data)",
				)
			),
    	));
?>

	<div class='error'>
		<?php echo CHtml::label($form_error, FALSE)?>
	</div>
	
   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->