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
   
	 $('#countWage').click(
         function(event) {
            $('#command').val('countWage');
            mainform=$('#paysalaries-form');
            mainform.submit();
            event.preventDefault();
         }
      ); 
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
        echo $form->hiddenField($model, 'idemployee');
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
		<?php echo $form->labelEx($model,'pmonth'); ?>
		<?php
			$pmonth = array('1'=>'Januari', '2'=>'Februari', '3'=>'Maret', '4'=>'April',
				'5'=>'Mei', '6'=>'Juni', '7'=>'Juli', '8'=>'Agustus', '9'=>'September',
				'10'=>'Oktober', '11'=>'Nopember', '12'=>'Desember'
			);
			echo $form->dropDownList($model, 'pmonth', $pmonth); ?>
		<?php echo $form->error($model,'pmonth'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'pyear'); ?>
		<?php 
			for($i=$model->pyear-3; $i < $model->pyear+3; $i++)
				$pyear[$i] = $i;
			echo $form->dropDownList($model,'pyear', $pyear); 
		?>
		<?php echo $form->error($model,'pyear'); ?>
	</div>
		
	<div class="row">
		<?php echo $form->labelEx($model,'presence'); ?>
		<?php echo $form->textField($model,'presence'); ?>
		<?php echo $form->error($model,'presence'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'nonworkingdays'); ?>
		<?php echo $form->textField($model,'nonworkingdays'); ?>
		<?php echo $form->error($model,'nonworkingdays'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'isthr'); ?>
		<?php echo $form->dropDownList($model,'isthr', array('1'=>'Ya', '0'=>'Tidak')); ?>
		<?php echo $form->error($model,'isthr'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'transport'); ?>
		<?php echo $form->textField($model,'transport'); ?>
		<?php echo $form->error($model,'transport'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'overtime'); ?>
		<?php echo $form->textField($model,'overtime'); ?>
		<?php echo $form->error($model,'overtime'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'lunch'); ?>
		<?php echo $form->textField($model,'lunch'); ?>
		<?php echo $form->error($model,'lunch'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'late'); ?>
		<?php echo $form->textField($model,'late'); ?>
		<?php echo $form->error($model,'late'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'earlystop'); ?>
		<?php echo $form->textField($model,'earlystop'); ?>
		<?php echo $form->error($model,'earlystop'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'receivable'); ?>
		<?php echo $form->textField($model,'receivable'); ?>
		<?php echo $form->error($model,'receivable'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'payment'); ?>
		<?php echo $form->textField($model,'payment'); ?>
		<?php echo $form->error($model,'payment'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'bpjs'); ?>
		<?php echo $form->textField($model,'bpjs'); ?>
		<?php echo $form->error($model,'bpjs'); ?>
	</div>
	
	<div class="row buttons">
      <?php echo CHtml::button('Hitung Gaji', array( 'id'=>'countWage'));   
      	if (isset($checkerror))
      		echo CHtml::tag('span', array('id'=>'checkerror', 
				'class'=>'error'), $checkerror);	
      ?>
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
            	'name'=>'componentname',
				'value'=>"lookup::getComponentName(\$data['componentname'])",
            ),
            array(
               'header'=>'Jumlah',
               'name'=>'amount',
            	'type'=>'number',
            ),
            array(
               'class'=>'CButtonColumn',
               'buttons'=> array(
                  'view'=>array(
                     'visible'=>'false'
                  )
               ),
            	'deleteButtonUrl'=>"Action::decodeDeleteDetailPaysalaryUrl(\$data, $model->regnum)",
               'updateButtonUrl'=>"Action::decodeUpdateDetailPaysalaryUrl(\$data, $model->regnum)",
            )
          ),
    ));
?> 
	
	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo CHtml::tag('span', array('id'=>'totalro', 
				'class'=>'money'), number_format($model->total)); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>
	
   
   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->