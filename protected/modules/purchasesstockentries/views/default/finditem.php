<?php

	$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar'=>array('index'),
		'Cari LPB berdasar Jenis Barang',
	);

	$this->menu=array();

	$jq=<<<EOH
   $('#adddetail').click(function(event){
     var mainform;
     var hiddenvar;
     mainform=$('#purchasesstockentries-form');
     $('#command').val('adddetail');
     mainform.submit();
     event.preventDefault();
   });
EOH;
	Yii::app()->clientScript->registerScript('myscript', $jq, CClientScript::POS_READY);
?>

<h1>Pencarian LPB berdasarkan Jenis Barang</h1>

<div class="form">

	<?php 
		echo CHtml::beginForm(Yii::app()->createUrl("/purchasesstockentries/default/finditem"), 'get');
	?>
	
<p class="note">Masukkan Jenis Barang yg dicari.</p>
	
	<div class="row">
	<?php echo CHtml::label('Jenis Barang', FALSE); ?>
	<?php

		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'name'=>'iditem',
			'sourceUrl'=> Yii::app()->createUrl('LookUp/getItem2'),
			'htmlOptions'=>array(
				'style'=>'height:20px;width:200px',
			),
			'value'=>$iditem,
		));
	?>
	</div>

	<div class="row buttons">
      <?php echo CHtml::submitButton("Cari"); ?>
   </div>
   
	<?php 
		echo CHtml::endForm();
	?>

	
</div> <!-- form -->
	
<h2><?php echo lookup::ItemNameFromItemID($iditem). " - $iditem" ?></h2>

	<?php 
	if (isset($founddata)) {
		$count=count($founddata);
	
		$dataProvider=new CArrayDataProvider($founddata, array(
			'totalItemCount'=>$count,
			'keyField'=>'iddetail'
		));
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider,
			'columns'=>array(
					array(
						'header'=>'Tanggal',
						'name'=>'idatetime',
						
					),
					array(
						'header'=>'No LPB',
						'name'=>'regnum',
					),
					array(
						'header'=>'No PO',
						'name'=>'ponum',
					),
					array(
						'header'=>'Qty',
						'name'=>'qty',
					),
					array(
						'header'=>'Harga Beli',
						'name'=>'buyprice',
						'type'=>'number'
					),
					array(
						'header'=>'Nomor Seri',
						'name'=>'serialnums',
						'type'=>'ntext'
					),
					array(
						'header'=>'Pemasok',
						'name'=>'suppliername',
						'type'=>'ntext'
					),
			),
		));
	};
	
	?>