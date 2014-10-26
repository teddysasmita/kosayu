   <?php
/* @var $this RetrievalreplacesController */
/* @var $model Retrievalreplaces */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('#Retrievalreplaces_serialnum').change(
		function() {
			$.getJSON('index.php?r=LookUp/getExitedItemFromSerial',
				{serialnum: escape($('#Retrievalreplaces_serialnum').val()),
				retrievalnum: $('#Retrievalreplaces_retrievalnum').val() },
            function(data) {
				if (data == false ) {
					$('#mdinfo').addClass('errorMessage');
					$('#mdinfo').removeClass('money');
					$('#mdinfo').html('Nomor Faktur dan/atau Nomor seri TIDAK valid');
				} else {
					$('#Retrievalreplaces_iditem').val(data.iditem);
					$('#mdinfo').addClass('money');
					$('#mdinfo').removeClass('errorMessage');
					$('#mdinfo').html(data.name);
				}
			})
		});
   
		$('#Retrievalreplaces_idwhsource').change(
		function() {
			$.getJSON('index.php?r=LookUp/checkItemQty',
				{iditem: $('#Retrievalreplaces_iditem').val(),
				 idwh: $('#Retrievalreplaces_idwhsource').val()},
            function(data) {
				if (data == 0 ) {
					$('#validdata').val('false');
					$('#mdqty').addClass('errorMessage');
					$('#mdqty').removeClass('money');
					$('#mdqty').html('Jumlah barang tidak cukup pada gudang tersebut.');
				} else {
					$('#validdata').val('true');
					$('#mdqty').html('');
				}
			})
		});
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'retrievalreplaces-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/retrievalreplaces/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'retrievalreplaces-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/retrievalreplaces/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo CHtml::hiddenField('detailcommand', '', array('id'=>'detailcommand'));
        echo CHtml::hiddenField('validdata', '', array('id'=>'validdata'));
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'idatetime');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'iditem');
        echo $form->hiddenField($model, 'id');
      ?>
     
    <div class="row">
		<?php echo $form->labelEx($model,'idwarehouse'); ?>
        <?php 
        	$warehouses = lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
        	if (count($warehouses) > 1) {
				$data = CHtml::listData($warehouses, 'id', 'code');
				echo CHtml::dropDownList('Retrievalreplaces[idwarehouse]', 
					'', $data, array('empty'=>'Harap Pilih'));
			} else {
           		echo CHtml::hiddenField('Retrievalreplaces[idwarehouse]', $warehouses[0]['id']);
				echo CHtml::tag('span',array('class'=>'money'), $warehouses[0]['code']); 
        	}
		?>
        <?php echo $form->error($model,'idwarehouse');?> 
	</div>
	 
    <div class="row">
		<?php echo $form->labelEx($model,'retrievalnum'); ?>
        <?php 
           echo $form->textField($model, 'retrievalnum', array('maxlength'=>12)); 
        ?>
        <?php echo $form->error($model,'retrievalnum');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'serialnum'); ?>
        <?php 
           echo $form->textField($model, 'serialnum', array('maxlength'=>50)); 
        ?>
        <?php echo $form->error($model,'serialnum');?> 
	</div>
	
	<div class="row">
		<?php 
			echo CHtml::tag('span', array('id'=>'mdinfo', 'class'=>'errorMessage'));
		?>
	</div>
	
	
	<div class="row">
		<?php echo $form->labelEx($model,'idwhsource'); ?>
		<?php
			$data=Yii::app()->db->createCommand()->select('id,code')->from('warehouses')->queryAll();
			$data=CHtml::listData($data, 'id', 'code'); 
			echo $form->dropDownList($model, 'idwhsource', $data, array('empty'=>'Harap Pilih')); 
		?>
		<?php echo $form->error($model,'idwhsource'); ?>
	</div>
	
	<div class="row">
        <?php 
        	echo CHtml::tag('span', array('id'=>'mdqty', 'class'=>'errorMessage')); 
        ?>
	</div>
	
	<div class="row">
        <?php 
           	if (strlen($error) > 0)
        		echo CHtml::tag('span', array('class'=>'errorMessage'), $error); 
        ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>
      
<?php $this->endWidget(); ?>


      
</div><!-- form -->