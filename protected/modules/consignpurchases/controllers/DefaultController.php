<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC5';
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
                $this->state='c';
                $this->trackActivity('c');    
                    
                $model=new Consignpurchases;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Consignpurchases'])) {
                   Yii::app()->session['Consignpurchases']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Consignpurchases'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                   if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Consignpurchases'];
                      
                      $this->beforePost($model);
                      $respond=$model->save();
                      if(!$respond) {
                          throw new CHttpException(404,serialize($model->errors));
                      }
                      
                      if(isset(Yii::app()->session['Detailconsignpurchases']) ) {
                        $details=Yii::app()->session['Detailconsignpurchases'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } else {
                          throw new CHttpException(404,'There is an error in detail posting');
                      }
                      
                      if($respond) {
                      	 $this->afterPost($model);
                         Yii::app()->session->remove('Consignpurchases');
                         Yii::app()->session->remove('Detailconsignpurchases');
                         $this->redirect(array('view','id'=>$model->id));
                      } 

                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if ($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Consignpurchases'];
                         Yii::app()->session['Consignpurchases']=$model->attributes;
                         $this->redirect(array('detailconsignpurchases/create',
                            'id'=>$model->id, 'regnum'=>$model->regnum));
                      } else if($_POST['command']=='setPO') {
                         $model->attributes=$_POST['Consignpurchases'];
                         $idsupplier = '';
                         $data = $this->setPO($model->id, $model->idorder, $idsupplier);
                         $model->idsupplier = $idsupplier;
                         $this->sumDetail($model, $data);
                         Yii::app()->session['Detailconsignpurchases'] = $data;
                         Yii::app()->session['Consignpurchases']=$model->attributes;
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

             $this->state='u';
             $this->trackActivity('u');

             $model=$this->loadModel($id);
             $this->afterEdit($model);
             
             Yii::app()->session['master']='update';

             if(!isset(Yii::app()->session['Consignpurchases']))
                Yii::app()->session['Consignpurchases']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Consignpurchases'];

             if(!isset(Yii::app()->session['Detailconsignpurchases'])) 
               Yii::app()->session['Detailconsignpurchases']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Consignpurchases'];
                     $this->beforePost($model);
                     $this->tracker->modify('consignpurchases', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailconsignpurchases'])) {
                         $details=Yii::app()->session['Detailconsignpurchases'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     

                     if(isset(Yii::app()->session['DeleteDetailconsignpurchases'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailconsignpurchases'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if($respond) {
                         Yii::app()->session->remove('Consignpurchases');
                         Yii::app()->session->remove('Detailconsignpurchases');
                         Yii::app()->session->remove('DeleteDetailconsignpurchases');
                         $this->redirect(array('view','id'=>$model->id));
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

            $this->trackActivity('d');
            $model=$this->loadModel($id);    
            $this->beforeDelete($model);
            $this->tracker->delete('consignpurchases', $id);

            $detailmodels=Detailconsignpurchases::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailconsignpurchases', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }

            $model->delete();
            $this->afterDelete();

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

               Yii::app()->session->remove('Consignpurchases');
               Yii::app()->session->remove('Detailconsignpurchases');
               Yii::app()->session->remove('DeleteDetailconsignpurchases');
               $dataProvider=new CActiveDataProvider('Consignpurchases',
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
               
                $model=new Consignpurchases('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consignpurchases']))
			$model->attributes=$_GET['Consignpurchases'];

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
                $this->tracker->restore('consignpurchases', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Consignpurchases');
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
                $this->tracker->restoreDeleted('consignpurchases', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Consignpurchases');
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
	 * @return Consignpurchases the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Consignpurchases::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Consignpurchases $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='consignpurchases-form')
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
             $model=new Consignpurchases;
             $model->attributes=Yii::app()->session['Consignpurchases'];

             $details=Yii::app()->session['Detailconsignpurchases'];
             $this->afterInsertDetail($model, $details);
			Yii::app()->session['Consignpurchases'] = $model->attributes;

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

             $model=new Consignpurchases;
             $model->attributes=Yii::app()->session['Consignpurchases'];

             $details=Yii::app()->session['Detailconsignpurchases'];
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


             $model=new Consignpurchases;
             $model->attributes=Yii::app()->session['Consignpurchases'];

             $details=Yii::app()->session['Detailconsignpurchases'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
     
	public function actionPrint($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
			Yii::app()->user->id)) {
			$this->trackActivity('p');
      
			$model=$this->loadModel($id);
			$detailmodel=$this->loadDetails($id);
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.consignpurchases.components.*');
			require_once('print_consignpurchases.php');
			ob_clean();
      
			execute($model, $detailmodel);	
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
    }
	
	public function actionGetreport()
    {
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      		$this->render('report');
      	} else {
      		throw new CHttpException(404,'You have no authorization for this operation.');
      	};
	}
      
	public function actionReportprint($startdate, $enddate, $order)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {
      
      					
      		$dataSQL = Yii::app()->db->createCommand()
      			->select('a.idatetime, a.regnum, a.idsupplier, b.iditem, b.batchcode, b.qty, b.buyprice, b.sellprice')
      			->from('consignpurchases a')->join('detailconsignpurchases b', 'b.id = a.id')
      			->where('a.idatetime >= :p_startdate and a.idatetime <= :p_enddate',
      				array(':p_startdate'=>$startdate, ':p_enddate'=>$enddate));
      		switch ($order) {
      			case 'B':
      				$dataSQL->order('b.batchcode, a.idatetime');
      			break;
      			case 'S':
      				$dataSQL->order('a.idsupplier, a.idatetime');
      			break;
      			case 'T':
      				$dataSQL->order('a.idatetime, b.batchcode');
      			break;
      		}
      		
      		$reportdata = $dataSQL->queryAll();
      		Yii::import('application.vendors.tcpdf.*');
      		require_once ('tcpdf.php');
      		Yii::import('application.modules.purchases.components.*');
      		require_once('print_consignpurchasereport.php');
      		ob_clean();
      
      		execute($reportdata);
      	} else {
      		throw new CHttpException(404,'You have no authorization for this operation.');
      	}
      
      }
      
     protected function saveNewDetails(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailconsignpurchases;
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
                $detailmodel=Detailconsignpurchases::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detailconsignpurchases;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailconsignpurchases', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailconsignpurchases::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->__DETAILFORMID);
                    $this->tracker->delete('detailconsignpurchases', $detailmodel->id);
                    $respond=$detailmodel->delete();
                    if (!$respond) {
                      break;
                    }
                    Action::deleteDetailStock($detailmodel->iddetail);
                }
            }
            return $respond;
        }

        protected function loadDetails($id)
        {
         $sql="select * from detailconsignpurchases where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();

         return $details;
        }
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getCurrentID2();
            $model->idatetime=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
            $model->status='0';
            $model->paystatus='0';
            $model->discount=0;
            $model->total=0;
        }
        
        protected function afterPost(& $model)
        {
            
            if ($this->state == 'c') {
            	$idmaker=new idmaker();
            	$idmaker->saveRegNum($this->formid, $model->regnum);
            	Action::addStock($model->id, $model->idatetime, $model->regnum, 'Konsinyasi');
            } else if ($this->state == 'u')
            	Action::updateStock($model->id, $model->idatetime);
 
            Yii::import('application.modules.sellingprice.models.*');
            $details = $this->loadDetails($model->id);
            
            foreach($details as $d) {
            	if ($d['sellprice'] > 0) {
            		$sellprice = Sellingprices::model()->findByPk($d['iddetail']);
            		if (is_null($sellprice)) {
            			$sellprice = new Sellingprices();
	            		$sellprice->id = $d['iddetail'];
            			$sellprice->regnum = idmaker::getRegNum('AC11');
            		}
            		$sellprice->idatetime = $model->idatetime;
	            	//$sellprice->iditem = lookup::ItemCodeFromItemID($d['iditem']);
            		$sellprice->iditem = $d['batchcode'];
            		$sellprice->normalprice = $d['sellprice'];
	            	$sellprice->minprice = $d['sellprice'];
	            	$sellprice->approvalby = 'Pak Made';
	            	$sellprice->datetimelog = $d['datetimelog'];
	            	$sellprice->userlog = $d['userlog'];
	            	
	            	if ($sellprice->isNewRecord)
	            		$resp = $sellprice->insert();
	            	else
	            		$resp = $sellprice->save();
	            	if (!$resp) {
	            		throw new CHttpException(100,'There is an error in after post');
	            	}
	            	idmaker::saveRegNum('AC11', $sellprice->regnum);
            	}
            	Action::saveItemBatch($d['iddetail'], $d['iditem'], $d['batchcode'], 
            		$d['buyprice'], $d['userlog'], $d['datetimelog'], $d['sellprice']);
            	
            	if ($this->state == 'c')
            		Action::addDetailStock($d['iddetail'], $d['id'], $d['iditem'], $d['batchcode'], $d['qty']);
            	else if ($this->state == 'u')
            		Action::updateDetailStock($d['iddetail'], $d['iditem'], $d['batchcode'], $d['qty']);
            }
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
        }
        
        protected function beforeDelete(& $model)
        {
        	Action::deleteStock($model->id);
        	
        	Yii::import('application.modules.sellingprice.models.*');
        	$details = $this->loadDetails($model->id);
        	
        	Action::deleteDetailStock2($model->id);
        	foreach($details as $d) {
        		if ($d['sellprice'] > 0) {
        			$sellprice = Sellingprices::model()->findByPk($d['iddetail']);
        			if (!is_null($sellprice))
        				$sellprice->delete();
        		}
        		Action::deleteItemBatch($d['iddetail']);
        	}
        }
        
        protected function afterDelete()
        {
               
        }
        
        protected function afterEdit(& $model)
        {
            
        }
        
        protected function afterInsertDetail(& $model, $details)
        {
            $this->sumDetail($model, $details);
        }
        

        protected function afterUpdateDetail(& $model, $details)
        {
        	$this->sumDetail($model, $details);
        }
        
        protected function afterDeleteDetail(& $model, $details)
        {
        	$this->sumDetail($model, $details);
        }
        
        protected function trackActivity($action)
        {
            $this->tracker=new Tracker();
            $this->tracker->init();
            $this->tracker->logActivity($this->formid, $action);
        }
        
        private function sumDetail(& $model, $details)
        {
        	$total=0;
        	$totaldisc=0;
        	foreach ($details as $row) {
        		$total=$total+(($row['sellprice'])*$row['qty']);
        		$totaldisc=0;
        	}
        	$model->total=$total;
        	$model->discount=$totaldisc;
        }
        
        private function setPO($id, $idorder, & $idsupplier)
        {
        	$salesorders = Yii::app()->db->createCommand()
        		->select('b.idsupplier, a.*')->from('detailpurchasesorders a')
        		->join('purchasesorders b', 'b.id = a.id')
        		->join('items c', 'c.id = a.iditem')
        		->where('b.regnum = :p_regnum and c.type = :p_type', 
        			array(':p_regnum'=>$idorder, ':p_type'=>'1'))
        		->queryAll();	
        	
        	$idsupplier = $salesorders[0]['idsupplier'];
        	
        	$received = Yii::app()->db->createCommand()
        		->select('a.*')->from('detailconsignpurchases a')
        		->join('consignpurchases b', 'b.id = a.id')
        		->where('b.idorder = :p_idorder', array(':p_idorder'=>$idorder))
        		->queryAll();
        	
        	$data = array();
        	
        	foreach($salesorders as $so) {
        		$found = FALSE;
        		foreach($received as $rd) {
        			if ($rd['iditem'] == $so['iditem'])	{
        				$found = TRUE;
        				if ($so['qty'] - $rd['qty'] > 0) {
        					$temp['id'] = $id;
        					$temp['iddetail'] = idmaker::getCurrentID2();
        					$temp['iditem'] = $so['iditem'];
        					$temp['qty'] = $so['qty'] - $rd['qty'];
        					$temp['price'] = $so['price'];
        					$temp['userlog'] = Yii::app()->user->id;
        					$temp['datetimelog'] = idmaker::getDateTime();
        					$data[] = $temp;
        				};
        				break;
        			}
        		};
        		if (! $found ) {
        			$temp['id'] = $id;
        			$temp['iddetail'] = idmaker::getCurrentID2();
        			$temp['iditem'] = $so['iditem'];
					$temp['qty'] = $so['qty'];
        			$temp['price'] = $so['price'];
        			$temp['discount'] = 0;
        			$temp['userlog'] = Yii::app()->user->id;
					$temp['datetimelog'] = idmaker::getDateTime();
        			$data[] = $temp;
        		}
        	}
        	return $data;
        }
        
	
}
