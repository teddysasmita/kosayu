<?php
/* @var $this PaysalariesController */
/* @var $model Paysalaries */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $employees=Yii::app()->db->createCommand()
      ->select('id, firstname, lastname')
      ->from('employees')
      ->order('firstname, lastname')
      ->queryAll();
   foreach($employees as $row) {
      $employeeids[]=$row['id'];
      $employeenames[]=$row['firstname'].' '.$row['lastname'];
   }
   $employeeids=CJSON::encode($employeeids);
   $employeenames=CJSON::encode($employeenames);
   $employeeScript=<<<EOS
      var employeeids=$employeeids;
      var employeenames=$employeenames;
      $('#employeename').change(function() {
         var activename=$('#employeename').val();
         $('#Paysalaries_idemployee').val(
            employeeids[employeenames.indexOf(activename)]);
      });
   
		$('#Paysalaries_idorder').change(function() {
         	$('#command').val('setPO');
   			$('#paysalaries-form').submit();
      	});
EOS;
   Yii::app()->clientScript->registerScript("employeeScript", $employeeScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'paysalaries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/paysalaries/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'paysalaries-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/paysalaries/default/update", array('id'=>$model->id))
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
                  'name'=>'Paysalaries[idatetime]',
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
		<?php echo $form->labelEx($model,'idemployee'); ?>
		<?php 
               $employees=Yii::app()->db->createCommand()
                  ->select("id,firstname,lastname")
                  ->from("employees")
                  ->order("firstname, lastname")   
                  ->queryAll();
               foreach($employees as $row) {
                  $employeename[]=$row['firstname'].' '.$row['lastname'];
               }
               $this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                   'name'=>'employeename',
                   'source'=>$employeename,
                 'value'=>lookup::EmployeeNameFromID($model->idemployee)
               ));
            ?>
		<?php echo $form->error($model,'idemployee'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'startdate'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Paysalaries[startdate]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->startdate
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->startdate,
               ));
            ?>
		<?php echo $form->error($model,'startdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enddate'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Paysalaries[enddate]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->enddate
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->enddate,
               ));
            ?>
		<?php echo $form->error($model,'enddate'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'presence'); ?>
		<?php echo $form->textField($model,'presence'); ?>
		<?php echo $form->error($model,'presence'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'overtime'); ?>
		<?php echo $form->textField($model,'overtime'); ?>
		<?php echo $form->error($model,'overtime'); ?>
	</div>
	

<?php 
    if (isset(Yii::app()->session['Detailpaysalaries'])) {
       $rawdata=Yii::app()->session['Detailpaysalaries'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailpaysalaries where id='$model->id'")->queryScalar();
       $sql="select * from detailpaysalaries where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
    	'keyField'=>'iddetail'
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
			array(
            	'header'=>'Komponen',
            	'name'=>'name',
            ),
            array(
               'header'=>'Jumlah',
               'name'=>'amount',
            ),
            array(
               'class'=>'CButtonColumn',
               'buttons'=> array(
                  'view'=>array(
                     'visible'=>'false'
                  )
               ),
            	'deleteButtonUrl'=>"Action::decodeDeleteDetailPaysalariesUrl(\$data, $model->regnum)",
               'updateButtonUrl'=>"Action::decodeUpdateDetailPaysalariesUrl(\$data, $model->regnum)",
            )
          ),
    ));
?> 
   
   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->