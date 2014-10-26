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
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	
	/*public function actionView($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
            $this->trackActivity('v');
            $this->render('view',array(
				'model'=>$this->loadModel($id),
			));
		};
	}*/

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	/*
	public function actionCreate()
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
              Yii::app()->user->id))  {   
            $this->state='c';
            $this->trackActivity('c');    

            $model=new Salesposcards;
            $this->afterInsert($model);

            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if(isset($_POST['Salesposcards'])) {
               $model->attributes=$_POST['Salesposcards'];
               $this->beforePost($model);
               if($model->save()) {
                  $this->afterPost($model);
                  $this->redirect(array('view','id'=>$model->id));                 
               }    
            }

            $this->render('create',array('model'=>$model) );
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }
	}*/

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	
	/*public function actionUpdate($id)
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
            Yii::app()->user->id))  {

            $this->state='u';
            $this->trackActivity('u');

            $model=$this->loadModel($id);
            $this->afterEdit($model);

            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if(isset($_POST['Salesposcards'])) {
               $model->attributes=$_POST['Salesposcards'];

               $this->beforePost($model);   
               $this->tracker->modify('salesposcards', $id);
               if($model->save()) {
                  $this->afterPost($model);
                  $this->redirect(array('view','id'=>$model->id));
               }        
            }

            $this->render('update',array( 'model'=>$model ));
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }
	}*/

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	
	/*
	public function actionDelete($id)
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-Delete', 
            Yii::app()->user->id))  {

            $this->trackActivity('d');
            $model=$this->loadModel($id);
            $this->beforeDelete($model);
            
            $this->tracker->delete('salesposcards', $id);

            $model->delete();
            $this->afterDelete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
               $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
	*/
	/**
	 * Lists all models.
	 */
	
	/*
	public function actionIndex()
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
            Yii::app()->user->id)) {
            $this->trackActivity('l');

            $dataProvider=new CActiveDataProvider('Salesposcards',
               array(
                  'criteria'=>array(
                     'order'=>'id desc'
                  )
               )
            );
            $this->render('index',array(
                'dataProvider'=>$dataProvider,
            ));
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }
	}
	*/
	/**
	 * Manages all models.
	 */
	
	/*
	 public function actionAdmin()
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
            Yii::app()->user->id)) {
            $this->trackActivity('s');

            $model=new Salesposcards('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Salesposcards']))
               $model->attributes=$_GET['Salesposcards'];

            $this->render('admin',array('model'=>$model));
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }
	}

      public function actionHistory($id)
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
            Yii::app()->user->id)) {
             $model=$this->loadModel($id);
             $this->render('history', array(
                'model'=>$model
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }   
      }
        
      public function actionDeleted()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
            Yii::app()->user->id)) {
             $this->render('deleted', array());
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }   
      }
        
      public function actionRestore($idtrack)
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
            Yii::app()->user->id)) {
            $this->trackActivity('r');
            $this->tracker->restore('salesposcards', $idtrack);

            $dataProvider=new CActiveDataProvider('Salesposcards');
            $this->render('index',array('dataProvider'=>$dataProvider));
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
        
      public function actionRestoreDeleted($idtrack)
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
            Yii::app()->user->id)) {
            $this->trackActivity('n');
            $this->tracker->restoreDeleted('salesposcards', $idtrack);

            $dataProvider=new CActiveDataProvider('Salesposcards');
            $this->render('index',array('dataProvider'=>$dataProvider));
         } else {
            throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
     */   
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Salesposcards the loaded model
	 * @throws CHttpException
	 */
	/*
	public function loadModel($id)
	{
         $model=Salesposcards::model()->findByPk($id);
         if($model===null)
               throw new CHttpException(404,'The requested page does not exist.');
         return $model;
	}*/

	/**
	 * Performs the AJAX validation.
	 * @param Salesposcards $model the model to be validated
	 */
	
	/*
	protected function performAjaxValidation($model)
	{
         if(isset($_POST['ajax']) && $_POST['ajax']==='salesposcards-form')
         {
               echo CActiveForm::validate($model);
               Yii::app()->end();
         }
	}
	  
      protected function afterInsert(& $model)
      {
         $idmaker=new idmaker();
         $model->id=$idmaker->getcurrentID2();  
      }
        
      protected function afterPost(& $model)
      {

      }
        
      protected function beforePost(& $model)
      {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
      }
        
      protected function beforeDelete(& $model)
      {

      }
        
      protected function afterDelete(& $model)
      {

      }
        
      protected function afterEdit(& $model)
      {

      }
      */  
      protected function trackActivity($action)
      {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
      }

}
