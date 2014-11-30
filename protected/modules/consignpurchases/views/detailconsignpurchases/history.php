<?php
/* @var $this DetailconsignpurchasesController */
/* @var $model Detailconsignpurchasesorders */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/consignpurchasesorder/detailconsignpurchasesorders/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailconsignpurchasesorders', 'url'=>array('index')),
	//array('label'=>'Create Detailconsignpurchasesorders', 'url'=>array('create')),
);

?>

<h1>Pembelian Konsinyasi dari Pemasok</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailconsignpurchasesorders')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailconsignpurchasesorders-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
		'idunit',
      'price',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCustomerUrl(\$data)",
		),
	),
)); ?>
