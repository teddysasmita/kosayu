<?php
/* @var $this StockentriesController */
/* @var $model Stockentries */

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
	array('label'=>'Cetak', 'url'=>array('printOut1', 'id'=>$model->id), 'linkOptions'=>['target'=>'_blank']),
);
?>

<h1>Pembayaran Komisi Guide</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idguide',
			'value'=>lookup::GuideNameFromID($model->idguide),
		),
		array(
			'name'=>'commission',
			'type'=>'number'
		),
		array(
			'name'=>'amount',
			'type'=>'number'
		),
		array(
			'label'=>'Userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),
		'datetimelog',
	),
)); ?>

<?php 
	
   $count=Yii::app()->db->createCommand("select count(*) from detailguidepayments where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailguidepayments where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
         	array(
         		'header'=>'Nomor Sticker',
         		'name'=>'stickernum',
         	),
         	array(
               'header'=>'Nomor Invoice',
               'name'=>'regnum',
            ),
            array(
				'header'=>'Tanggal',
              	'name'=>'stickerdate',
            ),
         	array(
         		'header'=>'Barang',
         		'name'=>'iditem',
         		'value'=>"lookup::ItemNamefromItemID(\$data['iditem'])",
         	),
         	array(
         		'header'=>'Qty',
         		'name'=>'qty',
         		'type'=>'number',
         	),
         	array(
         		'header'=>'Komisi',
         		'name'=>'amount',
         		'type'=>'number',
         	),
         	array(
         		'header'=>'Kasir',
         		'name'=>'idcashier',
         		'value'=>"lookup::UserNameFromUserID(\$data['idcashier'])",
         	),
         		 
         ),
   ));
 ?>
 
 <?php 
   /*$count=Yii::app()->db->createCommand("select count(*) from detailtippayments2 where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailtippayments2 where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
            array(
               'header'=>'Nama Komisi',
               'name'=>'idtipgroup',
            	'value'=>"lookup::ItemTipGroupNameFromID(\$data['idtipgroup'])",
            ),
            array(
              'header'=>'Jumlah',
              'name'=>'amount',
              'type'=>'number',
            ),
         ),
   ));*/
 ?>
