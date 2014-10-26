<?php
/* @var $this InputinventorytakingsController */
/* @var $model Inputinventorytakings */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Input Stok Opname</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()->
       select()->from('inputinventorytakings')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'inputinventorytakings-grid',
		'dataProvider'=>$ap,
		'columns'=>array(
			'idatetime',
			'regnum',
			'idinventorytaking',
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
				'updateButtonUrl'=>"Action::decodeRestoreHistoryInputinventorytakingUrl(\$data)",
			),
		),
    )); 
?>
