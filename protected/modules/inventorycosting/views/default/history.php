<?php
/* @var $this InventorycostingsController */
/* @var $model Inventorycostings */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Sejarah',
);

$this->menu=array(
	//array('label'=>'List Inventorycostings', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Pelanggan</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()->
       select()->from('inventorycostings')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inventorycostings-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'firstname',
		'lastname',
		'address',
		'phone',
		'email',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryInventorycostingUrl(\$data)",
		),
	),
    )); 
?>
