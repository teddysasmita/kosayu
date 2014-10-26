   <?php
/* @var $this StockdamageController */
/* @var $model Stockdamage */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#stockdamage-form').submit();
		});   
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stockdamage-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/stockdamage/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stockdamage-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/stockdamage/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo CHtml::hiddenField('detailcommand', '', array('id'=>'detailcommand'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'regnum');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Stockdamage[idatetime]',
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
         		echo CHtml::dropDownList('Stockdamage[idwarehouse]', $model->idwarehouse, $data, 
					array('empty'=>'Harap Pilih'));
         	} else {
				echo CHtml::hiddenField('Stockdamage[idwarehouse]', $warehouses[0]['id']);
				echo CHtml::label($warehouses[0]['code'],'false', array('class'=>'money')); 
			}
         ?>
	</div>
		
<?php 
    if (isset(Yii::app()->session['Detailstockdamage'])) {
       $rawdata=Yii::app()->session['Detailstockdamage'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailstockdamage where id='$model->id'")->queryScalar();
       $sql="select * from detailstockdamage where id='$model->id'";
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
						'header'=>'Catatan',
						'name'=>'remark',
						'type'=>'ntext',
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
				'updateButtonUrl'=>"Action::decodeUpdateDetailStockDamageUrl(\$data, \"$model->idwarehouse\")",
              )
          ),
    ));
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->