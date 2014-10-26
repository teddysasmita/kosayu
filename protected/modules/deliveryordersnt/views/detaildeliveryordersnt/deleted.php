<?php
/* @var $this DetailsalesordersController */
/* @var $model Detailsalesorders */

$this->breadcrumbs=array(
   'Daftar'=>array('default/index/'),
   'Lihat Data'=>array('default/view', 'id'=>$id),
   'Detil Data yang telah terhapus',
);

$this->menu=array(
	/*
       array('label'=>'List Detailsalesorders', 'url'=>array('index')),
	array('label'=>'Create Detailsalesorders', 'url'=>array('create')),
       */
);

?>

<h1>Detil Pemesanan oleh Pelanggan</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()
       ->select('a.*')->from('detailsalesorders a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action and a.id=:id', array(':action'=>'d', ':id'=>"$id"))->queryAll();
    
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'salesorders-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		array(
                   'name'=>'Nama Barang',
                   'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])",
                ),
		'qty',
		'discount',
		'price',
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
                   'updateButtonUrl'=>"Action::decodeRestoreDeletedDetailsalesorderUrl(\$data)",
		),
	),
    )); 
?>
