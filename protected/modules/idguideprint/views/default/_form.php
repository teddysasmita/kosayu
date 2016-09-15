   <?php
/* @var $this IdguideprintsController */
/* @var $model Idguideprints */
/* @var $form CActiveForm */
?>

<div class="form">

<?php


$supplierScript=<<<EOS
	$('#prepare').click(function() {
		$('#command').val('batchcode');
    	$('#idguideprints-form').submit();  
	});

	$('#Idguideprints_papersize').change(function() {
		var ps = $('#Idguideprints_papersize').val();
		if (ps == 'A4') {
			$('#Idguideprints_paperwidth').val('215');
			$('#Idguideprints_paperheight').val('297');
			$('#Idguideprints_paperwidth').prop('readonly', true);
			$('#Idguideprints_paperheight').prop('readonly', true);
		} else if (ps == 'A5') {
			$('#Idguideprints_paperwidth').val('148');
			$('#Idguideprints_paperheight').val('210');
			$('#Idguideprints_paperwidth').prop('readonly', true);
			$('#Idguideprints_paperheight').prop('readonly', true);
		} else if (ps == 'custom') {
			$('#Idguideprints_paperwidth').prop('readonly', false);
			$('#Idguideprints_paperheight').prop('readonly', false);
		}
	});
EOS;
Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);
	
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'idguideprints-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/idguideprint/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'idguideprints-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/idguideprint/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'regnum');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Idguideprints[idatetime]',
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
		<?php echo $form->labelEx($model,'papersize'); ?>
        <?php 
           echo $form->dropDownList($model, 'papersize', array('A4'=>'A4', 'A5'=>'A5', 'custom'=>'Lainnya'), 
			array('empty'=>'Harap Pilih')); 
        ?>
        <?php echo $form->error($model,'papersize');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'paperwidth'); ?>
        <?php 
           echo $form->textField($model, 'paperwidth'); 
        ?>
        <?php echo $form->error($model,'paperwidth');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'paperheight'); ?>
        <?php 
           echo $form->textField($model, 'paperheight'); 
        ?>
        <?php echo $form->error($model,'paperheight');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'papersidem'); ?>
        <?php 
           echo $form->textField($model, 'papersidem'); 
        ?>
        <?php echo $form->error($model,'papersidem');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'paperbotm'); ?>
        <?php 
           echo $form->textField($model, 'paperbotm'); 
        ?>
        <?php echo $form->error($model,'paperbotm');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'labelwidth'); ?>
        <?php 
           echo $form->textField($model, 'labelwidth'); 
        ?>
        <?php echo $form->error($model,'labelwidth');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'labelheight'); ?>
        <?php 
           echo $form->textField($model, 'labelheight'); 
        ?>
        <?php echo $form->error($model,'labelheight');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'labelsidem'); ?>
        <?php 
           echo $form->textField($model, 'labelsidem'); 
        ?>
        <?php echo $form->error($model,'labelsidem');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'labelbotm'); ?>
        <?php 
           echo $form->textField($model, 'labelbotm'); 
        ?>
        <?php echo $form->error($model,'labelbotm');?> 
	</div>
	
	
<?php 
    if (isset(Yii::app()->session['Detailidguideprints'])) {
       $rawdata=Yii::app()->session['Detailidguideprints'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailidguideprints where id='$model->id'")->queryScalar();
       $sql="select * from detailidguideprints where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
              array(
                  'header'=>'Guide',
                  'name'=>'idguide',
              	  'value'=>"lookup::GuideNameFromID(\$data['idguide'])",
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
                  'updateButtonUrl'=>"Action::decodeUpdateDetailIdguidePrintUrl(\$data)",
              )
          ),
    ));
    
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->