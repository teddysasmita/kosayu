   <?php
/* @var $this ItemtipgroupsController */
/* @var $model Itemtipgroups */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'itemtipgroups-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/itemtipgroup/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'itemtipgroups-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/itemtipgroup/default/update", array('id'=>$model->id))
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
		<?php echo $form->labelEx($model,'pct'); ?>
        <?php 
           echo $form->textField($model, 'pct'); 
        ?>
        <?php echo $form->error($model,'pct');?> 
	</div>
	  
<?php 
    if (isset(Yii::app()->session['Detailitemtipgroups'])) {
       $rawdata=Yii::app()->session['Detailitemtipgroups'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailitemtipgroups where id='$model->id'")->queryScalar();
       $sql="select * from detailitemtipgroups where id='$model->id'";
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
               	  'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])",
               ),
               array(
                  'class'=>'CButtonColumn',
                  'updateButtonUrl'=>"Action::decodeUpdateDetailItemtipGroupUrl(\$data)",
               		'deleteButtonUrl'=>"Action::decodeUpdateDetailItemtipGroupUrl(\$data)",
               		'viewButtonUrl'=>"Action::decodeUpdateDetailItemtipGroupUrl(\$data)",
               )
          ),
    ));
    
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->