<?php
/* @var $this StickertoguidesController */
/* @var $model Stickertoguides */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/masterdata'),
    'Daftar'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Sejarah',
);

$this->menu=array(
	//array('label'=>'List Stickertoguides', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Pencatatan Sticker ke Guide</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()->
       select()->from('Stickertoguides')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'Stickertoguides-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'regnum',
		'stickernum',
		'stickerdate',
		'userlog',
		'datetimelog',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryStickertoguideUrl(\$data)",
		),
	),
    )); 
?>
