<?php
/* @var $this InventorycostingsController */
/* @var $model Inventorycostings */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Data yang telah Terhapus',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Penentuan Harga Pokok Opname</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()
       ->select('a.*')->from('inventorycostings a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inventorycostings-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		//'id',
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
                   'updateButtonUrl'=>"Action::decodeRestoreDeletedInventorycostingUrl(\$data)",
		),
	),
    )); 
?>
