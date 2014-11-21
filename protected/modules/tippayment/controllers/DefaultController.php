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
	public $formid='AC23';
	public $tracker;
	public $state;
	
	private $salesdata = array();

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
                $compositions = array();
                
                $this->state='create';
                $this->trackActivity('c');    
                    
                $model=new Tippayments;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Tippayments'])) {
                   Yii::app()->session['Tippayments']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Tippayments'];
                }
                if (isset($_POST['Tippayments'])) {
                	$model->attributes=$_POST['Tippayments'];
                }
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);
				
				if (isset($_POST)){
					
					if(isset($_POST['yt0'])) {
						$model->attributes=$_POST['Tippayments'];
                      //The user pressed the button;
						$this->beforePost($model);
						$respond=$this->checkWarehouse($model->idwarehouse);
						if (!$respond)
	                      	throw new CHttpException(5000,'Lokasi anda tidak terdaftar');
						$respond = $this->checkSerialNum(Yii::app()->session['Detailtippayments'], $model->idwarehouse);
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
	
						if(isset(Yii::app()->session['Detailtippayments']) ) {
							$details=Yii::app()->session['Detailtippayments'];
							$respond=$respond&&$this->saveNewDetails($details, $model->idwarehouse	);
						} 
	
						$this->afterPost($model);
						Yii::app()->session->remove('Tippayments');
						Yii::app()->session->remove('Detailtippayments');
						Yii::app()->session->remove('Deletedetailtippayments');
						$this->redirect(array('view','id'=>$model->id));

					} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if($_POST['command']=='adddetail') {
							$model->attributes=$_POST['Tippayments'];
							Yii::app()->session['Tippayments']=$_POST['Tippayments'];
							$this->redirect(array('detailtippayments/create',
                            	'id'=>$model->id));
                      	} else if ($_POST['command']=='getPO') {
                        	$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
                         	$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                      	} else if ($_POST['command']=='updateDetail') {
							$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
                      	} else if ($_POST['command']=='getComp') {
							$model->attributes=$_POST['Tippayments'];
							$compositions = $this->getCompositions($model->idpartner);
							if (count($compositions) == 0) 
								$model->idcomp = '-';
                         	Yii::app()->session['Tippayments']=$model->attributes;
                      	} else if ($_POST['command']=='countTip') {
							$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
                         	$this->getSales($model->id, $model->idsticker, $model->ddatetime);
                         	Yii::app()->session['Detailtippayments'] = $this->salesdata;
                         	Yii::app()->session['Detailtippayments2'] = $this->getSalesDetail($model->idpartner, $model->idcomp, 
                         		$model->idsticker, $model->ddatetime);
                      	} 
					}
				}

				$this->render('create',array(
                    'model'=>$model, 'compositions'=>$compositions
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

			if(!isset(Yii::app()->session['Tippayments']))
                Yii::app()->session['Tippayments']=$model->attributes;
			else
                $model->attributes=Yii::app()->session['Tippayments'];

			if(!isset(Yii::app()->session['Detailtippayments'])) 
				Yii::app()->session['Detailtippayments']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST)) {
				if(isset($_POST['yt0'])) {
                      //The user pressed the button;
					$model->attributes=$_POST['Tippayments'];
                       
					$this->beforePost($model);
					$respond=$this->checkWarehouse($model->idwarehouse);
					if (!$respond)
						throw new CHttpException(5000,'Lokasi anda tidak terdaftar');
					$respond = $this->checkSerialNum(Yii::app()->session['Detailtippayments'], $model->idwarehouse);
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
	
					if(isset(Yii::app()->session['Detailtippayments']) ) {
						$details=Yii::app()->session['Detailtippayments'];
						$respond=$this->saveDetails($details, $model->idwarehouse);
						if (!$respond)
							throw new CHttpException(5002,'There is an error in detail posting');
					} 
	
					$this->afterPost($model);
					Yii::app()->session->remove('Tippayments');
					Yii::app()->session->remove('Detailtippayments');
					Yii::app()->session->remove('Deletedetailtippayments');
					
					$this->redirect(array('view','id'=>$model->id));

				} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if($_POST['command']=='adddetail') {
							$model->attributes=$_POST['Tippayments'];
							Yii::app()->session['Tippayments']=$_POST['Tippayments'];
							$this->redirect(array('detailtippayments/create',
                            	'id'=>$model->id));
                      	} else if ($_POST['command']=='getPO') {
                        	$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
                         	$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                      	} else if ($_POST['command']=='updateDetail') {
							$model->attributes=$_POST['Tippayments'];
                         	Yii::app()->session['Tippayments']=$_POST['Tippayments'];
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
            $this->tracker->delete('tippayments', $id);

            $detailmodels=Detailtippayments::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailtippayments', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Tippayments');
               Yii::app()->session->remove('Detailtippayments');
               Yii::app()->session->remove('Deletedetailtippayments');
               Yii::app()->session->remove('Detailtippayments2');
               Yii::app()->session->remove('Deletedetailtippayments2');
               $dataProvider=new CActiveDataProvider('Tippayments',
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
               
                $model=new Tippayments('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tippayments']))
			$model->attributes=$_GET['Tippayments'];

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
                $this->tracker->restore('tippayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Tippayments');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('tippayments')
                ->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                ->queryScalar();
                $this->tracker->restoreDeleted('detailtippayments', "id", $id );
                $this->tracker->restoreDeleted('tippayments', "idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Tippayments');
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
	 * @return Tippayments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tippayments::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tippayments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tippayments-form')
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
             $model=new Tippayments;
             $model->attributes=Yii::app()->session['Tippayments'];

             $details=Yii::app()->session['Detailtippayments'];
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

             $model=new Tippayments;
             $model->attributes=Yii::app()->session['Tippayments'];

             $details=Yii::app()->session['Detailtippayments'];
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


             $model=new Tippayments;
             $model->attributes=Yii::app()->session['Tippayments'];

             $details=Yii::app()->session['Detailtippayments'];
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
             $detailmodel=new Detailtippayments;
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
             $detailmodel=Detailtippayments::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailtippayments;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailtippayments', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailtippayments::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailtippayments', $detailmodel->iddetail);
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
      $sql="select * from detailtippayments where id='$id'";
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
         $model->idcomp = '-';
         $model->amount = 0;
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
            Yii::app()->session['Detailtippayments']);
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
        Yii::app()->session->remove('Detailtippayments');
        $sql=<<<EOS
    	select count(*) as received from tippayments a 
		join detailtippayments b on b.id = a.id
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
        Yii::app()->session['Detailtippayments']=$details;
      }
     
      private function loadLPB2($nolpb, $id, $idwh)
      {
      	$details=array();
      
      	$dataLPB=Yii::app()->db->createCommand()
      		->select('a.id, b.iditem, sum(b.qty) as qty')
      		->from('purchasestippayments a')
      		->join('detailpurchasestippayments b', 'b.id=a.id')
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
      	
      	Yii::app()->session->remove('Detailtippayments');
      	if ($dataLPB !== FALSE) {
	      	$sql=<<<EOS
	    	select count(*) as received from tippayments a
			join detailtippayments b on b.id = a.id
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
			Yii::app()->session['Detailtippayments']=$details;
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
			->from('purchasestippayments a')
			->join('detailpurchasestippayments b', 'b.id=a.id')
			->where('a.regnum = :p_regnum', array(':p_regnum'=>$nolpb) )
			->group('b.iditem')
			->queryAll();
		}
		 
		Yii::app()->session->remove('Detailtippayments');
		if ($dataLPB !== FALSE) {
			$sql=<<<EOS
	    	select count(*) as received from tippayments a
			join detailtippayments b on b.id = a.id
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
			Yii::app()->session['Detailtippayments']=$details;
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
      
      private function getCompositions($idpartner)
      {
      	return Yii::app()->db->createCommand()
      		->select('iddetail, comname')
      		->from('detailpartners')
      		->where("id = :p_id", array(':p_id'=>$idpartner))
      		->queryAll();   	
      }
      
    private function getSales($id, $idsticker, $ddatetime)
    {
    	$select1 = <<<EOS
 	a.id as iddetail, a.regnum as invnum, (a.total - a.tax) as amount, 
    a.discount, sum(b.qty*b.price) as totalnondisc
EOS;
   		$this->salesdata = Yii::app()->db->createCommand()
   			->select($select1)->from('salespos a')
   			->join('detailsalespos b', 'b.id = a.id')
   			->where("a.idsticker = :p_idsticker and a.idatetime like :p_datetime and b.discount = 0",
   				array(':p_idsticker'=>$idsticker, ':p_datetime'=>$ddatetime.'%'))
   			->group('a.regnum')
   			->queryAll(); 	
   		foreach($this->salesdata as & $sd) {
   			$sd['id'] = $id;	
   		};
    }
    
    private function getUnSeenDisc($regnum)
    {
 		$disc = 0;
    	foreach($this->salesdata as $sd) {
    		if ($sd['invnum'] == $regnum) {
    			$disc = $sd['discount'] / $sd['totalnondisc'];
    			break;
    		}	
    	}
    	return $disc;
    }
    
    private function getSalesDetail($idpartner, $idcomp, $idsticker, $ddatetime)
    {
    	if ($idcomp == '-') {
    		$tip = Yii::app()->db->createCommand()->select('a.defaulttip')->from('partners a')
	    		->where("a.id = :p_id ",
	    			array(':p_id'=>$idpartner))
	    		->queryScalar();
    	} else {
    		$tip = Yii::app()->db->createCommand()->select('a.tip')->from('detailpartners a')
	    		->where("a.id = :p_id and a.iddetail = :p_iddetail",
	    			array(':p_id'=>$idpartner, ':p_iddetail'=>$idcomp))
	    		->queryScalar();
    	}
    	$sql1 = <<<EOS
    	SELECT b.iddetail, a.regnum, b.iditem, b.qty, b.price, b.discount, c.pct, c.name
		FROM detailsalespos b
		JOIN salespos a ON a.id = b.id
		LEFT JOIN (
		detailitemtipgroups d 
		JOIN itemtipgroups c ON c.id = d.id
		) ON d.iditem = b.iditem
    	where a.idsticker = '$idsticker' and a.idatetime like '$ddatetime%' 
EOS;
		$detailsales = Yii::app()->db->createCommand($sql1)
			->queryAll();
    
    	foreach($detailsales as & $ds) {
    		if ($ds['discount'] == 0) {
    			$ds['discount'] = $this->getUnSeenDisc($ds['regnum']);
    		}
    		
    		if ( is_null($ds['pct']) ) {
    			$ds['pct'] = $tip;
    			$ds['tipgroupname'] = 'Komisi Standar';
    		}
    		$ds['amount'] = ($ds['price'] - $ds['discount']) * $ds['qty'] * $ds['pct'] / 100;
    	}
    	    	
    	return $detailsales;
    }
      
}