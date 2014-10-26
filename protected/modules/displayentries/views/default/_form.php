   <?php
/* @var $this DisplayentriesController */
/* @var $model Displayentries */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('#Displayentries_transid').change(
		function() {
			$.getJSON('index.php?r=LookUp/getTrans',{ id: $('#Displayentries_transid').val() },
            function(data) {
				if (data[0].id !== 'NA') {
					$('#Displayentries_transname').val(data[0].transname);
					$('#transinfo').html(data[0].transinfo);
            		$('#Displayentries_transinfo').val(data[0].transinfo);
            		$('#command').val('getPO');
					$('#Displayentries_transinfo_em_').prop('style', 'display:none')
					$('#displayentries-form').submit();
				} else {
					$('#Displayentries_transname').val();
					$('#transinfo').html('');
            		$('#Displayentries_transinfo_em_').html('Data tidak ditemukan');
					$('#Displayentries_transinfo_em_').prop('style', 'display:block')
				}
			})
		});
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#displayentries-form').submit();
		});   
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'displayentries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/displayentries/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'displayentries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/displayentries/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo CHtml::hiddenField('detailcommand', '', array('id'=>'detailcommand'));
       //echo $form->hiddenField($model, 'idwarehouse');
        
      ?>
     
    <div class="row">
		<?php echo $form->labelEx($model,'idwarehouse'); ?>
        <?php 
			$warehouses = lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
         	if (count($warehouses) > 1) {
				$data = CHtml::listData($warehouses, 'id', 'code');
         		echo CHtml::dropDownList('Displayentries[idwarehouse]', '', $data, 
					array('empty'=>'Harap Pilih'));
         	} else {
				echo CHtml::hiddenField('Displayentries[idwarehouse]', $warehouses[0]['id']);
				echo CHtml::label($warehouses[0]['code'],'false', array('class'=>'money')); 
			}
        ?>
        <?php echo $form->error($model,'idwarehouse');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'serialnum'); ?>
        <?php 
           echo $form->textField($model, 'serialnum', array('maxlength'=>50)); 
        ?>
        <?php echo $form->error($model,'serialnum');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'transid'); ?>
        <?php 
        	if ($info == 'Data Permintaan Barang tidak ditemukan')
           		echo CHtml::tag('div', array('id'=>'mdinfo', 'class'=>'errorMessage'), $info); 
        	else 
        		echo CHtml::tag('div', array('id'=>'mdinfo', 'class'=>'money'), $info);
        ?>
        <?php echo $form->error($model,'transid');?> 
	</div>
      
<?php $this->endWidget(); ?>


      
</div><!-- form -->