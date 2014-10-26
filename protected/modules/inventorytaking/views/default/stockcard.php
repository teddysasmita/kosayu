<?php
/* @var $this InventorytakingsController */
/* @var $model Inventorytakings */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
      'Lihat Data'=>array('view', 'id'=>$model->id),
	'Print Kartu Stok'
);

$this->menu=array(
	/*
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	*/
);
?>

<h1>Stok Opname</h1>

<?php 
	echo CHtml::form(Yii::app()->createUrl('inventorytaking/default/printstockcard2'));
	
	$dataProvider=new CArrayDataProvider($detailData, array(
		'totalItemCount'=>count($detailData),
		'keyField'=>'iditem',
		'pagination'=>array(
			'pageSize'=>25,
		),	
	));
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
               array(
                   'header'=>'Nama Barang',
                   'name'=>'iditem',
                   'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
               ),
               array(
                  'header'=>'Total 	Qty',
                  'type'=>'number',
                  'name'=>'totalqty',
               ),
               array(
                  'header'=>'Gudang',
                  'name'=>'idwarehouse',
				'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
               ), 
               /*array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                     'delete'=>array(
                        'visible'=>'false'
                      ),
                     'view'=>array(
                        'visible'=>'false'
                     )
                  ),
                  'updateButtonUrl'=>"Action::decodePrintStockCardUrl(\$data)",
               )*/
				array(
					'class'=>'CCheckBoxColumn',
					'selectableRows'=>'2',
					'value'=>"Action::decodePrintStockCard2(\$data)"
				)
	),
)); 

	echo CHtml::submitButton('Print');
	echo CHtml::endForm();
?>
