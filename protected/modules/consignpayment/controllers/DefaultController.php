<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC33';
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
                    
                $model=new Consignpayments;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Consignpayments'])) {
                   Yii::app()->session['Consignpayments']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Consignpayments'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                	if(isset($_POST['yt1'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Consignpayments'];
                      
                      $this->beforePost($model);
                      $respond=$model->save();
                      if(!$respond) {
                          throw new CHttpException(404,'There is an error in master posting');
                      }

                      if(isset(Yii::app()->session['Detailconsignpayments']) ) {
                        $details=Yii::app()->session['Detailconsignpayments'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } 
                      if(!$respond) {
                      	throw new CHttpException(404,'There is an error in detail posting');
                      }	
                      
                      if(isset(Yii::app()->session['Detailconsignpayments2']) ) {
                      	$details2=Yii::app()->session['Detailconsignpayments2'];
                      	$respond=$respond&&$this->saveNewDetails2($details2);
                      }
                      if(!$respond) {
                      	throw new CHttpException(404,'There is an error in detail2 posting');
                      }
                      
                      if(isset(Yii::app()->session['Detailconsignpayments3']) ) {
                      	$details3=Yii::app()->session['Detailconsignpayments3'];
                      	$respond=$respond&&$this->saveNewDetails3($details3);
                      }
                      if(!$respond) {
                      	throw new CHttpException(404,'There is an error in detail2 posting');
                      }
                      
                      if($respond) {
                         $this->afterPost($model);
                         Yii::app()->session->remove('Consignpayments');
                         Yii::app()->session->remove('Detailconsignpayments');
                         Yii::app()->session->remove('Detailconsignpayments2');
                         Yii::app()->session->remove('Detailconsignpayments3');
                         $this->redirect(array('view','id'=>$model->id));
                      } 
                           
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Consignpayments'];
                         Yii::app()->session['Consignpayments']=$_POST['Consignpayments'];
                         //$this->redirect(array('detailconsignpayments/create',
                            //'id'=>$model->id));
                      } else if ($_POST['command']=='setSupplier') {
                         $model->attributes=$_POST['Consignpayments'];
                         Yii::app()->session['Consignpayments']=$_POST['Consignpayments'];
                         Yii::app()->session['Detailconsignpayments'] = 
                         	$this->loadConsign( $model->idsupplier, $model->id, 
                         	$model->ldatetime, $model->idatetime);
                      } else if ($_POST['command']=='addpayment') {
                         $model->attributes=$_POST['Consignpayments'];
                         Yii::app()->session['Consignpayments']=$_POST['Consignpayments'];
                         //$this->redirect(array('detailconsignpayments/create',
                            //'id'=>$model->id));
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

             if(!isset(Yii::app()->session['Consignpayments']))
                Yii::app()->session['Consignpayments']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Consignpayments'];

             if(!isset(Yii::app()->session['Detailconsignpayments'])) 
               Yii::app()->session['Detailconsignpayments']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt1'])) {
                     $model->attributes=$_POST['Consignpayments'];
                     $this->beforePost($model);
                     $this->tracker->modify('consignpayments', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailconsignpayments'])) {
                         $details=Yii::app()->session['Detailconsignpayments'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailconsignpayments'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailconsignpayments'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if(isset(Yii::app()->session['Detailconsignpayments2'])) {
                     	$details=Yii::app()->session['Detailconsignpayments2'];
                     	$respond=$respond&&$this->saveDetails2($details);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail2 posting');
                     	}
                     };
                      
                     /*if(isset(Yii::app()->session['DeleteDetailconsignpayments2'])) {
                     	$deletedetails=Yii::app()->session['DeleteDetailconsignpayments2'];
                     	$respond=$respond&&$this->deleteDetails($deletedetails);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail2 deletion');
                     	}
                     };*/
                     
                     if(isset(Yii::app()->session['Detailconsignpayments3'])) {
                     	$details=Yii::app()->session['Detailconsignpayments3'];
                     	$respond=$respond&&$this->saveDetails($details);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail3 posting');
                     	}
                     };
                      
                     if(isset(Yii::app()->session['DeleteDetailconsignpayments3'])) {
                     	$deletedetails=Yii::app()->session['DeleteDetailconsignpayments3'];
                     	$respond=$respond&&$this->deleteDetails($deletedetails);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail3 deletion');
                     	}
                     };
                    
                     if($respond) {
                     	$this->afterPost($model);
                         Yii::app()->session->remove('Consignpayments');
                         Yii::app()->session->remove('Detailconsignpayments');
                         Yii::app()->session->remove('Detailconsignpayments2');
                         Yii::app()->session->remove('Detailconsignpayments3');
                         Yii::app()->session->remove('DeleteDetailconsignpayments');
                         Yii::app()->session->remove('DeleteDetailconsignpayments2');
                         Yii::app()->session->remove('DeleteDetailconsignpayments3');
                         $this->redirect(array('view','id'=>$model->id));
                     }
                 } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Consignpayments'];
                         Yii::app()->session['Consignpayments']=$_POST['Consignpayments'];
                         //$this->redirect(array('detailconsignpayments/create',
                            //'id'=>$model->id));
                      } else if ($_POST['command']=='setSupplier') {
                         $model->attributes=$_POST['Consignpayments'];
                         Yii::app()->session['Consignpayments']=$_POST['Consignpayments'];
                         Yii::app()->session['Detailconsignpayments'] = 
                         	$this->loadConsign($model->idsupplier, $model->id);
                         Yii::app()->session['Detailconsignpayments2'] =
                         	$this->loadReturs($model->idsupplier, $model->id);
                      } else if($_POST['command']=='adddetail2') {
                         $model->attributes=$_POST['Consignpayments'];
                         Yii::app()->session['Consignpayments']=$_POST['Consignpayments'];
                         $details2 = Yii::app()->session['Detailconsignpayments2'];
                         $details = Yii::app()->session['Detailconsignpayments'];
                         $this->matchRetur($details2, $_POST['yw2_c2']);
                         Yii::app()->session['Detailconsignpayments2'] = $details2;
                         $this->sumDetail($model, $details, $details2);
                         //$this->redirect(array('detailconsignpayments/create',
                            //'id'=>$model->id));
                      } else if ($_POST['command']=='addpayment') {
                         $model->attributes=$_POST['Consignpayments'];
                         Yii::app()->session['Consignpayments']=$_POST['Consignpayments'];
                         //$this->redirect(array('detailconsignpayments/create',
                            //'id'=>$model->id));
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
            $this->tracker->delete('consignpayments', $id);

            $detailmodels=Detailconsignpayments::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailconsignpayments', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }
            
            $detailmodels=Detailconsignpayments2::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
            	$this->tracker->init();
            	$this->tracker->delete('detailconsignpayments2', array('iddetail'=>$dm->iddetail));
            	$dm->delete();
            }
			
            $detailmodels=Payments::model()->findAll('idtransaction=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
            	$this->tracker->init();
            	$this->tracker->delete('payments', array('id'=>$dm->id));
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

         Yii::app()->session->remove('Consignpayments');
         Yii::app()->session->remove('Detailconsignpayments');
         Yii::app()->session->remove('Detailconsignpayments2');
         Yii::app()->session->remove('Detailconsignpayments3');
         Yii::app()->session->remove('DeleteDetailconsignpayments');
         $dataProvider=new CActiveDataProvider('Consignpayments',
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
         $model=new Consignpayments('search');
         $model->unsetAttributes();  // clear any default values
         if(isset($_GET['Consignpayments'])){
            $model->attributes=$_GET['Consignpayments'];
         }
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
                $this->tracker->restore('consignpayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Consignpayments');
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
                $this->tracker->restoreDeleted('consignpayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Consignpayments');
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
	 * @return Consignpayments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Consignpayments::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Consignpayments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='consignpayments-form')
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
             $model=new Consignpayments;
             $model->attributes=Yii::app()->session['Consignpayments'];

             $details=Yii::app()->session['Detailconsignpayments'];
             $details2 = Yii::app()->session['Detailconsignpayments2'];
            
			if (is_array($details) && is_array($details2)) {
             	$this->afterInsertDetail($model, $details, $details2);
			}
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

             $model=new Consignpayments;
             $model->attributes=Yii::app()->session['Consignpayments'];

             $details =Yii::app()->session['Detailconsignpayments'];
             $details2 = Yii::app()->session['Detailconsignpayments2'];
             $this->afterUpdateDetail($model, $details, $details2);

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


             $model=new Consignpayments;
             $model->attributes=Yii::app()->session['Consignpayments'];

             $details=Yii::app()->session['Detailconsignpayments'];
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
			if ($row['amount'] > 0) {
				$detailmodel=new Detailconsignpayments;
             	$detailmodel->attributes=$row;
             	$respond=$detailmodel->insert();
             	if (!$respond) {
                	break;
             	} else {
             		$purchasetotal=$detailmodel->total-$detailmodel->discount;
             		$left=$detailmodel->total-($detailmodel->discount+$detailmodel->paid+
             			$detailmodel->amount);
             		if ($purchasetotal == $left)
             			Action::setPaymentStatusPurchase($detailmodel->idpurchase, '0');
             		else if ($left>0)
             			Action::setPaymentStatusPurchase($detailmodel->idpurchase, '1');
             		else if ($left==0)
             			Action::setPaymentStatusPurchase($detailmodel->idpurchaseo, '2');
         		}
			}
		}	
		return $respond;
     }
     
     protected function saveNewDetails2(array $details)
     {
     	foreach ($details as $row) {
     		if ($row['checked'] == 1) {
     			$detailmodel=new Detailconsignpayments2;
     			$row1 = $row;
     			unset($row1['checked']);
     			$detailmodel->attributes=$row1;
     			$respond=$detailmodel->insert();
     			if (!$respond) {
     				break;
     			} 
     		}
     	}
     	return $respond;
     }
     
     protected function saveNewDetails3(array $details)
     {
     	foreach ($details as $row) {
     			$detailmodel=new Payments;
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
             $detailmodel=Detailconsignpayments::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailconsignpayments;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailconsignpayments', array('iddetail'=>$detailmodel->iddetail));
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
      
     protected function saveDetails2(array $details)
     {
     	$idmaker=new idmaker();
     
     	$respond=true;
     	foreach ($details as $row) {
     		$detailmodel=Detailconsignpayments2::model()->findByPk($row['iddetail']);
     		if($detailmodel==NULL) {
     			$detailmodel=new Detailconsignpayments2;
     		} else {
     			if(count(array_diff($detailmodel->attributes,$row))) {
     				$this->tracker->init();
     				$this->tracker->modify('detailconsignpayments2', array('iddetail'=>$detailmodel->iddetail));
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
     
     protected function saveDetails3(array $details)
     {
     	$idmaker=new idmaker();
     	 
     	$respond=true;
     	foreach ($details as $row) {
     		$detailmodel=Payments::model()->findByPk($row['iddetail']);
     		if($detailmodel==NULL) {
     			$detailmodel=new Payments;
     		} else {
     			if(count(array_diff($detailmodel->attributes,$row))) {
     				$this->tracker->init();
     				$this->tracker->modify('payments', array('id'=>$detailmodel->id));
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
			$detailmodel=Detailconsignpayments::model()->findByPk($row['iddetail']);
			if($detailmodel) {
				$this->tracker->init();
				$this->trackActivity('d', $this->__DETAILFORMID);
				$this->tracker->delete('detailconsignpayments', $detailmodel->id);
				$respond=$detailmodel->delete();
				if (!$respond) {
					break;
				} 
			}
		}
		return $respond;
	}

	protected function deleteDetails2(array $details)
	{
		$respond=true;
		foreach ($details as $row) {
			$detailmodel=Detailconsignpayments2::model()->findByPk($row['iddetail']);
			if($detailmodel) {
				$this->tracker->init();
     			$this->trackActivity('d', $this->__DETAILFORMID);
     			$this->tracker->delete('detailconsignpayments2', $detailmodel->id);
     			$respond=$detailmodel->delete();
     			if (!$respond) {
     				break;
     			}
			}
		}
     	return $respond;
	}
	
	protected function deleteDetails3(array $details)
	{
		$respond=true;
		foreach ($details as $row) {
			$detailmodel=Payments::model()->findByPk($row['id']);
			if($detailmodel) {
				$this->tracker->init();
				$this->trackActivity('d', $this->__DETAILFORMID);
				$this->tracker->delete('payments', $detailmodel->idtransaction);
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
		$sql="select * from detailconsignpayments where id='$id'";
		$details=Yii::app()->db->createCommand($sql)->queryAll();

		return $details;
	}

	protected function loadDetails2($id)
	{
		$sql="select * from detailconsignpayments2 where id='$id'";
     	$details=Yii::app()->db->createCommand($sql)->queryAll();
     
     	return $details;
     }
     
	protected function loadDetails3($id)
	{
		$sql="select * from payments where idtransaction='$id'";
		$details=Yii::app()->db->createCommand($sql)->queryAll();
     	 
     	return $details;
	}
     
     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         $model->status='0';
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         $idmaker->saveRegNum($this->formid, $model->regnum);
         /*Action::addFinancePayment(
         	lookup::SupplierNameFromSupplierID($model->idsupplier), $model->idatetime, 
         	$model->idatetime, $model->total);*/
         $details = $this->loadDetails($model->id);
         foreach($details as $d) {
			$left = $d['total'] - ($d['discount'] + $d['paid'] + $d['amount']);
         	if ($d['total'] - $d['discount'] == $left)
         		Action::setPaymentStatusPurchase($d['idpurchase'], '0');
         	else if ($left > 0)
         		Action::setPaymentStatusPurchase($d['idpurchase'], '1');
         	else if ($left == 0)
         		Action::setPaymentStatusPurchase($d['idpurchase'], '2');         	
         }
         
         $details2 = $this->loadDetails2($model->id);
         foreach($details2 as $d) {
         	Action::setStatusRetur($d['idpurchaseretur'], '1');
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
		$details = $this->loadDetails($model->id);
     	foreach($details as $d) {
     		Action::updatePaymentStatusPurchase($d['idpurchase']);
     	}
     	 
     	$details2 = $this->loadDetails2($model->id);
     	foreach($details2 as $d) {
     		Action::setStatusRetur($d['idpurchaseretur'], '0');
     	}
	}

     protected function afterDelete(& $model)
     {

     }

     protected function afterEdit(& $model)
     {

     }

     protected function afterInsertDetail(& $model, $details, $details2)
     {
		$this->sumDetail($model, $details, $details2);
     }


     protected function afterUpdateDetail(& $model, $details, $details2)
     {
		$this->sumDetail($model, $details, $details2);
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
     
	private function loadConsign($idsupplier, $id, $start, $end)
    {
    	$idsupplier = lookup::SupplierCodeFromID($idsupplier);
        $soldqty=Yii::app()->db->createCommand()
           ->select('sum(b.qty) as soldqty, b.batchcode')
           ->from('stocks a')->join('detailstocks b', 'b.id = a.id')
           ->where('b.batchcode like :p_batchcode and a.idatetime >= :p_start', 
           		array(':p_batchcode'=>$idsupplier.'%', ':p_start'=>$start))
           ->andWhere('a.idatetime <= :p_end', array(':p_end'=>$end ))
           ->andWhere('a.transtype <= :p_sold', array(':p_end'=>'Penjualan'))
           ->queryAll();
        
        $salereturqty=Yii::app()->db->createCommand()
        	->select('sum(b.qty) as returqty, b.batchcode')
        	->from('stocks a')->join('detailstocks b', 'b.id = a.id')
        	->where('b.batchcode like :p_batchcode and a.idatetime >= :p_start',
        		array(':p_batchcode'=>$idsupplier.'%', ':p_start'=>$start))
        		->andWhere('a.idatetime <= :p_end', array(':p_end'=>$end ))
        		->andWhere('a.transtype <= :p_sold', array(':p_end'=>'Retur Jual'))
        		->queryAll();
        
        Yii::app()->session->remove('Detailconsignpayments');
        foreach($soldqty as & $sq) {
        	$found = false;
        	foreach($salereturqty as $rq) {
        		if ($sq['batchcode'] == $rq['batchcode']) {
        			$found = true;
        			$sq['returqty'] = $rq['returqty'];
        			break;
        		}
        	}
        	if (!$found) {
        		$sq['returqty'] = 0;
        	}
        	$sq['buyprice'] = lookup::getbuyprice($sq['batchcode']);
        	$sq['total'] = ($sq['soldqty'] - $sq['returqty']) * $sq['buyprice'];
        	$sq['iddetail'] = idmaker::getCurrentID2();
        	$sq['id'] = $id;
        	$sq['userlog']=Yii::app()->user->id;
        	$sq['datetimelog']=idmaker::getDateTime();
        }
        return $soldqty;
	}
      
 	private function setStatusPO($detailmodel) 
 	{	
 		$dataPO=Yii::app()->db->createCommand()
 		->select()->from('consignorders')
 		->where('id=:id', array(':id'=>$detailmodel->idpurchaseorder))
 		->queryRow();
 		$dataMemo=Yii::app()->db->createCommand()
 		->select()->from('consignmemos')
 		->where('id=:id', array(':id'=>$detailmodel->idpurchaseorder))
 		->order('id DESC')->queryRow();
 		
 		if ($dataMemo)
 			$total=$dataMemo['total']-$dataMemo['discount'];
 		else
 			$total=$dataPO['total']-$dataMemo['discount'];
 		$dataPaid=Yii::app()->db->createCommand()
 		->select('sum(b.amount) as totalpaid, b.idpurchaseorder')
 		->from('consignpayments a')
 		->join('detailconsignpayments b', 'b.id = a.id')
 		->where('b.idpurchaseorder=:idpo',
 				array(':idpo'=>$detailmodel->idpurchaseorder))
 				->group('b.idpurchaseorder')
 				->queryRow();
 		if($dataPaid){
 			$paid=$dataPaid['totalpaid'];
 		} else {
 			$paid=0;
 		}
 		if ($paid==0)
 			Action::setPaymentStatusPO($detailmodel->idpurchaseorder, '0');
 		else if ($paid<$total)
 			Action::setPaymentStatusPO($detailmodel->idpurchaseorder, '1');
 	}
 	
 	private function sumDetail(& $model, array $details, array $details2)
 	{
 		$total=0;
 		$totaldisc=0;
 		foreach ($details as $row) {
 			$total=$total+$row['amount'];
 		}
 		foreach ($details2 as $row) {
 			if ($row['checked'] == '1')
 				$total=$total - $row['total'];
 		}
 		$model->attributes=Yii::app()->session['Consignpayments'];
 		$model->total=$total;
 	}
 	
 	private function matchRetur(& $main, $post)
 	{
 		foreach($main as & $m) {
 			$found = false;
 			foreach( $post as $p ) {
 				if ($m['iddetail'] == $p) {
 					$m['checked'] = 1;
 					$found = true;
 					break;
 				}
 			}
 			if (!$found)
 				$m['checked'] = 0;
 		}
 	}
}