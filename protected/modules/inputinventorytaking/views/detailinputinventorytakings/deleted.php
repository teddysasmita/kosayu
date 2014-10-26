<?php
/* @var $this DetailinputinventorytakingsController */
/* @var $model Detailinputinventorytakings */

$this->breadcrumbs=array(
   'Daftar'=>array('default/index/'),
   'Lihat Data'=>array('default/view', 'id'=>$id),
   'Detil Data yang telah terhapus',
);

$this->menu=array(
	/*
       array('label'=>'List Detailinputinventorytakings', 'url'=>array('index')),
	array('label'=>'Create Detailinputinventorytakings', 'url'=>array('create')),
       */
);

?>

<h1>Input Stok Opname</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()
       ->select('a.*')->from('detailinputinventorytakings a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action and a.id=:id', array(':action'=>'d', ':id'=>"$id"))->queryAll();
    
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inputinventorytakings-grid',
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
                   'updateButtonUrl'=>"Action::decodeRestoreDeletedDetailinputinventorytakingUrl(\$data)",
		),
	),
    )); 
?>
