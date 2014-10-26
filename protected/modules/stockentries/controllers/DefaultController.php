<?php

function cmp($a, $b)
{
	return strcmp($a['iditem'], $b['iditem']);
}

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC3';
	public $tracker;
	public $state;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('view',array(
				'model'=>$this->loadModel($id),
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };	
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
             if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {   
                $this->state='create';
                $this->trackActivity('c');    
                    
                $model=new Stockentries;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Stockentries'])) {
                   Yii::app()->session['Stockentries']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Stockentries'];
                }
                if (isset($_POST['Stockentries'])) {
                	$model->attributes=$_POST['Stockentries'];
                }
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);
				
				if (isset($_POST)){
					
					if(isset($_POST['yt0'])) {
						$model->attributes=$_POST['Stockentries'];
                      //The user pressed the button;
						$this->beforePost($model);
						$respond=$this->checkWarehouse($model->idwarehouse);
						if (!$respond)
	                      	throw new CHttpException(5000,'Lokasi anda tidak terdaftar');
						$respond = $this->checkSerialNum(Yii::app()->session['Detailstockentries'], $model->idwarehouse);
	                    if (!$respond)
	                      	throw new CHttpException(5001,'Nomor Seri yg anda daftarkan ada yg sdh terdaftar: '. $respond);
	                      
						$respond=$model->save();
						if(!$respond) {
							if (count($model->error) > 0 )
								$error = implode(',', $model->error);
							else
								$error = $model->error;
							throw new CHttpException(5002,'There is an error in master posting: '.$error);
	                    }
	
						if(isset(Yii::app()->session['Detailstockentries']) ) {
							$details=Yii::app()->session['Detailstockentries'];
							$respond=$respond&&$this->saveNewDetails($details, $model->idwarehouse	);
						} 
	
						$this->afterPost($model);
						Yii::app()->session->remove('Stockentries');
						Yii::app()->session->remove('Detailstockentries');
						Yii::app()->session->remove('Deletedetailstockentries');
						$this->redirect(array('view','id'=>$model->id));

					} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if($_POST['command']=='adddetail') {
							$model->attributes=$_POST['Stockentries'];
							Yii::app()->session['Stockentries']=$_POST['Stockentries'];
							$this->redirect(array('detailstockentries/create',
                            	'id'=>$model->id));
                      	} else if ($_POST['command']=='getPO') {
                        	$model->attributes=$_POST['Stockentries'];
                         	Yii::app()->session['Stockentries']=$_POST['Stockentries'];
                         	$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                      	} else if ($_POST['command']=='updateDetail') {
							$model->attributes=$_POST['Stockentries'];
                         	Yii::app()->session['Stockentries']=$_POST['Stockentries'];
                      	}
					}
				}

				$this->render('create',array(
                    'model'=>$model,
                ));
                
             } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
             }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {

			$this->state='update';
			$this->trackActivity('u');

			$model=$this->loadModel($id);
            $this->afterEdit($model);
             
			Yii::app()->session['master']='update';

			if(!isset(Yii::app()->session['Stockentries']))
                Yii::app()->session['Stockentries']=$model->attributes;
			else
                $model->attributes=Yii::app()->session['Stockentries'];

			if(!isset(Yii::app()->session['Detailstockentries'])) 
				Yii::app()->session['Detailstockentries']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST)) {
				if(isset($_POST['yt0'])) {
                      //The user pressed the button;
					$model->attributes=$_POST['Stockentries'];
                       
					$this->beforePost($model);
					$respond=$this->checkWarehouse($model->idwarehouse);
					if (!$respond)
						throw new CHttpException(5000,'Lokasi anda tidak terdaftar');
					$respond = $this->checkSerialNum(Yii::app()->session['Detailstockentries'], $model->idwarehouse);
					if (!$respond)
						throw new CHttpException(5001,'Nomor Seri yg anda daftarkan ada yg sdh terdaftar: '. $respond);
	                      
					$respond=$model->save();
					if(!$respond) {
						if (count($model->error) > 0 )
							$error = implode(',', $model->error);
						else
							$error = $model->error;
						throw new CHttpException(5002,'There is an error in master posting: '.$error);
	                }
	
					if(isset(Yii::app()->session['Detailstockentries']) ) {
						$details=Yii::app()->session['Detailstockentries'];
						$respond=$this->saveDetails($details, $model->idwarehouse);
						if (!$respond)
							throw new CHttpException(5002,'There is an error in detail posting');
					} 
	
					$this->afterPost($model);
					Yii::app()->session->remove('Stockentries');
					Yii::app()->session->remove('Detailstockentries');
					Yii::app()->session->remove('Deletedetailstockentries');
					
					$this->redirect(array('view','id'=>$model->id));

				} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if($_POST['command']=='adddetail') {
							$model->attributes=$_POST['Stockentries'];
							Yii::app()->session['Stockentries']=$_POST['Stockentries'];
							$this->redirect(array('detailstockentries/create',
                            	'id'=>$model->id));
                      	} else if ($_POST['command']=='getPO') {
                        	$model->attributes=$_POST['Stockentries'];
                         	Yii::app()->session['Stockentries']=$_POST['Stockentries'];
                         	$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                      	} else if ($_POST['command']=='updateDetail') {
							$model->attributes=$_POST['Stockentries'];
                         	Yii::app()->session['Stockentries']=$_POST['Stockentries'];
                      	}
					}
				}
				
             $this->render('update',array(
                     'model'=>$model,
             ));
         }  else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
         if(Yii::app()->authManager->checkAccess($this->formid.'-Delete', 
                 Yii::app()->user->id))  {

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('stockentries', $id);

            $detailmodels=Detailstockentries::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailstockentries', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }

            $model->delete();
            $this->afterDelete($model);

         // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
         if(!isset($_GET['ajax']))
               $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
     }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
               $this->trackActivity('l');

               Yii::app()->session->remove('Stockentries');
               Yii::app()->session->remove('Detailstockentries');
               Yii::app()->session->remove('Deletedetailstockentries');
               $dataProvider=new CActiveDataProvider('Stockentries',
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

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
                $this->trackActivity('s');
               
                $model=new Stockentries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stockentries']))
			$model->attributes=$_GET['Stockentries'];

		$this->render('admin',array(
			'model'=>$model,
		));
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
                   'model'=>$model,
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionDeleted()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->render('deleted', array(
         
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionRestore($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('r');
                $this->tracker->restore('stockentries', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Stockentries');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
        public function actionRestoreDeleted($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('n');
                $id = Yii::app()->tracker->createCommand()->select('id')->from('stockentries')
                ->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                ->queryScalar();
                $this->tracker->restoreDeleted('detailstockentries', "id", $id );
                $this->tracker->restoreDeleted('stockentries', "idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Stockentries');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Stockentries the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Stockentries::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Stockentries $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stockentries-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
      public function actionCreateDetail()
      {
      //this action continues the process from the detail page
         if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                 Yii::app()->user->id))  {
             $model=new Stockentries;
             $model->attributes=Yii::app()->session['Stockentries'];

             $details=Yii::app()->session['Detailstockentries'];
             $this->afterInsertDetail($model, $details);
			 
             
             $this->render('create',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         } 
      }
      
      public function actionUpdateDetail()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {

             $model=new Stockentries;
             $model->attributes=Yii::app()->session['Stockentries'];

             $details=Yii::app()->session['Detailstockentries'];
             $this->afterUpdateDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
      public function actionDeleteDetail()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {


             $model=new Stockentries;
             $model->attributes=Yii::app()->session['Stockentries'];

             $details=Yii::app()->session['Detailstockentries'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      

     protected function saveNewDetails(array $details, $idwh)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailstockentries;
             $detailmodel->attributes=$row;
             $respond=$detailmodel->insert();
             if (!$respond) {
                break;
             }
         }
         return $respond;
     }
     

     protected function saveDetails(array $details)
     {
         $idmaker=new idmaker();

         $respond=true;
         foreach ($details as $row) {
             $detailmodel=Detailstockentries::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailstockentries;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailstockentries', array('iddetail'=>$detailmodel->iddetail));
                 }    
             }
             $detailmodel->attributes=$row;
             $detailmodel->userlog=Yii::app()->user->id;
             $detailmodel->datetimelog=$idmaker->getDateTime();
             $respond=$detailmodel->save();
             if (!$respond) {
               break;
             }
          }
          return $respond;
     }
      
     protected function deleteDetails(array $details)
     {
         $respond=true;
         foreach ($details as $row) {
             $detailmodel=Detailstockentries::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailstockentries', $detailmodel->iddetail);
                 $respond=$detailmodel->delete();
                 if (!$respond) {
                   break;
                 }
             }
         }
         return $respond;
     }


     protected function loadDetails($id)
     {
      $sql="select * from detailstockentries where id='$id'";
      $details=Yii::app()->db->createCommand($sql)->queryAll();

      return $details;
     }


     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         //$model->idwarehouse=lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         if ($this->state == 'create') {
         	$idmaker->saveRegNum($this->formid, $model->regnum);
         } 
         $details = $this->loadDetails($model->id);
         foreach($details as $detail) {
         	if ($detail['serialnum'] !==  'Belum Diterima') {
         		$exist = Action::checkItemToWarehouse($model->idwarehouse, $detail['iditem'], 
	         		$detail['serialnum'], '%') > 0;
	         	if (!$exist)	
	         		Action::addItemToWarehouse($model->idwarehouse, $detail['iddetail'], 
	         			$detail['iditem'], $detail['serialnum'], $detail['status']);
	         	else {
	         		Action::setItemAvailinWarehouse($model->idwarehouse, $detail['serialnum'], '1');
	         		Action::setItemStatusinWarehouse($model->idwarehouse, $detail['serialnum'], $detail['status']);
	         	}
	         	if ($model->transname == 'AC33')
	         		Action::receiveRepairOut($model->transid, $detail['serialnum']);
	         }
         };
          
         $this->setStatusPO($model->transid,
            Yii::app()->session['Detailstockentries']);
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         if ($this->state == 'create')
         	$model->regnum=$idmaker->getRegNum($this->formid);
         else if ($this->state == 'update') {
         	$details = $this->loadDetails($model->id);
         	foreach($details as $detail) {
         		if ($detail['serialnum'] !==  'Belum Diterima') {
         			if ($model->transname == 'AC12')
         				Action::deleteItemFromWarehouse($model->idwarehouse, $detail['serialnum']);
         			else
         				Action::setItemAvailinWarehouse($model->idwarehouse, $detail['serialnum'], '0');
         		}
         	};
         }
     }

     protected function beforeDelete(& $model)
     {
     	$details = $this->loadDetails($model->id);
     	foreach($details as $detail) {
     		if ($detail['serialnum'] !==  'Belum Diterima') {
     			Action::deleteItemFromWarehouse($model->idwarehouse, $detail['serialnum']);
     		}
     	};
     }

     protected function afterDelete(& $model)
     {

     }

     protected function afterEdit(& $model)
     {

     }

     protected function afterInsertDetail(& $model, $details)
     {

     }


     protected function afterUpdateDetail(& $model, $details)
     {

     }

     protected function afterDeleteDetail(& $model, $details)
     {
     }


     protected function trackActivity($action)
     {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
     }
     
      private function loadPO($idpo, $id)
      {
        $details=array();

        $dataPO=Yii::app()->db->createCommand()
           ->select('a.id, b.*')
           ->from('purchasesorders a')
           ->join('detailpurchasesorders b', 'b.id=a.id')
           ->where('a.regnum = :p_id', array(':p_id'=>$idpo) )
           ->queryAll();
        Yii::app()->session->remove('Detailstockentries');
        $sql=<<<EOS
    	select count(*) as received from stockentries a 
		join detailstockentries b on b.id = a.id
		where a.transid = :p_transid and b.iditem = :p_iditem and
        b.serialnum <> 'Belum Diterima'   
EOS;
        $mycommand=Yii::app()->db->createCommand($sql);
         foreach($dataPO as $row) {
         	$mycommand->bindParam(':p_transid', $idpo, PDO::PARAM_STR);
         	$mycommand->bindParam(':p_iditem', $row['iditem'], PDO::PARAM_STR);
         	$accepted=$mycommand->queryScalar();
            for ($index = 0; $index < $row['qty'] - $accepted; $index++) {
               $detail['iddetail']=idmaker::getCurrentID2();
               $detail['id']=$id;
               $detail['iditem']=$row['iditem'];
               $detail['userlog']=Yii::app()->user->id;
               $detail['datetimelog']=idmaker::getDateTime();
               $detail['serialnum']='Belum Diterima';

               $details[]=$detail; 
           	}
        }
        Yii::app()->session['Detailstockentries']=$details;
      }
     
      private function loadLPB2($nolpb, $id, $idwh)
      {
      	$details=array();
      
      	$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.iditem, sum(b.qty) as qty')
      		->from('purchasesstockentries a')
      		->join('detailpurchasesstockentries b', 'b.id=a.id')
      		->where('a.regnum = :p_regnum', array(':p_regnum'=>$nolpb) )
      		->group('b.iditem')
      		->queryAll();
      	/*if ($dataLPB == FALSE) {
      		$dataLPB=Yii::app()->db->createCommand()
      			->select('a.id, b.*')
      			->from('requestdisplays a')
      			->join('detailrequestdisplays b', 'b.id=a.id')
      			->where('a.regnum = :p_regnum', array(':p_regnum'=>$nolpb) )
      			->queryAll();
      	}*/
      	if ($dataLPB == FALSE) {
      		$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.iditem, sum(b.qty) as qty')
      		->from('itemtransfers a')
      		->join('detailitemtransfers b', 'b.id=a.id')
      		->where('a.regnum = :p_regnum and a.idwhdest = :p_idwhdest', 
      				array(':p_regnum'=>$nolpb, ':p_idwhdest'=>$idwh) )
      		->group('b.iditem')
      		->queryAll();
      	}
      	
      	
      	
      	if ($dataLPB == FALSE ) {
      		$invnum = Yii::app()->db->createCommand()
      		->select('invnum')->from('salescancel')
      		->where('regnum = :p_regnum', array(':p_regnum'=>$nolpb))
      		->queryScalar();
      		
      		$dataSJ=Yii::app()->db->createCommand()
	      		->select('a.id, b.iditem, sum(b.qty) as qty')
	      		->from('deliveryorders a')
	      		->join('detaildeliveryorders b', 'b.id=a.id')
	      		->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse',
	      				array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh) )
				->group('b.iditem')
	      		->queryAll();
      		$dataPB=Yii::app()->db->createCommand()
	      		->select('a.id, b.iditem, sum(b.qty) as qty')
	      		->from('orderretrievals a')
	      		->join('detailorderretrievals b', 'b.id=a.id')
	      		->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse',
	      				array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh) )
				->group('b.iditem')
				->queryAll();
      		
      		$dataLPB = array_merge($dataPB, $dataSJ);
      	}
      	
      	if ($dataLPB == FALSE ) {
      		$dataPBs = array();
      		$dataSJs = array();
      		
      		$invnum = Yii::app()->db->createCommand()
	      		->select('invnum')->from('salesreplace')
	      		->where('regnum = :p_regnum', array(':p_regnum'=>$nolpb))
	      		->queryScalar();
      	
      		$detailreplaces1 = Yii::app()->db->createCommand()
      			->select('a.id, b.iditem, b.qty')->from('detailsalesreplace b')
      			->join('salesreplace a', 'a.id = b.id')
      			->where('a.regnum = :p_regnum and b.deleted = :p_same', 
      				array(':p_regnum'=>$nolpb, ':p_same'=>'2'))
      			->queryAll();
      		
      		foreach($detailreplaces1 as & $dr) {
      			$qtySJ=Yii::app()->db->createCommand()
	      			->select('sum(b.qty) as qty')
	      			->from('deliveryorders a')
	      			->join('detaildeliveryorders b', 'b.id=a.id')
	      			->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
      					array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
      				->group('b.iditem')
					->queryScalar();
      			 
      			$qtyPB=Yii::app()->db->createCommand()
	      			->select('sum(b.qty) as qty')
	      			->from('orderretrievals a')
	      			->join('detailorderretrievals b', 'b.id=a.id')
	      			->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
      					array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
      				->group('b.iditem')
      				->queryScalar();
      		
      			if (($qtySJ + $qtyPB) < $dr['qty'] )
      				$dr['qty'] = $qtyPB + $qtySJ;
      		}
      		
      		$detailreplaces2 = Yii::app()->db->createCommand()
	      		->select('a.id, b.iditem, b.qty, b.qtynew')->from('detailsalesreplace b')
	      		->join('salesreplace a', 'a.id = b.id')
	      		->where('a.regnum = :p_regnum and b.deleted = :p_same',
	      				array(':p_regnum'=>$nolpb, ':p_same'=>'1'))
				->queryAll();
      		foreach($detailreplaces2 as & $dr) {
      			$qtySJ=Yii::app()->db->createCommand()
	      			->select('sum(b.qty) as qty')
	      			->from('deliveryorders a')
	      			->join('detaildeliveryorders b', 'b.id=a.id')
	      			->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
	      				array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
	      			->group('b.iditem')
	      			->queryScalar();
	      			 
      			$qtyPB=Yii::app()->db->createCommand()
	      			->select('sum(b.qty) as qty')
	      			->from('orderretrievals a')
	      			->join('detailorderretrievals b', 'b.id=a.id')
	      			->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
	      				array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
					->group('b.iditem')
      				->queryScalar();
      			 
      			if ($dr['qty'] > $dr['qtynew']) 
      				$dr['qty'] = $dr['qty'] - $dr['qtynew'];
      			else if ($dr['qty'] < $dr['qtynew'])
      				$dr['qty'] = 0;
      			
      			if (($qtySJ + $qtyPB) < $dr['qty'] )
      				$dr['qty'] = $qtyPB + $qtySJ;
      		}
      		$dataLPB = array_merge($detailreplaces2, $detailreplaces1);
      	}
      	
      	if ($dataLPB == FALSE ) {
      		$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.iditem, (1) as qty')
      		->from('receiverepairs a')
      		->join('detailreceiverepairs b', 'b.id=a.id')
      		->where('a.regnum = :p_regnum and b.idwarehouse = :p_idwarehouse',
      				array(':p_regnum'=>$nolpb, ':p_idwarehouse'=>$idwh) )
      				->queryAll();
      	}
      	
      	Yii::app()->session->remove('Detailstockentries');
      	if ($dataLPB !== FALSE) {
	      	$sql=<<<EOS
	    	select count(*) as received from stockentries a
			join detailstockentries b on b.id = a.id
			where a.transid = :p_transid and b.iditem = :p_iditem and
	        b.serialnum <> 'Belum Diterima'
EOS;
	      	$mycommand=Yii::app()->db->createCommand($sql);
	      	foreach($dataLPB as $row) {
	 
	      		$mycommand->bindParam(':p_transid', $nolpb, PDO::PARAM_STR);
	      		$mycommand->bindParam(':p_iditem', $row['iditem'], PDO::PARAM_STR);
				$accepted=$mycommand->queryScalar();
				for ($index = 0; $index < $row['qty'] - $accepted; $index++) {
					$detail['iddetail']=idmaker::getCurrentID2();
	      			$detail['id']=$id;
					$detail['iditem']=$row['iditem'];
					$detail['userlog']=Yii::app()->user->id;
					$detail['datetimelog']=idmaker::getDateTime();
					$detail['serialnum']='Belum Diterima';
					$detail['status'] = '';
	      			$details[]=$detail;
				}
			}
			Yii::app()->session['Detailstockentries']=$details;
      	};
	}
      			
	private function loadLPB($nolpb, $id, $idwh)
	{
		$details=array();
	
		$prefix = substr($nolpb, 0, 2);
		
		/*if ($dataLPB == FALSE) {
		 $dataLPB=Yii::app()->db->createCommand()
		->select('a.id, b.*')
		->from('requestdisplays a')
		->join('detailrequestdisplays b', 'b.id=a.id')
		->where('a.regnum = :p_regnum', array(':p_regnum'=>$nolpb) )
		->queryAll();
		}*/
		if ($prefix == 'TB') {
			$dataLPB=Yii::app()->db->createCommand()
			->select('a.id, b.iditem, sum(b.qty) as qty')
			->from('itemtransfers a')
			->join('detailitemtransfers b', 'b.id=a.id')
			->where('a.regnum = :p_regnum and a.idwhdest = :p_idwhdest',
					array(':p_regnum'=>$nolpb, ':p_idwhdest'=>$idwh) )
					->group('b.iditem')
					->queryAll();
		} else if ($prefix == 'FB') {
			$invnum = Yii::app()->db->createCommand()
			->select('invnum')->from('salescancel')
			->where('regnum = :p_regnum', array(':p_regnum'=>$nolpb))
			->queryScalar();
	
			$dataSJ=Yii::app()->db->createCommand()
			->select('a.id, b.iditem, sum(b.qty) as qty')
			->from('deliveryorders a')
			->join('detaildeliveryorders b', 'b.id=a.id')
			->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse',
					array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh) )
					->group('b.iditem')
					->queryAll();
			$dataPB=Yii::app()->db->createCommand()
			->select('a.id, b.iditem, sum(b.qty) as qty')
			->from('orderretrievals a')
			->join('detailorderretrievals b', 'b.id=a.id')
			->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse',
					array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh) )
					->group('b.iditem')
					->queryAll();
	
			$dataLPB = array_merge($dataPB, $dataSJ);
		} else if ($prefix == 'FG' ) {
			$dataPBs = array();
			$dataSJs = array();
	
			$invnum = Yii::app()->db->createCommand()
			->select('invnum')->from('salesreplace')
			->where('regnum = :p_regnum', array(':p_regnum'=>$nolpb))
			->queryScalar();
			 
			$detailreplaces1 = Yii::app()->db->createCommand()
			->select('a.id, b.iditem, b.qty')->from('detailsalesreplace b')
			->join('salesreplace a', 'a.id = b.id')
			->where('a.regnum = :p_regnum and b.deleted = :p_same',
					array(':p_regnum'=>$nolpb, ':p_same'=>'2'))
					->queryAll();
	
			foreach($detailreplaces1 as & $dr) {
				$qtySJ=Yii::app()->db->createCommand()
				->select('sum(b.qty) as qty')
				->from('deliveryorders a')
				->join('detaildeliveryorders b', 'b.id=a.id')
				->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
						array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
						->group('b.iditem')
						->queryScalar();
	
				$qtyPB=Yii::app()->db->createCommand()
				->select('sum(b.qty) as qty')
				->from('orderretrievals a')
				->join('detailorderretrievals b', 'b.id=a.id')
				->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
						array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
						->group('b.iditem')
						->queryScalar();
	
				if (($qtySJ + $qtyPB) < $dr['qty'] )
					$dr['qty'] = $qtyPB + $qtySJ;
			}
	
			$detailreplaces2 = Yii::app()->db->createCommand()
			->select('a.id, b.iditem, b.qty, b.qtynew')->from('detailsalesreplace b')
			->join('salesreplace a', 'a.id = b.id')
			->where('a.regnum = :p_regnum and b.deleted = :p_same',
					array(':p_regnum'=>$nolpb, ':p_same'=>'1'))
					->queryAll();
			foreach($detailreplaces2 as & $dr) {
				$qtySJ=Yii::app()->db->createCommand()
				->select('sum(b.qty) as qty')
				->from('deliveryorders a')
				->join('detaildeliveryorders b', 'b.id=a.id')
				->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
						array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
						->group('b.iditem')
						->queryScalar();
				 
				$qtyPB=Yii::app()->db->createCommand()
				->select('sum(b.qty) as qty')
				->from('orderretrievals a')
				->join('detailorderretrievals b', 'b.id=a.id')
				->where('a.invnum = :p_invnum and b.idwarehouse = :p_idwarehouse and b.iditem = :p_iditem',
						array(':p_invnum'=>$invnum, ':p_idwarehouse'=>$idwh, ':p_iditem'=>$dr['iditem']) )
						->group('b.iditem')
						->queryScalar();
	
				if ($dr['qty'] > $dr['qtynew'])
					$dr['qty'] = $dr['qty'] - $dr['qtynew'];
				else if ($dr['qty'] < $dr['qtynew'])
					$dr['qty'] = 0;
				 
				if (($qtySJ + $qtyPB) < $dr['qty'] )
					$dr['qty'] = $qtyPB + $qtySJ;
			}
			$dataLPB = array_merge($detailreplaces2, $detailreplaces1);
		} else if ($prefix == 'KR' ) {
			$dataLPB=Yii::app()->db->createCommand()
			->select('a.id, b.iditem, (1) as qty')
			->from('receiverepairs a')
			->join('detailreceiverepairs b', 'b.id=a.id')
			->where('a.regnum = :p_regnum and b.idwarehouse = :p_idwarehouse',
					array(':p_regnum'=>$nolpb, ':p_idwarehouse'=>$idwh) )
					->queryAll();
		} else {
			$dataLPB=Yii::app()->db->createCommand()
			->select('a.id, b.iditem, sum(b.qty) as qty')
			->from('purchasesstockentries a')
			->join('detailpurchasesstockentries b', 'b.id=a.id')
			->where('a.regnum = :p_regnum', array(':p_regnum'=>$nolpb) )
			->group('b.iditem')
			->queryAll();
		}
		 
		Yii::app()->session->remove('Detailstockentries');
		if ($dataLPB !== FALSE) {
			$sql=<<<EOS
	    	select count(*) as received from stockentries a
			join detailstockentries b on b.id = a.id
			where a.transid = :p_transid and b.iditem = :p_iditem and
	        b.serialnum <> 'Belum Diterima'
EOS;
			$mycommand=Yii::app()->db->createCommand($sql);
			foreach($dataLPB as $row) {
	
				$mycommand->bindParam(':p_transid', $nolpb, PDO::PARAM_STR);
				$mycommand->bindParam(':p_iditem', $row['iditem'], PDO::PARAM_STR);
				$accepted=$mycommand->queryScalar();
				for ($index = 0; $index < $row['qty'] - $accepted; $index++) {
					$detail['iddetail']=idmaker::getCurrentID2();
					$detail['id']=$id;
					$detail['iditem']=$row['iditem'];
					$detail['userlog']=Yii::app()->user->id;
					$detail['datetimelog']=idmaker::getDateTime();
					$detail['serialnum']='Belum Diterima';
					$detail['status'] = '';
					$details[]=$detail;
				}
			}
			Yii::app()->session['Detailstockentries']=$details;
		};
	}
      private function checkSerialNum(array $details, $idwh ) 
      {
         $respond=true;
         
         foreach($details as $detail) {
            if ($detail['serialnum'] !== 'Belum Diterima') {
               $count=Yii::app()->db->createCommand()
                  ->select('count(*)')
                  ->from("wh$idwh")
                  ->where("serialnum = :serialnum and avail = '1'", array(':serialnum'=>$detail['serialnum']))
                  ->queryScalar();
               $respond = $count==0;
               if($respond === false) {
                  	$respond = $detail['serialnum'];
               		break;
               }
            };
         }   
         return $respond;
      }
      
      private function setStatusPO($idpo, array $details)
      {
         $complete=true;
         $partial=false;
         foreach($details as $detail) {
            if($detail['serialnum'] !== 'Belum Diterima')
               $partial=true;
            if($detail['serialnum']=='Belum Diterima') 
               $complete=false;
         }
         if(!$complete && !$partial)
            $status='0';
         if(!$complete && $partial)
            $status='1';
         if($complete && $partial)
            $status='2';
         Action::setStatusPO ($idpo, $status);
      }
      
      private function checkWarehouse($idwarehouse)
      {
         $respond=$idwarehouse<>'NA';
         if (!$respond)
           throw new CHttpException(404,'Gudang belum terdaftar.'); 
         else
            return $respond; 
      }
      
      public function actionSummary($id)
      {
      	$this->trackActivity('v');
      	$this->render('summary',array(
      			'model'=>$this->loadModel($id),
      	));
      
      }
      
      public function actionPrintsummary($id)
      {
      	$this->trackActivity('v');
      	Yii::import("application.vendors.tcpdf.*");
      	require_once('tcpdf.php');
      	$this->render('printsummary',array(
      			'model'=>$this->loadModel($id),
      	));
      }
      
      public function actionSerial()
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      
      		$alldata = array();
      		$whcodeparam = '';
      		$itemnameparam = '';
      			
      		if (isset($_GET['go'])) {
      			$whcodeparam = $_GET['whcode'];
      			$itemnameparam = $_GET['itemname'];
      			$whs = Yii::app()->db->createCommand()
      			->select("id, code")->from('warehouses')->where('code like :p_code',
      					array(':p_code'=>'%'.$whcodeparam.'%'))
      					->queryAll();
      			foreach($whs as $wh) {
      				$data = Yii::app()->db->createCommand()
      				->select("c.iddetail, a.transid, c.iditem, b.name, c.serialnum, concat('${wh['code']}') as code")
      				->from("stockentries a")
      				->join("detailstockentries c", "c.id = a.id")
      				->join('items b', 'b.id = c.iditem')
      				->where("b.name like :p_name and a.idwarehouse = '${wh['id']}' and c.serialnum <> 'Belum Diterima'", array(':p_name'=>"%$itemnameparam%"))
      				->order('b.name')
      				->queryAll();
      				$alldata = array_merge($alldata, $data);
		      	}
		      	usort($alldata, 'cmp');
		      }
		      $this->render('serial', array('alldata'=>$alldata, 'whcode'=>$whcodeparam, 'itemname'=>$itemnameparam));
	      } else {
	      	throw new CHttpException(404,'You have no authorization for this operation.');
	      };
      }
      
      public function actionSerialScan()
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      
      		$alldata = array();
      		$whcodeparam = '';
      		$itemnameparam = '';
      		 
      		if (isset($_GET['go'])) {
      			$whcodeparam = $_GET['whcode'];
      			$itemnameparam = $_GET['itemname'];
      			$whs = Yii::app()->db->createCommand()
      			->select("id, code")->from('warehouses')->where('code like :p_code',
      					array(':p_code'=>'%'.$whcodeparam.'%'))
      					->queryAll();
      			foreach($whs as $wh) {
      				$data = Yii::app()->db->createCommand()
      				->select("c.iddetail, a.regnum, a.idatetime, a.iditem, b.name, c.serialnum, concat('${wh['code']}') as code")
      				->from("acquisitions a")
      				->join("detailacquisitions c", "c.id = a.id")
      				->join('items b', 'b.id = a.iditem')
      				->where("b.name like :p_name and a.idwarehouse = '${wh['id']}'", 
      					array(':p_name'=>"%$itemnameparam%"))
      				->order('b.name')
      				->queryAll();
      				$alldata = array_merge($alldata, $data);
		      	}
		      	usort($alldata, 'cmp');
			}
			$this->render('serialscan', array('alldata'=>$alldata, 'whcode'=>$whcodeparam, 'itemname'=>$itemnameparam));
		} else {
	      	throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
}