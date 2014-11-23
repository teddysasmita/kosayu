<?php
/* @var $this StockentriesController */
/* @var $model Stockentries */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Detail',
);

$this->menu=array(
	
);
?>

<h1>Pembayaran Komisi Agen</h1>

<?php 
	$dataProvider=new CArrayDataProvider($detail,
   		array(
			'totalItemCount'=>count($detail),
   			'pagination'=>array(
   				'pageSize'=>20,	
   			),
		));
	
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
            array(
				'header'=>'No Faktur',
				'name'=>'regnum',
			),
			array(
				'header'=>'Nama Barang',
				'name'=>'iditem',
				'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])",
			),
			array(
				'header'=>'Harga',
				'name'=>'price',
				'type'=>'number',
			),
			array(
				'header'=>'Discount',
				'name'=>'discount',
				'type'=>'number',
			),
			array(
				'header'=>'Qty',
				'name'=>'qty',
				'type'=>'number',
			),
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
   ));
 ?>

 