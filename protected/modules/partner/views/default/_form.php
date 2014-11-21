   <?php
/* @var $this PartnersController */
/* @var $model Partners */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'partners-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/partner/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'partners-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/partner/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
      ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
        <?php 
           echo $form->textField($model, 'name'); 
        ?>
        <?php echo $form->error($model,'name');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'defaulttip'); ?>
        <?php 
           echo $form->textField($model, 'defaulttip'); 
        ?>
        <?php echo $form->error($model,'defaulttip');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
        <?php 
           echo $form->textField($model, 'address'); 
        ?>
        <?php echo $form->error($model,'address');?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'phone'); ?>
        <?php 
           echo $form->textField($model, 'phone'); 
        ?>
        <?php echo $form->error($model,'phone');?> 
	</div>
	  
<?php 
    if (isset(Yii::app()->session['Detailpartners'])) {
       $rawdata=Yii::app()->session['Detailpartners'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailpartners where id='$model->id'")->queryScalar();
       $sql="select * from detailpartners where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
               array(
                  'header'=>'Komposisi',
                  'name'=>'comname',
               ),
            	array(
            		'header'=>'Komisi(%)',
            		'name'=>'tip',
				),
               array(
                  'class'=>'CButtonColumn',
                  'updateButtonUrl'=>"Action::decodeUpdateDetailPartnerUrl(\$data)",
               		'deleteButtonUrl'=>"Action::decodeUpdateDetailPartnerUrl(\$data)",
               		'viewButtonUrl'=>"Action::decodeUpdateDetailPartnerUrl(\$data)",
               )
          ),
    ));
    
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->