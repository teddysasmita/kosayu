<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC34';
	public $tracker;
	public $state;
	private $recapdetails = array();
	private $invdetails = array();
	
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
                $error = '';
                    
                $model=new Deliveryreplaces;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Deliveryreplaces'])) {
                   Yii::app()->session['Deliveryreplaces']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Deliveryreplaces'];
                }
                
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);
               

				if (isset($_POST)){
					if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                   		$details = $this->processSelectedItems($_POST['yw0_c4']);
						$allitems = $details;
                   		
						$model->attributes=$_POST['Deliveryreplaces'];
                      
                      	$this->beforePost($model);
                      
                      
						$respond=$model->save();
						if(!$respond)
							throw new CHttpException(404,'There is an error in master posting');
							                     
						if (count($details)>0) 
							$respond= $this->saveNewDetails($details);
						
						if (!$respond)
							throw new CHttpException(404,'There is an error in detail posting');

						$this->afterPost($model);
						
						Yii::app()->session->remove('Deliveryreplaces');
						Yii::app()->session->remove('Detaildeliveryreplaces');
						$this->redirect(array('view','id'=>$model->id));
                   } else if (isset($_POST['command'])){
                   	
                      // save the current master data before going to the detail page
                      if($_POST['command']=='loadDelivery') {
						$model->attributes=$_POST['Deliveryreplaces'];
						Yii::app()->session['Detaildeliveryreplaces']=$this->loadDelivery($model);
                      } 
                   }
                }

                $this->render('create',array(
                    'model'=>$model, 'form_error'=>$error
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

             if(!isset(Yii::app()->session['Deliveryreplaces']))
                Yii::app()->session['Deliveryreplaces']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Deliveryreplaces'];

             if(!isset(Yii::app()->session['Detaildeliveryreplaces'])) 
               Yii::app()->session['Detaildeliveryreplaces']=$this->loadDetails($id);
             
             if(!isset(Yii::app()->session['Detaildeliveryreplaces2'])) 
               Yii::app()->session['Detaildeliveryreplaces2']=$this->loadDetails2($id);
             
            

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Deliveryreplaces'];
                     // Uncomment the following line if AJAX validation is needed
                     $this->performAjaxValidation($model);
                     $this->beforePost($model);
                     $this->tracker->modify('deliveryreplaces', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detaildeliveryreplaces'])) {
                         $details=Yii::app()->session['Detaildeliveryreplaces'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetaildeliveryreplaces'])) {
                         $deletedetails=Yii::app()->session['DeleteDetaildeliveryreplaces'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetaildeliveryreplaces2'])) {
                         $deletedetails=Yii::app()->session['DeleteDetaildeliveryreplaces2'];
                         $respond=$respond&&$this->deleteDetails2($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail2 deletion');
                         }
                     };

                     if($respond) {
                         Yii::app()->session->remove('Deliveryreplaces');
                         Yii::app()->session->remove('Detaildeliveryreplaces');
                         Yii::app()->session->remove('DeleteDetaildeliveryreplaces');
                         $this->redirect(array('view','id'=>$model->id));
                     }
                 }
             }

             $this->render('update',array(
                     'model'=>$model,'form_error'=>''
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
            $this->tracker->delete('deliveryreplaces', $id);

            $detailmodels=Detaildeliveryreplaces::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detaildeliveryreplaces', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }

            $detailmodels=Detaildeliveryreplaces2::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
            	$this->tracker->init();
               $this->tracker->delete('detaildeliveryreplaces2', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Deliveryreplaces');
               Yii::app()->session->remove('Detaildeliveryreplaces');
               Yii::app()->session->remove('DeleteDetaildeliveryreplaces');
               $dataProvider=new CActiveDataProvider('Deliveryreplaces',
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
               
                $model=new Deliveryreplaces('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Deliveryreplaces']))
			$model->attributes=$_GET['Deliveryreplaces'];

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
                $this->tracker->restore('deliveryreplaces', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Deliveryreplaces');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('deliveryreplaces')
                	->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
               	 	->queryScalar();
                $this->tracker->restoreDeleted('detaildeliveryreplaces', "id", $id );
                $this->tracker->restoreDeleted('detaildeliveryreplaces2', "id", $id );
                $this->tracker->restoreDeleted('deliveryreplaces',"idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Deliveryreplaces');
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
	 * @return Deliveryreplaces the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Deliveryreplaces::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Deliveryreplaces $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='deliveryreplaces-form')
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
             $model=new Deliveryreplaces;
             $model->attributes=Yii::app()->session['Deliveryreplaces'];

             $details=Yii::app()->session['Detaildeliveryreplaces'];
             $this->afterInsertDetail($model, $details);

             $this->render('create',array(
                 'model'=>$model, 'form_error'=>''
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         } 
      }
      
      public function actionUpdateDetail()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {

             $model=new Deliveryreplaces;
             $model->attributes=Yii::app()->session['Deliveryreplaces'];

             $details=Yii::app()->session['Detaildeliveryreplaces'];
             $this->afterUpdateDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model, 'form_error'=>''
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
            
      public function actionDeleteDetail()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {


             $model=new Deliveryreplaces;
             $model->attributes=Yii::app()->session['Deliveryreplaces'];

             $details=Yii::app()->session['Detaildeliveryreplaces'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
     protected function saveNewDetails(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detaildeliveryreplaces;
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
                $detailmodel=Detaildeliveryreplaces::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detaildeliveryreplaces;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detaildeliveryreplaces', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detaildeliveryreplaces::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->__DETAILFORMID);
                    $this->tracker->delete('detaildeliveryreplaces', $detailmodel->iddetail);
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
         $sql="select * from detaildeliveryreplaces where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();

         return $details;
        }
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getCurrentID2();
            $model->idatetime=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
            $lookup=new lookup();
            $model->status=$lookup->reverseOrderStatus('Belum Diproses');
        }
        
        protected function afterPost(& $model)
        {
            $idmaker=new idmaker();
            if ($this->state == 'create')
            	$idmaker->saveRegNum($this->formid, substr($model->regnum, 2));    
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            if ($this->state == 'create')
            	$model->regnum='RD'.$idmaker->getRegNum($this->formid);
        }
        
        protected function beforeDelete(& $model)
        {
            
        }
        
        protected function afterDelete()
        {
               
        }
        
        protected function afterEdit(& $model)
        {
            
        }
        
        protected function afterInsertDetail(& $model, $details)
        {
            //$this->sumDetail($model, $details);
        }
        
        
        protected function afterUpdateDetail(& $model, $details)
        {
        	//$this->sumDetail($model, $details);
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
        		$total=$total+(($row['price']+$row['cost1']+$row['cost2'])*$row['qty']);
        		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
        	}
        	$model->attributes=Yii::app()->session['Deliveryreplaces'];
        	$model->total=$total;
        	$model->discount=$totaldisc;
        }
        
        private function loadDelivery(& $model)
        {
        	$prefix = substr($model->deliverynum, 0, 2); 
        	if ( $prefix == 'SJ')
        		$tablename = 'deliveryorders';
        	else if ( $prefix == 'SM')
        		$tablename = 'deliveryordersnt';
        	
			$info = Yii::app()->db->createCommand()->select()->from($tablename)
				->where('regnum = :p_regnum', array(':p_regnum'=>$model->deliverynum))
        		->queryRow();
			
        	if ($info !== FALSE) {
        		$model->receivername = $info['receivername'];
        		$model->receiveraddress = $info['receiveraddress'];
        		$model->receiverphone = $info['receiverphone'];
        	}
        	
        	$deliverydatas = Yii::app()->db->createCommand()
        		->select('a.regnum, a.idwarehouse as idwhsource, b.iddetail, b.iditem, b.serialnum')
        		->from('stockexits a')
        		->join('detailstockexits b', 'b.id = a.id')
        		->where('a.transid = :p_transid', array(':p_transid'=>$model->deliverynum))
        		->queryAll();
			
        	if (count($deliverydatas) > 0) {
	        	foreach($deliverydatas as & $dd) {
	       			$dd['selected'] = '0';
	        		$dd['userlog'] = Yii::app()->user->id;
	        		$dd['datetimelog'] = idmaker::getDateTime();
	        		$dd['id'] = $model->id;
 	        	}
        	}
        	
        	return $deliverydatas;
        }
	
		public function actionPrintsj($id)
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
        			Yii::app()->user->id)) {
        		$this->trackActivity('p');
        	
        		$model=$this->loadModel($id);
        		$detailmodel=$this->loadDetails($id);
        		$receivable=Yii::app()->db->createCommand()
        			->select('receiveable')->from('salespos')
        			->where('regnum = :p_regnum',array(':p_regnum'=>$model->invnum))
        			->queryScalar();
        			
        		Yii::import('application.vendors.tcpdf.*');
        		require_once ('tcpdf.php');
        		Yii::import('application.modules.deliveryreplaces.components.*');
        		require_once('printsj.php');
        		ob_clean();
        		
        		execute($model, $detailmodel, $receivable);
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }    
	
	private function processSelectedItems(array $selectIDs)
	{
		$found = array();
		foreach($selectIDs as $id) {
			foreach(Yii::app()->session['Detaildeliveryreplaces'] as $temp) {
				if ($temp['iddetail'] == $id) {
					$found[] = $temp;
					break;
				}
			}
		}
		return $found;
	}
	
}
