   <?php
/* @var $this StockentriesController */
/* @var $model Stockentries */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('#Stockentries_transid').change(
		function() {
			$.getJSON('index.php?r=LookUp/getTrans',{ id: $('#Stockentries_transid').val() },
            function(data) {
				if (data[0].id !== 'NA') {
					$('#Stockentries_transname').val(data[0].transname);
					$('#transinfo').html(data[0].transinfo);
            		$('#Stockentries_transinfo').val(data[0].transinfo);
            		$('#command').val('getPO');
					$('#Stockentries_transinfo_em_').prop('style', 'display:none')
					$('#stockentries-form').submit();
				} else {
					$('#Stockentries_transname').val();
					$('#transinfo').html('');
            		$('#Stockentries_transinfo_em_').html('Data tidak ditemukan');
					$('#Stockentries_transinfo_em_').prop('style', 'display:block')
				}
			})
		});
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#stockentries-form').submit();
		});   
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stockentries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/stockentries/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stockentries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/stockentries/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo CHtml::hiddenField('detailcommand', '', array('id'=>'detailcommand'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'transname');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'transinfo');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Stockentries[idatetime]',
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
		<?php echo $form->labelEx($model,'idwarehouse'); ?>
         <?php 
         	$warehouses = lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
         	if (count($warehouses) > 1) {
				$data = CHtml::listData($warehouses, 'id', 'code');
         		echo $form->dropDownList($model, 'idwarehouse', $data, 
					array('empty'=>'Harap Pilih'));
         	} else {
				echo CHtml::hiddenField('Stockentries[idwarehouse]', $warehouses[0]['id']);
				echo CHtml::label($warehouses[0]['code'],'false', array('class'=>'money')); 
			}
         ?>
	</div>
	
	
		
	<div class="row">
		<?php echo $form->labelEx($model,'donum'); ?>
        <?php 
           echo $form->textField($model, 'donum', array('maxlength'=>50)); 
        ?>
        <?php echo $form->error($model,'donum');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
        <?php 
           echo $form->textArea($model, 'remark', array('COLS'=>40, 'ROWS'=>5)); 
        ?>
        <?php echo $form->error($model,'remark');?> 
	</div>
      
    <div class="row">
		<?php echo $form->labelEx($model,'transid'); ?>
		<?php 
			echo $form->textField($model,'transid', array('maxlength'=>30));
		?>
		<?php echo $form->error($model,'transid'); ?>
	</div>
	
    <div class="row">
		<?php echo CHtml::label('Info', false); ?>
		<?php 
			echo CHtml::label($model->transinfo, false, 
				array('id'=>'transinfo','width'=>'200px'));
		?>
		<?php echo $form->error($model,'transinfo'); ?>
	</div>
	  
	
<?php 
    if (isset(Yii::app()->session['Detailstockentries'])) {
       $rawdata=Yii::app()->session['Detailstockentries'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailstockentries where id='$model->id'")->queryScalar();
       $sql="select * from detailstockentries where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
		  'keyField'=>'iddetail',
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
                  'header'=>'Nomor Seri',
                  'name'=>'serialnum',
              ),
				array(
					'header'=>'Tersedia',
					'name'=>'status',
					'value'=>"lookup::StockStatusName(\$data['status'])",
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
				'updateButtonOptions'=>array("class"=>'updateButton'),
				'updateButtonUrl'=>"Action::decodeUpdateDetailStockEntryUrl(\$data, '$model->idwarehouse', 
					'$model->transname', '$model->transid')",
              )
          ),
    ));
    
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->