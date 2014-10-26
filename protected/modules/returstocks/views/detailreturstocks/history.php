<?php
/* @var $this DetailreturstocksController */
/* @var $model Detailreturstocks */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/returstocks/detailreturstocks/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailreturstocks', 'url'=>array('index')),
	//array('label'=>'Create Detailreturstocks', 'url'=>array('create')),
);

?>

<h1>Detil Pengembalian Barang ke Pemasok</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailreturstocks')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailreturstocks-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
		'idunit',
      'price',
      'cost1',
      'cost2',
		'discount',
      'qty',
		/*
		
		'userlog',
		'datetimelog',
		*/
		array(
                    'class'=>'CButtonColumn',
                   'buttons'=> array(
                        'view'=>array(
                            'visible'=>'false',
                        ),
                        'delete'=>array(
                          'visible'=>'false',
                        ),
                    ),
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryDetailPurchasesStockEntriesUrl(\$data)",
		),
	),
)); ?>
