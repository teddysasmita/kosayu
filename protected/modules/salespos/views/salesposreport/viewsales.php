<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Laporan Pendapatan Kasir'=>array('salesposreport/create'),
	'Lihat Data'
);

$this->menu=array(
	
);
?>

<h1>Laporan Pendapatan Kasir</h1>

<h2>
<?php
	echo "Periode ".$startdate." hingga ".$enddate;
?>
</h2>

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
						'header'=>'Kasir',
						'name'=>'idcashier',
						'value'=>"lookup::UserNameFromUserID(\$data['idcashier'])",
				),
				array(
						'header'=>'Metode',
						'name'=>'method',
						'value'=>"lookup::getMethod(\$data['method'])",
				),
				array(
						'header'=>'Kurs',
						'name'=>'idcurr',
						'value'=>"lookup::CurrSymbolFromID(\$data['idcurr'])",
				),
				array(
						'header'=>'Nilai Tukar',
						'name'=>'idrate',
						'value'=>"lookup::CurrRateFromID(\$data['idrate'])",
				),
				array(
						'header'=>'Jumlah',
						'name'=>'total',
						'type'=>'number',
				),
				array(
						'header'=>'Kembalian',
						'name'=>'cashreturn',
						'type'=>'number',
				),
		),
));
	
?>

<?php
	$total = array();
	$temp['id'] = 1;
	$temp['tunai'] = 0;
	$temp['kembalian'] = 0;
	$temp['kartukredit'] = 0;
	$temp['kartudebit'] = 0;
	$temp['voucher'] = 0;
	$temp['retur'] = 0;
	$total[] = $temp;
	
	foreach($data as $d) {
		if ($d['method'] == 'KK') {
			$total[0]['kartukredit'] += $d['total'];
		} else if ($d['method'] == 'KD') {
			$total[0]['kartudebit'] += $d['total'];
		} else if ($d['method'] == 'V') {
			$total[0]['voucher'] += $d['total'];
		} else if ($d['method'] == 'R') {
			$total[0]['retur'] += $d['total'];
		} else if ($d['method'] == 'C') {
			if ($d['idrate'] == 'NA')
				$rate = 1;
			else $rate = lookup::CurrRateFromID($d['idrate']);
			$total[0]['tunai'] += $d['total'] * $rate;
			//$total[0]['kembalian'] += $d['cashreturn'];
			$total[0]['kembalian'] += 0;
		} 
	}
	
	$dataProvider2=new CArrayDataProvider($total,array(
			'totalItemCount'=>count($total),
	));
	
	$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider2,
			'columns'=>array(
					array(
							'header'=>'Total Kembalian',
							'name'=>'kembalian',
							'type'=>'number',
					),
					array(
							'header'=>'Total Tunai',
							'name'=>'tunai',
							'type'=>'number',
					),
					array(
							'header'=>'Total Kartu Debit',
							'name'=>'kartudebit',
							'type'=>'number',
					),
					array(
							'header'=>'Total Kartu Kredit',
							'name'=>'kartukredit',
							'type'=>'number',
					),
					array(
							'header'=>'Total Voucher',
							'name'=>'voucher',
							'type'=>'number',
					),
					array(
							'header'=>'Total Retur',
							'name'=>'retur',
							'type'=>'number',
					),
			),
	));
	
	$grandtotal = $total[0]['tunai'] + $total[0]['kartukredit'] + $total[0]['kartudebit'] +
		$total[0]['voucher'] + $total[0]['retur'] - $total[0]['kembalian'];
	
	echo CHtml::tag('h1', array(), "Total: ".number_format($grandtotal));
?>
