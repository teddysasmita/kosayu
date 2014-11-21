   <?php
/* @var $this TippaymentsController */
/* @var $model Tippayments */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
		$('#Tippayments_transid').change(
		function() {
			$.getJSON('index.php?r=LookUp/getTrans',{ id: $('#Tippayments_transid').val() },
            function(data) {
				if (data[0].id !== 'NA') {
					$('#Tippayments_transname').val(data[0].transname);
					$('#transinfo').html(data[0].transinfo);
            		$('#Tippayments_transinfo').val(data[0].transinfo);
            		$('#command').val('getPO');
					$('#Tippayments_transinfo_em_').prop('style', 'display:none')
					$('#tippayments-form').submit();
				} else {
					$('#Tippayments_transname').val();
					$('#transinfo').html('');
            		$('#Tippayments_transinfo_em_').html('Data tidak ditemukan');
					$('#Tippayments_transinfo_em_').prop('style', 'display:block')
				}
			})
		});
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#tippayments-form').submit();
		});   
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tippayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/tippayments/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tippayments-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/tippayments/default/update", array('id'=>$model->id))
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
				'name'=>'idpartner',
				'sourceUrl'=>Yii::app()->createUrl('LookUp/getPartner'),
				'htmlOptions'=>array('size'=>50),
				'value'=>$model->idpartner
			));
         ?>
	</div>
	<?php echo $form->error($model,'idpartner'); ?>
	
		
	<div class="row">
		<?php echo $form->labelEx($model,'receiver'); ?>
        <?php 
           echo $form->textField($model, 'receiver', array('maxlength'=>100)); 
        ?>
        <?php echo $form->error($model,'receiver');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
        <?php 
           echo $form->textField($model, 'amount', array('maxlength'=>8)); 
        ?>
        <?php echo $form->error($model,'amount');?> 
	</div>
	
<?php 
    if (isset(Yii::app()->session['Detailtippayments'])) {
       $rawdata=Yii::app()->session['Detailtippayments'];
       $count=count($rawdata);
    } 
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
		  'keyField'=>'id',
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
              ),
          ),
    ));

    if (isset(Yii::app()->session['Detailtippayments2'])) {
    	$rawdata=Yii::app()->session['Detailtippayments2'];
    	$count=count($rawdata);
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
    		'totalItemCount'=>$count,
    		'keyField'=>'id',
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
    		'dataProvider'=>$dataProvider,
    		'columns'=>array(
    				array(
    					'header'=>'Kelompok Komisi',
						'name'=>'tipgroupname',
    				),
    				array(
						'header'=>'Total Belanja',
						'name'=>'amount',
    				),
    		),
    ));
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->