   <?php
/* @var $this GuidepaymentsController */
/* @var $model Guidepayments */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#guidepayments-form').submit();
		});  
		$('#process').click(
		function(evt) {
			$('#command').val('countTip');
			$('#guidepayments-form').submit();
		});
   	
		$("#Guidepayments_idguide").focusout(
			function(event) {
				$.getJSON("index.php?r=LookUp/getGuideName",
					{ id: $("#Guidepayments_idguide").val() },
						function(data) {
							if (data == 0) {
								$("#guidename").removeClass('money');
								$("#guidename").addClass('errorMessage');
								$("#guidename").html('Data Guide tidak ditemukan');
								$("#Guidepayments_idguide").val('');
							} else {
								$("#guidename").addClass('money');
								$("#guidename").removeClass('errorMessage');
								$("#guidename").html(data);
							}
				});
				
		});
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'guidepayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/guidepayment/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'guidepayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/guidepayment/default/update", array('id'=>$model->id))
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
        echo $form->hiddenField($model, 'amount');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Guidepayments[idatetime]',
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
		<?php echo $form->labelEx($model,'idguide'); ?>
		<?php 
			//echo $form->textField($model, 'idguide');
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'Guidepayments[idguide]',
				// additional javascript options for the date picker plugin
				'sourceUrl'=> Yii::app()->createUrl('LookUp/CompleteGuide'),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->idguide,
			));
		?>
		<?php echo $form->error($model,'idguide'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('', false);
			echo CHtml::tag('span', array('id'=>'guidename', 'class'=>'money'), ''); 
		?>
	</div>
   
	<div class="row buttons">
      <?php echo CHtml::button('Proses', array('id'=>'process')); ?>
   </div>
	
<?php 
	/*if (isset(Yii::app()->session['Detailguidepayments'])) {
       $rawdata=Yii::app()->session['Detailguidepayments'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailguidepayments where id='$model->id'")->queryScalar();
       $sql="select * from detailguidepayments where id='$model->id'";
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
                   'header'=>'Nomor Invoice',
                   'name'=>'invoicenum',
               ),
              array(
                  'header'=>'Total Belanja',
                  'name'=>'amount',
              		'type'=>'number',
              ),
            array(
            	'header'=>'Total Potongan',
            	'name'=>'totaldiscount',
            	'type'=>'number',
            ),
          ),
    ));
    */
?>
	<div class="row">
    		<?php echo $form->labelEx($model,'commission'); ?>
            <?php 
              echo CHtml::tag('span', array('id'=>'commision', 'class'=>'money'),
              		number_format($model->commission));
               //echo $form->textField($model, 'amount', array('maxlength'=>8)); 
            ?>
    </div>
    
    <div class="row">
    		<?php echo $form->labelEx($model,'deposit'); ?>
            <?php 
              echo CHtml::tag('span', array('id'=>'deposit', 'class'=>'money'),
              		number_format($model->deposit));
               //echo $form->textField($model, 'amount', array('maxlength'=>8)); 
            ?>
    </div>
    
    <div class="row">
    		<?php echo $form->labelEx($model,'amount'); ?>
            <?php 
              echo $form->textField($model,'amount');
               //echo $form->textField($model, 'amount', array('maxlength'=>8)); 
            ?>
    </div>
	
<?php     
    /*if (isset(Yii::app()->session['Detailguidepayments2'])) {
    	$rawdata=Yii::app()->session['Detailguidepayments2'];
    	$count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailguidepayments2 where id='$model->id'")->queryScalar();
       $sql="select * from detailguidepayments2 where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
    		'totalItemCount'=>$count,
    		'keyField'=>'iddetail',
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
    		'dataProvider'=>$dataProvider,
    		'columns'=>array(
    				/*array(
    					'header'=>'Nomor Faktur',
						'name'=>'regnum',
    				),
    				array(
    					'header'=>'Harga',
    					'name'=>'price',
    					'type'=>'number',
    				),
    				array(
    					'header'=>'Disc',
    					'name'=>'discount',
    					'type'=>'number',
    				),
    				array(
    					'header'=>'Qty',
						'name'=>'qty',
    					'type'=>'number',
    				),
    				
    				array(
    					'header'=>'Kelompok Komisi',
						'name'=>'idtipgroup',
    					'value'=>"lookup::ItemTipGroupNameFromID(\$data['idtipgroup'])",
    				),
    				array(
						'header'=>'Jumlah',
						'name'=>'amount',
    					'type'=>'number',
    				),
    		),
    ));*/
?>
	
   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->