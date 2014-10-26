<?php
/* @var $this DetailacquisitionsController */
/* @var $model Detailacquisitions */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/acquisitions/detailacquisitions/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailacquisitions', 'url'=>array('index')),
	//array('label'=>'Create Detailacquisitions', 'url'=>array('create')),
);

?>

<h1>Detil Akuisisi Barang dan Nomor Seri</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailacquisitions')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailacquisitions-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'serialnum',
		'avail',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryDetailAcquisitionsUrl(\$data)",
		),
	),
)); ?>
