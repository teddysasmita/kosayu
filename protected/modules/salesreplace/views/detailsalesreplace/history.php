<?php
/* @var $this DetailsalesreplaceController */
/* @var $model Detailsalesreplace */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/purchasesorder/detailsalesreplace/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailsalesreplace', 'url'=>array('index')),
	//array('label'=>'Create Detailsalesreplace', 'url'=>array('create')),
);

?>

<h1>Detil Perubahan Penjualan</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailsalesreplace')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailsalesreplace-grid',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCustomerUrl(\$data)",
		),
	),
)); ?>
