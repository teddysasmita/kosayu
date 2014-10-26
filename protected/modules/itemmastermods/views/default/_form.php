   <?php
/* @var $this ItemmastermodsController */
/* @var $model Itemmastermods */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $transScript=<<<EOS
	$('#processbtn').click(
	function(evnt) {
		$('#command').val('scanItems');	
		$('#itemmastermods-form').submit();
	});
		
	$('#Itemmastermods_idwarehouse').change(
	function() {
		$.getJSON('index.php?r=LookUp/checkItemQty', {iditem: $('#Itemmastermods_iditemprevious').val(),
			idwh: $('#Itemmastermods_idwarehouse').val()},
		function(data) {
			if (data == 0 ) {
				$('#validdata').val('false');
				$('#previousinfo').addClass('errorMessage');
				$('#previousinfo').removeClass('money');
				$('#previousinfo').html('Jumlah barang tidak cukup pada gudang tersebut.');
			} else {
				$('#validdata').val('true');
				$('#previousinfo').html('');
			}
		})
	});
   
	$('#Itemmastermods_itemname').focus(function(){
		$('#ItemDialog').dialog('open');
	});
      
	$('#dialog-item-name').change(
		function(){
			$.getJSON('index.php?r=LookUp/getItem',{ name: $('#dialog-item-name').val() },
			function(data) {
				$('#dialog-item-select').html('');
				var ct=0;
				while(ct < data.length) {
					$('#dialog-item-select').append(
						'<option value='+data[ct]+'>'+unescape(data[ct])+'</option>'
					);
					ct++;
				}
			})
		}
	);
		
	$('#dialog-item-select').click(
		function(){
			$('#dialog-item-name').val(unescape($('#dialog-item-select').val()));
		}
	);
   
	$('#Itemmastermods_itemname2').focus(function(){
		$('#ItemDialog2').dialog('open');
	});
      
	$('#dialog-item-name2').change(
		function(){
			$.getJSON('index.php?r=LookUp/getItem',{ name: $('#dialog-item-name2').val() },
			function(data) {
				$('#dialog-item-select2').html('');
				var ct=0;
				while(ct < data.length) {
					$('#dialog-item-select2').append(
						'<option value='+data[ct]+'>'+unescape(data[ct])+'</option>'
					);
					ct++;
				}
			})
		}
	);
		
	$('#dialog-item-select2').click(
		function(){
			$('#dialog-item-name2').val(unescape($('#dialog-item-select2').val()));
		}
	);
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'itemmastermods-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/itemmastermods/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'itemmastermods-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/itemmastermods/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
      	echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'idatetime');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'iditemnext');
        echo $form->hiddenField($model, 'iditemprevious');
      ?>
     
	<div class="row">
		<?php echo $form->labelEx($model,'iditemprevious'); ?>
		<?php 
			echo CHtml::textField('Itemmastermods_itemname', lookup::ItemNameFromItemID($model->iditemprevious), 
				array('size'=>50));   
			
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id'=>'ItemDialog',
				'options'=>array(
					'title'=>'Pilih Barang', 'autoOpen'=>false,
					'height'=>300, 'width'=>600, 'modal'=>true,
					'buttons'=>array(
						array('text'=>'Ok', 'click'=>'js:function(){
							$(\'#Itemmastermods_itemname\').val($(\'#dialog-item-name\').val());
							$.get(\'index.php?r=LookUp/getItemID\',{ name: encodeURI($(\'#dialog-item-name\').val()) },
							function(data) {
								$(\'#Itemmastermods_iditemprevious\').val(data);
							})
                            $(this).dialog("close");
						}'),
						array('text'=>'Close', 'click'=>'js:function(){
							$(this).dialog("close");
						}'),
					),
				),
			));
			$myd=<<<EOS
         	<div><input type="text" name="itemname" id="dialog-item-name" size='50'/></div>
            <div><select size='8' width='100' id='dialog-item-select'>   
                <option>Harap Pilih</option>
            </select>           
            </div>
            </select>           
EOS;
			echo $myd;
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		?>
		<?php echo $form->error($model,'iditemprevious'); ?>
	</div>
	
	<div class="row">
		<?php 
			echo CHtml::tag('span', array('id'=>'previousinfo', 'class'=>'errorMessage'));
		?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'iditemnext'); ?>
		<?php 
			echo CHtml::textField('Itemmastermods_itemname2', 
				lookup::ItemNameFromItemID($model->iditemnext) , array('size'=>50));   
			
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id'=>'ItemDialog2',
				'options'=>array(
					'title'=>'Pilih Barang', 'autoOpen'=>false,
					'height'=>300, 'width'=>600, 'modal'=>true,
					'buttons'=>array(
						array('text'=>'Ok', 'click'=>'js:function(){
							$(\'#Itemmastermods_itemname2\').val($(\'#dialog-item-name2\').val());
							$.get(\'index.php?r=LookUp/getItemID\',{ name: encodeURI($(\'#dialog-item-name2\').val()) },
							function(data) {
								$(\'#Itemmastermods_iditemnext\').val(data);
							})
                            $(this).dialog("close");
						}'),
						array('text'=>'Close', 'click'=>'js:function(){
							$(this).dialog("close");
						}'),
					),
				),
			));
			$myd=<<<EOS
         	<div><input type="text" name="itemname2" id="dialog-item-name2" size='50'/></div>
            <div><select size='8' width='100' id='dialog-item-select2'>   
                <option>Harap Pilih</option>
            </select>           
            </div>
            </select>           
EOS;
			echo $myd;
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		?>
		<?php echo $form->error($model,'iditemnext'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::Button('Process', array('id'=>'processbtn')); ?>
	</div>
      
	<div class="row">
        <?php 
           	if (strlen($error) > 0)
        		echo CHtml::tag('span', array('class'=>'errorMessage'), $error); 
        ?>
	</div>
	
	<?php 
  
    if (isset(Yii::app()->session['Detailitemmastermods'])) {
       $rawdata=Yii::app()->session['Detailitemmastermods'];
       $count=count($rawdata);
    } else {
       $rawdata=Yii::app()->db->createCommand()
			->select('*')
			->from('detailitemmastermods')
			->where('id = :p_id', array(':p_id'=>$model->id))
       		->queryAll();
       $count = count($rawdata);
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
		'keyField'=>'iddetail'
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
				'serialnum',
				array(
					'name'=>'status',
					'value'=>"lookup::StockStatusName(\$data['status'])"
				),
				array(
					'name'=>'idwarehouse',
					'header'=>'Gudang',
					'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
				),
			),
    	));
?>
	
	<div class="row">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>
      
<?php $this->endWidget(); ?>


      
</div><!-- form -->