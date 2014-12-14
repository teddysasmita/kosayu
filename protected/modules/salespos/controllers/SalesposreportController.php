<?php

class SalesposreportController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
      public $layout='//layouts/column2';
      public $formid='AD1';
      public $tracker;
      public $state;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function actionCreate()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
		
			$this->render('create');
		} else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         };
	}
	
	public function actionCreate2()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
					$this->trackActivity('v');
	
					$this->render('create2');
				} else {
					throw new CHttpException(404,'You have no authorization for this operation.');
				};
	}
	
	public function actionCreate3()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
	
			Yii::app()->session->remove('datasales3');
			$this->render('create3');
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionCreate4()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
					$this->trackActivity('v');
	
					Yii::app()->session->remove('datasales4');
					$this->render('create4');
				} else {
					throw new CHttpException(404,'You have no authorization for this operation.');
				};
	}
	
	public function actionGetsales($startdate, $enddate, $idcashier )
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			if (!isset($idcashier) || ($idcashier == '')) 
				$idcashier = '%';

			$sql1 =<<<EOS
	select a.id, a.idatetime, left(a.idatetime, 10) as idate, a.userlog as idcashier, a.method, 
	a.idcurr, a.idrate, sum(a.amount) as total
	from posreceipts a
	where a.userlog like '$idcashier' 
	and a.idatetime >= '$startdate' and a.idatetime <= '$enddate'
	group by idate, a.userlog, a.method, a.idcurr
	order by idate, a.userlog, a.method, a.idcurr
EOS;
			$datareceipt = Yii::app()->db->createCommand($sql1)->queryAll();
			
			$sql2 =<<<EOS
	select left(a.idatetime, 10) as idate, a.userlog as idcashier, sum(a.cashreturn) as totalreturn
	from posreceipts b
	join salespos a on a.id = b.idpos
	where a.userlog like '$idcashier'
	and a.idatetime >= '$startdate' and a.idatetime <= '$enddate'
	and (b.method = 'C' or b.method = 'R')
	group by idate, a.userlog
	order by idate, a.userlog
EOS;
			$datacashreturn = Yii::app()->db->createCommand($sql2)->queryAll();
			
			foreach($datareceipt as & $sd) {
				if ( $sd['method'] == 'C' && $sd['idrate'] == 'NA') {
					foreach($datacashreturn as $dc) {
						if (($dc['idcashier'] == $sd['idcashier']) && 
							($dc['idate'] == $sd['idate'])) {
							//$sd['total'] = $sd['total'] - $dc['totalreturn'];
							$sd['cashreturn'] = $dc['totalreturn'];
							break;
						} 
					} 
				} else
					$sd['cashreturn'] = 0;
			}
			unset($sd);
			$this->render('viewsales', array('data'=>$datareceipt,'startdate'=>$startdate, 'enddate'=>$enddate));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetSales2($startdate, $enddate, $idcashier )
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			if (!isset($idcashier) || ($idcashier == ''))
				$idcashier = '%';
			
			$sql1 =<<<EOS
	select a.id, a.idatetime, left(a.idatetime, 10) as idate, a.userlog as idcashier, a.method,
	a.idcurr, a.idrate, a.amount, a.idpos, b.total, b.cashreturn, b.regnum, b.idsticker
	from posreceipts a
	join salespos b on b.id = a.idpos
	where a.userlog like '$idcashier'
	and a.idatetime >= '$startdate' and a.idatetime <= '$enddate'
	order by a.idatetime
