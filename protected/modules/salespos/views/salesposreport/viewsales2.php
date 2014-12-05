<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/masterdata'),
	'Laporan Detil Pendapatan Kasir'=>array('salesposreport/create2'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Laporan Detil Pendapatan Kasir</h1>

<?php 
	
	$dataProvider=new CArrayDataProvider($data,array(
		'totalItemCount'=>count($data),
		'pagination'=>array(
				'pageSize'=>20,
			),
	));
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
				array(
						'header'=>'Tanggal',
						'name'=>'idate',
				),
				array(
						'header'=>'Nomor Nota',
						'name'=>'regnum',
				),
				array(
						'header'=>'Nomor Sticker',
						'name'=>'idsticker',
				),
				array(
						'header'=>'Total Nota',
						'name'=>'totalsales',
						'type'=>'number',
				),
				array(
						'header'=>'Kembalian',
						'name'=>'cashreturn',
				),
				array(
						'header'=>'Tunai',
						'name'=>'cash',
						'type'=>'number',
				),
				array(
						'header'=>'Kartu Debit',
						'name'=>'debitcard',
						'type'=>'number',
				),
				array(
						'header'=>'Kartu Kredit',
						'name'=>'creditcard',
						'type'=>'number',
				),
				array(
						'header'=>'Voucher',
						'name'=>'voucher',
						'type'=>'number',
				),				
		),
));
	
?>
<H1>
Total: <?php
	$total = 0; 
	foreach($data as $d) {
		if ($d['idrate'] !== 'NA') 
			$amount = $d['total'] * lookup::CurrRateFromID($d['idrate']);
		else
			$amount = $d['total'];
		$total = $total + $amount;
	};
	echo ' Rp '.number_format($total);
?>

</H1>