<?php
/* @var $this SendrepairsController */
/* @var $model Sendrepairs */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Data Detil yang dihapus', 
         'url'=>array('/purchasesorder/detailsendrepairs/deleted', 'id'=>$model->id)),
	/*array('label'=>'Ringkasan', 'url'=>array('summary', 'id'=>$model->id)),*/
	array('label'=>'Cetak', 'url'=>array('printlpb', 'id'=>$model->id))
);
?>

<h1>Pengiriman Barang untuk Perbaikan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idservicecenter',
			'value'=>lookup::CompanyNameFromServiceCenterID($model->idservicecenter),			
		),
		'duedate',
		array(
               'name'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailsendrepairs where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailsendrepairs where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
		array(
			'header'=>'Nama Barang',
			'name'=>'iditem',
			'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
		),
		array(
			'header'=>'Serialnum',
			'name'=>'serialnum',
		),
		array(
			'header'=>'Gudang',
			'name'=>'idwarehouse',
			'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
		),
		array(
			'header'=>'Keluar',
			'name'=>'exit',
			'value'=>"lookup::SendRepairExit(\$data)"
		),
		/*array(
			'header'=>'Catatan',
			'name'=>'remark',
		),*/
			array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                      'delete'=>array(
                       'visible'=>'false'
                      ),
                     'update'=>array(
                        'visible'=>'false'
                     ),
					/*'view'=>array(
                        'visible'=>'false'
                     ),*/
                  ),
                  'viewButtonUrl'=>"Action::decodeViewDetailReturStockUrl(\$data)",
              )
         ),
   ));	
 ?>
 