EOS;
			$datareceipt = Yii::app()->db->createCommand($sql1)->queryAll();
			
			$realdata = array();
			foreach($datareceipt as $dr) {
				$found = FALSE;
				foreach($realdata as &$r) {
					if ($r['id'] == $dr['idpos']) {
						$found = true;
						if ($dr['method'] == 'V')
							$r['voucher'] += $dr['amount'];
						else if ($dr['method'] == 'C') {
							if ($dr['idrate'] == 'NA')
								$r['cash'] += $dr['amount'];
							else
								$r['cash'] += $dr['amount'] * lookup::CurrRateFromID($dr['idrate']);
						} else if ($dr['method'] == 'KK')
							$r['creditcard'] += $dr['amount'];
						else if ($dr['method'] == 'KD')
							$r['debitcard'] += $dr['amount'];
						else if ($dr['method'] == 'R')
							$r['retur'] += $dr['amount'];
						break;
					}						
				}	
				unset($r);
				if (!$found) {
					$temp['id'] = $dr['idpos'];
					$temp['idcashier'] = $dr['idcashier'];
					$temp['idsticker'] = $dr['idsticker'];
					$temp['idatetime'] = $dr['idatetime'];
					$temp['regnum'] = $dr['regnum'];
					$temp['totalsales'] = $dr['total'];
					$temp['cashreturn'] = $dr['cashreturn'];
					$temp['voucher'] = 0;
					$temp['cash'] = 0;
					$temp['creditcard'] = 0;
					$temp['debitcard'] = 0;
					$temp['retur'] = 0;
					if ($dr['method'] == 'V')
						$temp['voucher'] += $dr['amount'];
					else if ($dr['method'] == 'C') {
						if ($dr['idrate'] == 'NA')
							$temp['cash'] += $dr['amount'];
						else
							$temp['cash'] += $dr['amount'] * lookup::CurrRateFromID($dr['idrate']);
					} else if ($dr['method'] == 'KK')
						$temp['creditcard'] += $dr['amount'];
					else if ($dr['method'] == 'KD')
						$temp['debitcard'] += $dr['amount'];
					else if ($dr['method'] == 'R')
						$temp['retur'] += $dr['amount'];
					$realdata[] = $temp;						
				}
			}
			$this->render('viewsales2', array('data'=>$realdata, 'idcashier'=>$idcashier,
					'startdate'=>$startdate, 'enddate'=>$enddate
			));
		
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
		
	}
	
	public function actionGetsales3($startdate, $enddate )
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
			Yii::app()->user->id))  {
			$this->trackActivity('v');
						
			if (!isset($idcashier) || ($idcashier == ''))
				$idcashier = '%';
	
			$sql1 =<<<EOS
	select b.id, left(c.code, 3) as scode, e.firstname, a.iddetail, c.code, a.qty, a.price, a.discount
	from detailsalespos a
	join salespos b 
	on b.id = a.id
	join (items c 
		join suppliers e on e.code = left(c.code, 3)
	) on c.id = a.iditem
	where 
	b.idatetime >= '$startdate' and b.idatetime <= '$enddate'
	order by scode
EOS;
			$datasales = Yii::app()->db->createCommand($sql1)->queryAll();
			
			$sql4 =<<<EOS
	select b.id, b.total, b.discount, sum((a.price-a.discount)*a.qty) as itemtotal
	from detailsalespos a
	join (salespos b
	join posreceipts d on d.idpos = b.id
	) on b.id = a.id
	where
	b.idatetime >= '$startdate' and b.idatetime <= '$enddate'
	group by b.id
EOS;
			$infosales = Yii::app()->db->createCommand($sql4)->queryAll();
			
			foreach($datasales as &$ds) {
				foreach($infosales as $is) {
					if ($is['id'] == $ds['id']) {
						$ds['discount']	+= ($is['discount'] / $is['total'] * $is['itemtotal']);
						break;
					}
				}	
				$ds['itemcog'] = lookup::getbuyprice($ds['code']);
			}
			unset($ds);
			$summarysales = array();
			foreach($datasales as & $ds) {
				$scode = $ds['scode'];
				$found = FALSE;
				foreach($summarysales as &$ss) {
					if ($ss['scode'] == $scode) {
						$ss['qty'] += $ds['qty'];
						$ss['totalsold'] += $ds['qty'] * $ds['price'];
						$ss['totaldisc'] += $ds['qty'] * $ds['discount'];
						$ss['totalcog'] += $ds['qty'] * $ds['itemcog'];
						$ss['totalgain'] += ($ds['qty'] * ($ds['price'] - $ds['discount'] - $ds['itemcog']));
						$found = TRUE;
						break;	
					}
				}
				unset($ss);
				if (!$found) {
					$temp['iddetail'] = $ds['iddetail'];
					$temp['name'] = $ds['firstname'];
					$temp['scode'] = $scode;
					$temp['qty'] = $ds['qty'];
					$temp['totalsold'] = $ds['qty'] * $ds['price'];
					$temp['totaldisc'] = $ds['qty'] * $ds['discount'];
					$temp['totalcog'] = $ds['qty'] * $ds['itemcog'];
					$temp['totalgain'] = $temp['totalsold'] - $temp['totalcog'];
					$temp['suppliername'] = $ds['firstname'];
					$summarysales[] = $temp;
				}
			}
			unset($ds);
			
			
			Yii::app()->session['datasales3'] = $summarysales;
			$this->render('viewsales3', array('data'=>$summarysales, 'startdate'=>$startdate, 'enddate'=>$enddate));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	
	public function actionGetsales4($startdate, $enddate, $suppliercode )
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
					$this->trackActivity('v');
	
					$sql1 =<<<EOS
	select b.id, left(c.code, 3) as scode, e.firstname, a.iddetail, a.iditem, c.code, a.qty, a.price, a.discount
	from detailsalespos a
	join salespos b
	on b.id = a.id
	join (items c
		join suppliers e on e.code = left(c.code, 3)
	) on c.id = a.iditem
	where
	c.code like '$suppliercode%' and b.idatetime >= '$startdate' and b.idatetime <= '$enddate'
	order by scode
EOS;
					$datasales = Yii::app()->db->createCommand($sql1)->queryAll();
						
					$sql4 =<<<EOS
	select b.id, b.total, b.discount, sum((a.price-a.discount)*a.qty) as itemtotal
	from detailsalespos a
	join (salespos b
	join posreceipts d on d.idpos = b.id
	) on b.id = a.id
	where
	b.idatetime >= '$startdate' and b.idatetime <= '$enddate'
	group by b.id
EOS;
					$infosales = Yii::app()->db->createCommand($sql4)->queryAll();
						
					foreach($datasales as &$ds) {
						foreach($infosales as $is) {
							if ($is['id'] == $ds['id']) {
								$ds['discount']	+= ($is['discount'] / $is['total'] * $is['itemtotal']);
								break;
							}
						}
						$ds['itemcog'] = lookup::getbuyprice($ds['code']);
					}
					unset($ds);
					
					$summarysales = array();
					foreach($datasales as & $ds) {
						$batchcode = $ds['code'];
						$found = FALSE;
						foreach($summarysales as &$ss) {
							if ($ss['batchcode'] == $batchcode) {
								$ss['qty'] += $ds['qty'];
								$ss['totalsold'] += $ds['qty'] * $ds['price'];
								$ss['totaldisc'] += $ds['qty'] * $ds['discount'];
								$ss['totalcog'] += $ds['qty'] * $ds['itemcog'];
								$ss['totalgain'] += ($ds['qty'] * ($ds['price'] - $ds['discount'] - $ds['itemcog']));
								$found = TRUE;
								break;
							}
						}
						unset($ss);
						if (!$found) {
							$temp['iddetail'] = $ds['iddetail'];
							$temp['iditem'] = $ds['iditem'];
							$temp['batchcode'] = $batchcode;
							$temp['qty'] = $ds['qty'];
							$temp['totalsold'] = $ds['qty'] * $ds['price'];
							$temp['totaldisc'] = $ds['qty'] * $ds['discount'];
							$temp['totalcog'] = $ds['qty'] * $ds['itemcog'];
							$temp['totalgain'] = $temp['totalsold'] - $temp['totalcog'];
							$temp['suppliername'] = $ds['firstname'];
							$summarysales[] = $temp;
						}
					}
					unset($ds);
						
					Yii::app()->session['datasales4'] = $summarysales;
					$this->render('viewsales4', array('data'=>$summarysales, 'startdate'=>$startdate, 'enddate'=>$enddate));
				} else {
					throw new CHttpException(404,'You have no authorization for this operation.');
				};
	}
	
	public function actionGetexcel3()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$xl = new PHPExcel();
			$xl->getProperties()->setCreator("Program KOSAYU")
				->setLastModifiedBy("Program KOSAYU")
				->setTitle("Laporan Penjualan Global Berdasar Pemasok")
				->setSubject("Laporan Penjualan")
				->setDescription("Laporan Penjualan Global Berdasar Pemasok")
				->setKeywords("Laporan Penjualan Global")
				->setCategory("Laporan");
			$data = Yii::app()->session['datasales3'];
			$headersfield = array(
				'scode', 'name', 'qty', 'totalsold', 'totaldisc', 'totalcog', 'totalgain'
			);
			$headersname = array(
				'Kode', 'Nama Pemasok', 'Qty', 'Bruto', 'Potongan', 'Harga Beli', 'Margin'				
			);
			for( $i=0;$i<count($headersname); $i++ ) {
				$xl->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($i,1, $headersname[$i]);
			}			
						
			for( $i=0; $i<count($data); $i++){
				for( $j=0; $j<count($headersfield); $j++ ) {
					$cellvalue = $data[$i][$headersfield[$j]];
					$xl->setActiveSheetindex(0)
						->setCellValueByColumnAndRow($j,$i+2, $cellvalue);
				}
			}
						
			$xl->getActiveSheet()->setTitle('Laporan Penjualan');
			$xl->setActiveSheetIndex(0);
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment;filename="sales-report-'.idmaker::getDateTime().'.xls"');
			header('Cache-Control: max-age=0');
			$xlWriter = PHPExcel_IOFactory::createWriter($xl, 'Excel5');
			$xlWriter->save('php://output');
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetexcel4()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
					$this->trackActivity('v');
					$xl = new PHPExcel();
					$xl->getProperties()->setCreator("Program KOSAYU")
					->setLastModifiedBy("Program KOSAYU")
					->setTitle("Laporan Penjualan Tiap Pemasok")
					->setSubject("Laporan Penjuala Tiap Pemasokn")
					->setDescription("Laporan Penjualan Tiap Pemasok")
					->setKeywords("Laporan Penjualan Tiap Pemasok")
					->setCategory("Laporan");
					$data = Yii::app()->session['datasales4'];
					$headersfield = array(
							'batchcode', 'name', 'qty', 'totalsold', 'totaldisc', 'totalcog', 'totalgain'
					);
					$headersname = array(
							'Kode Batch', 'Nama Barang', 'Qty', 'Bruto', 'Potongan', 'Harga Beli', 'Margin'
					);
					for( $i=0;$i<count($headersname); $i++ ) {
						$xl->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($i,1, $headersname[$i]);
					}
	
					for( $i=0; $i<count($data); $i++){
						for( $j=0; $j<count($headersfield); $j++ ) {
							$cellvalue = $data[$i][$headersfield[$j]];
							$xl->setActiveSheetindex(0)
							->setCellValueByColumnAndRow($j,$i+2, $cellvalue);
						}
					}
	
					$xl->getActiveSheet()->setTitle('Laporan Penjualan 2');
					$xl->setActiveSheetIndex(0);
					header('Content-Type: application/pdf');
					header('Content-Disposition: attachment;filename="sales-report2-'.idmaker::getDateTime().'.xls"');
					header('Cache-Control: max-age=0');
					$xlWriter = PHPExcel_IOFactory::createWriter($xl, 'Excel5');
					$xlWriter->save('php://output');
				} else {
					throw new CHttpException(404,'You have no authorization for this operation.');
				};
	}
	
	public function actionGetexcel($startdate, $enddate, $brand, $objects)
	{
		$datacancels = array();
		$datareplaces = array();
		$data = array();
		
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			$xl = new PHPExcel();
			$xl->getProperties()->setCreator("Program GSI Malang")
				->setLastModifiedBy("Program GSI Malang")
				->setTitle("Laporan Penjualan")
				->setSubject("Laporan Penjualan")
				->setDescription("Laporan Penjualan Bulanan")
				->setKeywords("Laporan Penjualan")
				->setCategory("Laporan");	
			$enddate=$enddate.' 23:59:59';
			$selectfields = <<<EOS
			a.idatetime, a.regnum, a.total, a.discount, a.cash, a.cashreturn,  
			a.payer_name, a.payer_address, a.payer_phone, a.userlog, a.receiveable, 
			'Penjualan' as kind, '-' as invnum,
			case when (a.status) = '0' then 'Batal'
				when (a.status) = '1' then 'Berlaku'
				when (a.status) = '2' then 'Ganti Barang'
			end as cstatus,
			c.name, c.address, c.phone, 
			b.idsales, b.iditem, b.qty, b.price, b.discount
EOS;
			$selectfields2 = <<<EOS
			a.idatetime, a.regnum, a.total, a.discount, a.cash, a.cashreturn, a.status,
			a.payer_name, a.payer_address, a.payer_phone, a.userlog, a.receiveable,  
			c.name, c.address, c.phone,
			b.idsales, b.iditem, b.qty, b.price, b.discount
EOS;
			$selectfields3 = <<<EOS
			a.idatetime, a.regnum, a.total, a.discount, a.cash, a.cashreturn,
			a.payer_name, a.payer_address, a.payer_phone, a.userlog, a.receiveable,
			c.name, c.address, c.phone,
			b.idsales, b.iditem, b.qty, b.price, b.discount
EOS;
			$selectfields4 = <<<EOS
			a.idatetime, a.regnum, a.diff as total, (0) as discount, a.cash, a.cashreturn,
			a.payer_name, a.payer_address, a.payer_phone, a.userlog, a.receiveable,
			c.name, c.address, c.phone,
			b.idsales, b.iditem, b.qty, b.price, b.discount
EOS;
			$selectwhere = <<<EOS
			a.idatetime >= :p_startidatetime and a.idatetime <= :p_endidatetime 
EOS;
			
			unset($selectparam);
			$selectparam['p_startidatetime'] = $startdate;
			$selectparam['p_endidatetime'] = $enddate;

			if (isset($brand) && ($brand <> '')) {
				$selectwhere .= ' and d.brand = :p_brand';
				$selectparam[':p_brand'] = $brand;
			}
			if (isset($objects) && ($objects <> '')) {
				$selectwhere .= ' and d.objects = :p_objects';
				$selectparam[':p_objects'] = $objects;
			}
			
			// Get ALL Sales data
			$data=Yii::app()->db->createCommand()
				->select($selectfields)				
				->from('detailsalespos b')
				->join('salespos a', 'a.id = b.id')
				->join('items d', 'd.id = b.iditem')
				->leftJoin('salesreceivers c', 'c.id = a.idreceiver')
				->where($selectwhere, $selectparam)
				->order('a.idatetime, a.regnum, b.iddetail')
				->queryAll();
			
			$serialnumpb = Yii::app()->db->createCommand()
				->select('c.serialnum')->from('orderretrievals a')
				->join('stockexits b', 'b.transid = a.regnum')
				->join('detailstockexits c', 'c.id = b.id')
				->where("a.invnum = :p_invnum and c.serialnum <> 'Belum Diterima' and c.iditem = :p_iditem");
			$serialnumsj = Yii::app()->db->createCommand()
				->select('c.serialnum')->from('deliveryorders a')
				->join('stockexits b', 'b.transid = a.regnum')
				->join('detailstockexits c', 'c.id = b.id')
				->where("a.invnum = :p_invnum and c.serialnum <> 'Belum Diterima' and c.iditem = :p_iditem");
			
			foreach($data as & $myrow) {
				$serialnumpb->bindParam(':p_invnum', $myrow['regnum']);
				$serialnumpb->bindParam(':p_iditem', $myrow['iditem']);
				$datapb = $serialnumpb->queryColumn();
				$serialnumsj->bindParam(':p_invnum', $myrow['regnum']);
				$serialnumsj->bindParam(':p_iditem', $myrow['iditem']);
				$datasj = $serialnumsj->queryColumn();
				if ($datapb !== FALSE)
					$myrow['serialnums'] = implode(', ', $datapb);
				if ($datasj !== FALSE)
					$myrow['serialnums'] = $myrow['serialnums'] . implode(', ', $datasj);
			}
			// end -- Get ALL Sales data
			
			// Get ALL Sales Cancelation data
			$datarawcancels = Yii::app()->db->createCommand()
				->select('a.regnum, a.userlog, a.datetimelog, a.totalcash, a.invnum, a.totalnoncash')->from('salescancel a')
				->join('salespos b', 'b.regnum = a.invnum')
				->join('detailsalespos c', 'c.id = b.id')
				->join('items d', 'd.id = c.iditem')
				->where($selectwhere, $selectparam)
				->queryAll();
			foreach($datarawcancels as $dc) {
				$cancelsales = Yii::app()->db->createCommand()
					->select($selectfields2)->from('salespos a')->join('detailsalespos b', 'b.id = a.id')
					->leftJoin('salesreceivers c', 'c.id = a.idreceiver')
					->where('a.regnum = :p_regnum', array(':p_regnum'=>$dc['invnum']))
					->queryAll();
				foreach($cancelsales as $cs) {
					$datacancel['idatetime'] = $cs['idatetime'];
					$datacancel['regnum'] = $dc['regnum'];
					$datacancel['invnum'] = $cs['regnum'];
					$datacancel['status'] = $cs['status'];
					$datacancel['total'] = - ($dc['totalcash'] + $dc['totalnoncash']);
					$datacancel['cash'] = $cs['cash'];
					$datacancel['cashreturn'] = $cs['cashreturn'];
					$datacancel['receiveable'] = $cs['receiveable'];
					$datacancel['payer_name'] = $cs['payer_name'];
					$datacancel['payer_address'] = $cs['payer_address'];
					$datacancel['payer_phone'] = $cs['payer_phone'];
					$datacancel['iditem'] = $cs['iditem'];
					$datacancel['price'] = $cs['price'];
					$datacancel['qty'] = $cs['qty'];
					$datacancel['discount'] = $cs['discount'];
					$datacancel['name'] = $cs['name'];
					$datacancel['address'] = $cs['address'];
					$datacancel['phone'] = $cs['phone'];
					$datacancel['userlog'] = $dc['userlog'];
					$datacancel['datetimelog'] = $dc['datetimelog'];
					$datacancel['idsales'] = $cs['idsales'];
					$datacancel['cstatus'] = 'Berlaku';
					$datacancel['kind'] = 'Pembatalan';
					$datacancels[] = $datacancel;
				}
			}
			$serialnumkn1 = Yii::app()->db->createCommand()
				->select('c.serialnum')->from('salescancel a')
				->join('stockentries b', 'b.transid = a.regnum')
				->join('detailstockentries c', 'c.id = b.id')
				->where("a.invnum = :p_invnum and c.serialnum <> 'Belum Diterima' and c.iditem = :p_iditem");
			foreach($datacancels as & $myrow) {
				$serialnumkn1->bindParam(':p_invnum', $myrow['regnum']);
				$serialnumkn1->bindParam(':p_iditem', $myrow['iditem']);
				$datakn = $serialnumkn1->queryColumn();
				if ($datakn !== FALSE)
					$myrow['serialnums'] = implode(', ', $datakn);
			}
			// end -- Get ALL Sales Cancelation
			
			// Get ALL Sales Modification data
			$datarawreplaces = Yii::app()->db->createCommand()
				->select('a.regnum, a.invnum, a.totaldiff, a.userlog, a.datetimelog, b.*')
				->from('salesreplace a')
				->join('detailsalesreplace b', 'b.id = a.id')
				->join('items d', 'd.id = b.iditem')
				->where($selectwhere, $selectparam)
				->queryAll();
			foreach($datarawreplaces as $dr) {
				if ($dr['deleted'] == '0') {
					$replacesales = Yii::app()->db->createCommand()
						->select($selectfields3)->from('salespos a')->join('detailsalespos b', 'b.id = a.id')
						->leftJoin('salesreceivers c', 'c.id = a.idreceiver')
						->where('a.regnum = :p_regnum and b.iditem = :p_iditem and b.price = :p_price and b.qty = :p_qty', 
							array(':p_regnum'=>$dr['invnum'], ':p_iditem' => $dr['iditem'], ':p_price'=>$dr['price'],
								'p_qty'=>$dr['qty']))
						->queryRow();
				} else if ($dr['deleted'] == '1') {
					$replacesales = Yii::app()->db->createCommand()
					->select($selectfields4)->from('salesreplace2 a')->join('detailsalesreplace2 b', 'b.id = a.id')
					->leftJoin('salesreceivers c', 'c.id = a.idreceiver')
					->where('a.invnum = :p_invnum and b.iditem = :p_iditem and b.price = :p_price and b.qty = :p_qty',
							array(':p_invnum'=>$dr['invnum'], ':p_iditem' => $dr['iditemnew'], ':p_price'=>$dr['pricenew'],
									'p_qty'=>$dr['qtynew']))
									->queryRow();
				}
				if ($dr['deleted'] == '0' || $dr['deleted'] == '1') {
					$datareplace['idatetime'] = $replacesales['idatetime'];
					$datareplace['regnum'] = $dr['regnum'];
					$datareplace['invnum'] = $dr['invnum'];
					$datareplace['status'] = $dr['deleted'];
					$datareplace['total'] = - $dr['totaldiff'];
					$datareplace['cash'] = $replacesales['cash'];
					$datareplace['cashreturn'] = $replacesales['cashreturn'];
					$datareplace['receiveable'] = $replacesales['receiveable'];
					$datareplace['payer_name'] = $replacesales['payer_name'];
					$datareplace['payer_address'] = $replacesales['payer_address'];
					$datareplace['payer_phone'] = $replacesales['payer_phone'];
					$datareplace['iditem'] = $replacesales['iditem'];
					$datareplace['price'] = $replacesales['price'];
					$datareplace['qty'] = $replacesales['qty'];
					$datareplace['discount'] = $replacesales['discount'];
					$datareplace['name'] = $replacesales['name'];
					$datareplace['address'] = $replacesales['address'];
					$datareplace['phone'] = $replacesales['phone'];
					$datareplace['userlog'] = $dr['userlog'];
					$datareplace['datetimelog'] = $dr['datetimelog'];
					$datareplace['idsales'] = $replacesales['idsales'];
					$datareplace['kind'] = 'Ganti Barang';
					/*switch ($dr['status']) {
						case 0:
							$datareplace['cstatus'] = 'Batal';
							break;
						case 1:
							$datareplace['cstatus'] = 'Berlaku';
							break;
						case 0:
							$datareplace['cstatus'] = 'Diganti';
							break;
					}*/
					$datareplace['cstatus'] = 'Berlaku';
					$datareplaces[] = $datareplace;
				}
			}
			/*
			$serialnumkn2a = Yii::app()->db->createCommand()
				->select('c.serialnum')->from('salesreplaces a')
				->join('stockentries b', 'b.transid = a.regnum')
				->join('detailstockentries c', 'c.id = b.id')
				->where("a.invnum = :p_invnum and c.serialnum <> 'Belum Diterima' and c.iditem = :p_iditem");
			*/
			foreach($datareplaces as & $myrow) {
				/*
				$serialnumkn1->bindParam(':p_invnum', $myrow['regnum']);
				$serialnumkn1->bindParam(':p_iditem', $myrow['iditem']);
				$datakn = $serialnumkn1->queryColumn();
				if ($datakn !== FALSE)
					$myrow['serialnums'] = implode(', ', $datakn);
				*/
				$myrow['serialnums'] = '-';
			}
			// end -- Get ALL Sales Modification
			$data = array_merge($data, $datacancels, $datareplaces);
			$headersfield = array( 
				'kind', 'cstatus', 'idatetime', 'regnum', 'invnum', 'total', 'discount', 
				'cash', 'cashreturn', 'receiveable', 'payer_name', 'payer_address', 'payer_phone', 'userlog',
				'name', 'address', 'phone','idsales', 'iditem', 'qty', 'price', 'discount', 
				'serialnums');
			$headersname = array(
				'Jenis', 'Status', 'Tanggal', 'No Urut', 'No Faktur', 'Total', 'Potongan', 
				'Terima Tunai', 'Kembalian', 'Piutang', 'Nama Pembeli', 'Alamat Pembeli', 'Telp Pembeli', 'Nama Kasir', 
				'Nama Penerima', 'Alamat Penerima', 'Telp Penerima', 'Nama Sales', 'Nama Barang', 'Qty', 'Harga', 'Potongan', 
				'Nomor Seri');
			for( $i=0;$i<count($headersname); $i++ ) {
				$xl->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($i,1, $headersname[$i]);
			}			
			
			for( $i=0; $i<count($data); $i++){
				for( $j=0; $j<count($headersfield); $j++ ) {
					$cellvalue = $data[$i][$headersfield[$j]];
					if ($headersfield[$j] == 'idsales')
						$cellvalue = lookup::SalesPersonNameFromID($data[$i]['idsales']);
					else if ($headersfield[$j] == 'iditem')
						$cellvalue = lookup::ItemNameFromItemID($data[$i]['iditem']);
					else if ($headersfield[$j] == 'userlog')
						$cellvalue = lookup::UserNameFromUserID($data[$i]['userlog']);
					$xl->setActiveSheetindex(0)
						->setCellValueByColumnAndRow($j,$i+2, $cellvalue);
				}
			}
			
			$xl->getActiveSheet()->setTitle('Laporan Penjualan');
			$xl->setActiveSheetIndex(0);
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment;filename="sales-report-'.idmaker::getDateTime().'.xlsx"');
			header('Cache-Control: max-age=0');
			$xlWriter = PHPExcel_IOFactory::createWriter($xl, 'Excel2007');
			$xlWriter->save('php://output');
		} else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         };
	}
	
	protected function trackActivity($action)
	{
		$this->tracker=new Tracker();
        $this->tracker->init();
		$this->tracker->logActivity($this->formid, $action);
	}
      
	private function getbuyprice($code)
	{
		$price =  Yii::app()->db->createCommand()
			->select('id, buyprice, baseprice')->from('itembatch')
			->where('batchcode = :p_batchcode', array(':p_batchcode'=>$code))
			->order('id desc')
			->queryRow();
		if (!$price['buyprice'])
			return $price['baseprice'];
		else
			return $price['buyprice'];
	}	

}
