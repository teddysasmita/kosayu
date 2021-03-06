<?php
/* @var $this DetailstockexitsController */
/* @var $model Detailstockexits */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/stockexits/detailstockexits/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailstockexits', 'url'=>array('index')),
	//array('label'=>'Create Detailstockexits', 'url'=>array('create')),
);

?>

<h1>Pengeluaran Barang</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailstockexits')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailstockexits-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
		'sernum',
		/*
		'price',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCustomerUrl(\$data)",
		),
	),
)); ?>
