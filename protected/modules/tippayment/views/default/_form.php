   <?php
/* @var $this TippaymentsController */
/* @var $model Tippayments */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('#Tippayments_idpartner').change(
		function() {
			$('#command').val('getComp');
			$('#tippayments-form').submit();
		});
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#tippayments-form').submit();
		});  
		$('#process').click(
		function(evt) {
			$('#command').val('countTip');
			$('#tippayments-form').submit();
		});
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tippayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/tippayment/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tippayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/tippayment/default/update", array('id'=>$model->id))
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
                  'name'=>'Tippayments[idatetime]',
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
		<?php echo $form->labelEx($model,'idsticker'); ?>
        <?php 
           echo $form->textField($model, 'idsticker', array('maxlength'=>50)); 
        ?>
        <?php echo $form->error($model,'idsticker');?> 
	</div>

	     
	<div class="row">
		<?php echo $form->labelEx($model,'ddatetime'); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'Tippayments[ddatetime]',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'yy/mm/dd',
					'defaultdate'=>$model->ddatetime
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;',
				),
                'value'=>$model->ddatetime,
			));
		?>
		<?php echo $form->error($model,'ddatetime'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'idpartner'); ?>
         <?php 
         	$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
				'name'=>'Tippayments[idpartner]',
				'sourceUrl'=>Yii::app()->createUrl('LookUp/getPartner'),
				'htmlOptions'=>array('size'=>35, 
						'id'=>'Tippayments_idpartner'					
	         	),
				'value'=>$model->idpartner
			));
         ?>
	</div>
	<?php echo $form->error($model,'idpartner'); ?>
	
	
		<?php 
			if ($model->idcomp !== '-' ) {
				echo "<div class=\"row\" id=\"comp\">";
				echo $form->labelEx($model,'idcomp'); 
				$data = CHtml::listData($compositions, 'iddetail', 'comname');
         		echo $form->dropDownList($model, 'idcomp', $data);	
         		echo $form->error($model,'idcomp');
         		echo "</div>";
			} else {
				echo $form->hiddenField($model, 'idcomp');
			}
         ?>
		
	<div class="row buttons">
      <?php echo CHtml::button('Proses', array('id'=>'process')); ?>
   </div>
   
	<div class="row">
		<?php echo $form->labelEx($model,'receiver'); ?>
        <?php 
           echo $form->textField($model, 'receiver', array('size'=>50, 'maxlength'=>100)); 
        ?>
        <?php echo $form->error($model,'receiver');?> 
	</div>
	
<?php 
    if (isset(Yii::app()->session['Detailtippayments'])) {
       $rawdata=Yii::app()->session['Detailtippayments'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailtippayments where id='$model->id'")->queryScalar();
       $sql="select * from detailtippayments where id='$model->id'";
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
                   'name'=>'invnum',
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

    if (isset(Yii::app()->session['Detailtippayments2'])) {
    	$rawdata=Yii::app()->session['Detailtippayments2'];
    	$count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailtippayments2 where id='$model->id'")->queryScalar();
       $sql="select * from detailtippayments2 where id='$model->id'";
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
    				*/
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
    ));
?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
        <?php 
          echo CHtml::tag('span', array('id'=>'amount', 'class'=>'money'),
          		number_format($model->amount));
           //echo $form->textField($model, 'amount', array('maxlength'=>8)); 
        ?>
        <?php echo $form->error($model,'amount');?> 
	</div>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->