<?php
/* @var $this DetailitemtipgroupsController */
/* @var $model Detailitemtipgroups */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/itemtipgroups/detailitemtipgroups/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailitemtipgroups', 'url'=>array('index')),
	//array('label'=>'Create Detailitemtipgroups', 'url'=>array('create')),
);

?>

<h1>Kelompok Komisi Barang</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailitemtipgroups')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailitemtipgroups-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryItemtipGroupUrl(\$data)",
		),
	),
)); ?>
