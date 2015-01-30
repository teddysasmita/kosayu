<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC32';
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
                    
                $model=new Purchasespayments;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Purchasespayments'])) {
                   Yii::app()->session['Purchasespayments']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Purchasespayments'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                	if(isset($_POST['yt1'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Purchasespayments'];
                      
                      $this->beforePost($model);
                      $respond=$model->save();
                      if(!$respond) {
                          throw new CHttpException(404,'There is an error in master posting');
                      }

                      if(isset(Yii::app()->session['Detailpurchasespayments']) ) {
                        $details=Yii::app()->session['Detailpurchasespayments'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } 
                      if(!$respond) {
                      	throw new CHttpException(404,'There is an error in detail posting');
                      }	
                      
                      if(isset(Yii::app()->session['Detailpurchasespayments2']) ) {
                      	$details2=Yii::app()->session['Detailpurchasespayments2'];
                      	$respond=$respond&&$this->saveNewDetails2($details2);
                      }
                      if(!$respond) {
                      	throw new CHttpException(404,'There is an error in detail2 posting');
                      }
                      
                      if(isset(Yii::app()->session['Detailpurchasespayments3']) ) {
                      	$details3=Yii::app()->session['Detailpurchasespayments3'];
                      	$respond=$respond&&$this->saveNewDetails3($details3);
                      }
                      if(!$respond) {
                      	throw new CHttpException(404,'There is an error in detail2 posting');
                      }
                      
                      if($respond) {
                         $this->afterPost($model);
                         Yii::app()->session->remove('Purchasespayments');
                         Yii::app()->session->remove('Detailpurchasespayments');
                         Yii::app()->session->remove('Detailpurchasespayments2');
                         Yii::app()->session->remove('Detailpurchasespayments3');
                         $this->redirect(array('view','id'=>$model->id));
                      } 
                           
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Purchasespayments'];
                         Yii::app()->session['Purchasespayments']=$_POST['Purchasespayments'];
                         //$this->redirect(array('detailpurchasespayments/create',
                            //'id'=>$model->id));
                      } else if ($_POST['command']=='setSupplier') {
                         $model->attributes=$_POST['Purchasespayments'];
                         $details = $this->loadPurchases($model->idsupplier, $model->id);
                         Yii::app()->session['Detailpurchasespayments'] = $details;
                        $details2 = $this->loadReturs($model->idsupplier, $model->id); 
						Yii::app()->session['Detailpurchasespayments2'] = $details2;
                        Yii::app()->session['Purchasespayments']=$model->attributes;	 
                      } else if($_POST['command']=='adddetail2') {
                         $model->attributes=$_POST['Purchasespayments'];
                         Yii::app()->session['Purchasespayments']=$_POST['Purchasespayments'];
                         $details2 = Yii::app()->session['Detailpurchasespayments2'];
                         $details = Yii::app()->session['Detailpurchasespayments'];
                         $this->matchRetur($details2, $_POST['yw2_c2']);
                         Yii::app()->session['Detailpurchasespayments2'] = $details2;
                         $this->sumDetail($model, $details, $details2);
                         //$this->redirect(array('detailpurchasespayments/create',
                            //'id'=>$model->id));
                      } else if ($_POST['command']=='addpayment') {
                         $model->attributes=$_POST['Purchasespayments'];
                         Yii::app()->session['Purchasespayments']=$_POST['Purchasespayments'];
                         //$this->redirect(array('detailpurchasespayments/create',
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

             if(!isset(Yii::app()->session['Purchasespayments']))
                Yii::app()->session['Purchasespayments']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Purchasespayments'];

             if(!isset(Yii::app()->session['Detailpurchasespayments'])) 
               Yii::app()->session['Detailpurchasespayments']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt1'])) {
                     $model->attributes=$_POST['Purchasespayments'];
                     $this->beforePost($model);
                     $this->tracker->modify('purchasespayments', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailpurchasespayments'])) {
                         $details=Yii::app()->session['Detailpurchasespayments'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailpurchasespayments'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailpurchasespayments'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if(isset(Yii::app()->session['Detailpurchasespayments2'])) {
                     	$details=Yii::app()->session['Detailpurchasespayments2'];
                     	$respond=$respond&&$this->saveDetails2($details);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail2 posting');
                     	}
                     };
                      
                     /*if(isset(Yii::app()->session['DeleteDetailpurchasespayments2'])) {
                     	$deletedetails=Yii::app()->session['DeleteDetailpurchasespayments2'];
                     	$respond=$respond&&$this->deleteDetails($deletedetails);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail2 deletion');
                     	}
                     };*/
                     
                     if(isset(Yii::app()->session['Detailpurchasespayments3'])) {
                     	$details=Yii::app()->session['Detailpurchasespayments3'];
                     	$respond=$respond&&$this->saveDetails($details);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail3 posting');
                     	}
                     };
                      
                     if(isset(Yii::app()->session['DeleteDetailpurchasespayments3'])) {
                     	$deletedetails=Yii::app()->session['DeleteDetailpurchasespayments3'];
                     	$respond=$respond&&$this->deleteDetails($deletedetails);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail3 deletion');
                     	}
                     };
                    
                     if($respond) {
                     	$this->afterPost($model);
                         Yii::app()->session->remove('Purchasespayments');
                         Yii::app()->session->remove('Detailpurchasespayments');
                         Yii::app()->session->remove('Detailpurchasespayments2');
                         Yii::app()->session->remove('Detailpurchasespayments3');
                         Yii::app()->session->remove('DeleteDetailpurchasespayments');
                         Yii::app()->session->remove('DeleteDetailpurchasespayments2');
                         Yii::app()->session->remove('DeleteDetailpurchasespayments3');
                         $this->redirect(array('view','id'=>$model->id));
                     }
                 } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Purchasespayments'];
                         Yii::app()->session['Purchasespayments']=$_POST['Purchasespayments'];
                         //$this->redirect(array('detailpurchasespayments/create',
                            //'id'=>$model->id));
                      } else if ($_POST['command']=='setSupplier') {
                         $model->attributes=$_POST['Purchasespayments'];
                         Yii::app()->session['Purchasespayments']=$_POST['Purchasespayments'];
                         Yii::app()->session['Detailpurchasespayments'] = 
                         	$this->loadPurchases($model->idsupplier, $model->id);
                         Yii::app()->session['Detailpurchasespayments2'] =
                         	$this->loadReturs($model->idsupplier, $model->id);
                      } else if($_POST['command']=='adddetail2') {
                         $model->attributes=$_POST['Purchasespayments'];
                         Yii::app()->session['Purchasespayments']=$_POST['Purchasespayments'];
                         $details2 = Yii::app()->session['Detailpurchasespayments2'];
                         $details = Yii::app()->session['Detailpurchasespayments'];
                         $this->matchRetur($details2, $_POST['yw2_c2']);
                         Yii::app()->session['Detailpurchasespayments2'] = $details2;
                         $this->sumDetail($model, $details, $details2);
                         //$this->redirect(array('detailpurchasespayments/create',
                            //'id'=>$model->id));
                      } else if ($_POST['command']=='addpayment') {
                         $model->attributes=$_POST['Purchasespayments'];
                         Yii::app()->session['Purchasespayments']=$_POST['Purchasespayments'];
                         //$this->redirect(array('detailpurchasespayments/create',
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
            $this->tracker->delete('purchasespayments', $id);

            $detailmodels=Detailpurchasespayments::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailpurchasespayments', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }
            
            $detailmodels=Detailpurchasespayments2::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
            	$this->tracker->init();
            	$this->tracker->delete('detailpurchasespayments2', array('iddetail'=>$dm->iddetail));
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

         Yii::app()->session->remove('Purchasespayments');
         Yii::app()->session->remove('Detailpurchasespayments');
         Yii::app()->session->remove('Detailpurchasespayments2');
         Yii::app()->session->remove('Detailpurchasespayments3');
         Yii::app()->session->remove('DeleteDetailpurchasespayments');
         $dataProvider=new CActiveDataProvider('Purchasespayments',
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
         $model=new Purchasespayments('search');
         $model->unsetAttributes();  // clear any default values
         if(isset($_GET['Purchasespayments'])){
            $model->attributes=$_GET['Purchasespayments'];
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
                $this->tracker->restore('purchasespayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasespayments');
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
                $this->tracker->restoreDeleted('purchasespayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasespayments');
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
	 * @return Purchasespayments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Purchasespayments::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Purchasespayments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purchasespayments-form')
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
             $model=new Purchasespayments;
             $model->attributes=Yii::app()->session['Purchasespayments'];

             $details=Yii::app()->session['Detailpurchasespayments'];
             $details2 = Yii::app()->session['Detailpurchasespayments2'];
            
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

             $model=new Purchasespayments;
             $model->attributes=Yii::app()->session['Purchasespayments'];

             $details =Yii::app()->session['Detailpurchasespayments'];
             $details2 = Yii::app()->session['Detailpurchasespayments2'];
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


             $model=new Purchasespayments;
             $model->attributes=Yii::app()->session['Purchasespayments'];

             $details=Yii::app()->session['Detailpurchasespayments'];
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
				$detailmodel=new Detailpurchasespayments;
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
     	$respond = true;
     	foreach ($details as $row) {
     		if ($row['checked'] == 1) {
     			$detailmodel=new Detailpurchasespayments2;
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
     	$respond = true;
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
             $detailmodel=Detailpurchasespayments::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailpurchasespayments;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailpurchasespayments', array('iddetail'=>$detailmodel->iddetail));
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
     		$detailmodel=Detailpurchasespayments2::model()->findByPk($row['iddetail']);
     		if($detailmodel==NULL) {
     			$detailmodel=new Detailpurchasespayments2;
     		} else {
     			if(count(array_diff($detailmodel->attributes,$row))) {
     				$this->tracker->init();
     				$this->tracker->modify('detailpurchasespayments2', array('iddetail'=>$detailmodel->iddetail));
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
			$detailmodel=Detailpurchasespayments::model()->findByPk($row['iddetail']);
			if($detailmodel) {
				$this->tracker->init();
				$this->trackActivity('d', $this->__DETAILFORMID);
				$this->tracker->delete('detailpurchasespayments', $detailmodel->id);
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
			$detailmodel=Detailpurchasespayments2::model()->findByPk($row['iddetail']);
			if($detailmodel) {
				$this->tracker->init();
     			$this->trackActivity('d', $this->__DETAILFORMID);
     			$this->tracker->delete('detailpurchasespayments2', $detailmodel->id);
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
		$sql="select * from detailpurchasespayments where id='$id'";
		$details=Yii::app()->db->createCommand($sql)->queryAll();

		return $details;
	}

	protected function loadDetails2($id)
	{
		$sql="select * from detailpurchasespayments2 where id='$id'";
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
         $model->discount=0;
         $model->labelcost=0;
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
     
	private function loadPurchases($idsupplier, $id)
    {
		$details=array();

        $dataPO=Yii::app()->db->createCommand()
           ->select()
           ->from('purchases')
           ->where('idsupplier=:idsupplier and paystatus <> :paystatus', 
           		array(':idsupplier'=>$idsupplier, ':paystatus'=>'2'))
           ->queryAll();
        $dataPO2=Yii::app()->db->createCommand()
        	->select('a.id, sum(b.qty) as totalqty')
        	->from('purchases a')
        	->join('detailpurchases b', 'b.id = a.id')
        	->group('a.id')
        	->where('a.idsupplier = :p_idsupplier and a.paystatus <> :p_paystatus',
        		array(':p_idsupplier'=>$idsupplier, ':p_paystatus'=>'2'))
        	->queryAll();
        
        Yii::app()->session->remove('Detailpurchasespayments');
        foreach($dataPO as $rowPO) {
        	//----------------------------
        	//finding payments
        	$dataPaid=Yii::app()->db->createCommand()
	        	->select('sum(b.amount) as totalpaid, b.idpurchase')
	        	->from('purchasespayments a')
	        	->join('detailpurchasespayments b', 'b.id = a.id')
	        	->where('b.idpurchase=:idpo',
	        			array(':idpo'=>$rowPO['id']))
	        	->queryRow();
        	
        	if(is_null($dataPaid['totalpaid'])){
        		$paid=0;
        	} else {
        		$paid=$dataPaid['totalpaid'];
        	}
        	//----------------------------
        	
        	$detail['iddetail']=idmaker::getCurrentID2();
        	$detail['id']=$id;
        	$detail['userlog']=Yii::app()->user->id;
        	$detail['datetimelog']=idmaker::getDateTime();
        	$detail['idpurchase']=$rowPO['id'];
        	$detail['discount']=$rowPO['discount'];
        	$detail['paid']=$paid;
        	$detail['amount']=0;
        	
        	foreach($dataPO2 as $d2) {
        		if ($d2['id'] == $rowPO['id']) {
        			$detail['labelcost'] = $d2['totalqty'] * idmaker::getInformation('labelcost');
        			break;
        		}
        	}
        	
        	$detail['total']=$rowPO['total'];
        	
        	$details[]=$detail;
        }
        return $details;
	}
	
	private function loadReturs($idsupplier, $id)
	{
		$details=array();
		
		$dataPO=Yii::app()->db->createCommand()
			->select("a.*, sum(b.qty) as itemqty")
			->from('purchasesreturs a')
			->join('detailpurchasesreturs b', 'b.id = a.id')
			->where('a.idsupplier=:p_idsupplier and a.status=:p_status',
				array(':p_idsupplier'=>$idsupplier, ':p_status'=>'0'))
			->order('a.id')
			->queryAll();
		Yii::app()->session->remove('Detailpurchasespayments2');
		foreach($dataPO as $rowPO) { 
			$detail['iddetail']=idmaker::getCurrentID2();
			$detail['id']=$id;
			$detail['userlog']=Yii::app()->user->id;
			$detail['datetimelog']=idmaker::getDateTime();
			$detail['idpurchaseretur']=$rowPO['id'];
			$detail['total']=$rowPO['total'];
			$detail['qty']=$rowPO['itemqty'];
			$detail['checked']=0;
			$details[]=$detail;
		}
		return $details;
	}
      
 	private function setStatusPO($detailmodel) 
 	{	
 		$dataPO=Yii::app()->db->createCommand()
 		->select()->from('purchasesorders')
 		->where('id=:id', array(':id'=>$detailmodel->idpurchaseorder))
 		->queryRow();
 		$dataMemo=Yii::app()->db->createCommand()
 		->select()->from('purchasesmemos')
 		->where('id=:id', array(':id'=>$detailmodel->idpurchaseorder))
 		->order('id DESC')->queryRow();
 		
 		if ($dataMemo)
 			$total=$dataMemo['total']-$dataMemo['discount'];
 		else
 			$total=$dataPO['total']-$dataMemo['discount'];
 		$dataPaid=Yii::app()->db->createCommand()
 		->select('sum(b.amount) as totalpaid, b.idpurchaseorder')
 		->from('purchasespayments a')
 		->join('detailpurchasespayments b', 'b.id = a.id')
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
 		$labelcost=0;
 		foreach ($details as $row) {
 			if ($row['amount'] > 0 ) {
 				$total=$total+$row['amount'];
 				$labelcost += $row['labelcost'];
 			};
 		}
 		foreach ($details2 as $row) {
 			if ($row['checked'] == 1) {
 				$total=$total - $row['total'];
 				$labelcost -= $row['qty'] * idmaker::getInformation('labelcost');
 			};
 		}
 		$model->attributes=Yii::app()->session['Purchasespayments'];
 		$model->total=$total;
 		if ($model->labelcost < 0) 
 			$model->labelcost = 0;
 		$model->labelcost = $labelcost;
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