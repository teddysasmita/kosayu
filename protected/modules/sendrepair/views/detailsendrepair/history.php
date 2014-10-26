<?php
/* @var $this DetailsendrepairsController */
/* @var $model Detailsendrepairs */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/sendrepairs/detailsendrepairs/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailsendrepairs', 'url'=>array('index')),
	//array('label'=>'Create Detailsendrepairs', 'url'=>array('create')),
);

?>

<h1>Detil Pengiriman Barang untuk Perbaikan</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailsendrepairs')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailsendrepairs-grid',
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
