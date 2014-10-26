<?php
/* @var $this RequestdisplaysController */
/* @var $model Requestdisplays */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar'=>array('index'),
    'Data yang telah terhapus',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Permintaan Barang Display</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()
       ->select('a.*')->from('requestdisplays a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'requestdisplays-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'idatetime',
		'regnum',
		'receivername',
		'vehicleinfo',
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
                   'updateButtonUrl'=>"Action::decodeRestoreDeletedSalesorderUrl(\$data)",
		),
	),
    )); 
?>
