<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC19';
	public $tracker;
	public $state;
	private $recapdetails = array();
	private $invdetails = array();
	private $detailid = 'AC19a';
	private $detailid2 = 'AC19b';
	

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

	public function actionViewRegnum($regnum)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('view',array(
					'model'=>$this->loadModelRegnum($regnum),
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
                    
                $model=new Orderretrievals;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Orderretrievals'])) {
                   Yii::app()->session['Orderretrievals']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Orderretrievals'];
                }
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);
               

                if (isset($_POST)){
                   if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Orderretrievals'];
                      
                      $this->beforePost($model);
                      
                      if ($this->checkDetailsItemQty()) {
	                      $respond=$model->save();
	                      if($respond) {
	                          $this->afterPost($model);
	                      } else {
	                          throw new CHttpException(404,'There is an error in master posting');
	                      }
	                      
	                      if(isset(Yii::app()->session['Detailorderretrievals']) ) {
	                        $details=Yii::app()->session['Detailorderretrievals'];
	                        $respond=$respond&&$this->saveNewDetails($details);
	                      } 
	                      
	                      if(isset(Yii::app()->session['Detailorderretrievals2']) ) {
	                        $details=Yii::app()->session['Detailorderretrievals2'];
	                        $respond=$respond&&$this->saveNewDetails2($details);
	                      }
	                      
	                      if($respond) {
	                         Yii::app()->session->remove('Orderretrievals');
	                         Yii::app()->session->remove('Detailorderretrievals');
	                         $this->redirect(array('view','id'=>$model->id));
	                      }
                      } else {
                      	$error = 'Ada kesalahan dalam detil pengiriman';
                      }
                   } else if (isset($_POST['command'])){
                   	
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Orderretrievals'];
                         Yii::app()->session['Orderretrievals']=$_POST['Orderretrievals'];
                         $this->redirect(array('detailorderretrievals/create',
                            'id'=>$model->id, 'regnum'=>$model->regnum));
                      } else if($_POST['command']=='adddetail2') {
                         $model->attributes=$_POST['Orderretrievals'];
                         Yii::app()->session['Orderretrievals']=$_POST['Orderretrievals'];
                         $this->redirect(array('detailorderretrievals2/create',
                            'id'=>$model->id, 'regnum'=>$model->regnum ));                          
                      } else if($_POST['command']=='loadInvoice') {
						$model->attributes=$_POST['Orderretrievals'];
						Yii::app()->session['Orderretrievals']=$_POST['Orderretrievals'];
						$this->loadInvoice($model->invnum, $model->id);
						$model->attributes=Yii::app()->session['Orderretrievals'];
                      } else if ($_POST['command']=='updateDetail') {
                         $model->attributes=$_POST['Orderretrievals'];
                         Yii::app()->session['Orderretrievals']=$_POST['Orderretrievals'];
                         print_r($_POST);
                   		die();
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

             if(!isset(Yii::app()->session['Orderretrievals']))
                Yii::app()->session['Orderretrievals']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Orderretrievals'];

             if(!isset(Yii::app()->session['Detailorderretrievals'])) 
               Yii::app()->session['Detailorderretrievals']=$this->loadDetails($id);
             
             if(!isset(Yii::app()->session['Detailorderretrievals2'])) 
               Yii::app()->session['Detailorderretrievals2']=$this->loadDetails2($id);
             

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Orderretrievals'];
                     // Uncomment the following line if AJAX validation is needed
                     $this->performAjaxValidation($model);
                     $this->beforePost($model);
                     $this->tracker->modify('orderretrievals', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailorderretrievals'])) {
                         $details=Yii::app()->session['Detailorderretrievals'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['Detailorderretrievals2'])) {
                         $details=Yii::app()->session['Detailorderretrievals2'];
                         $respond=$respond&&$this->saveDetails2($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail2 posting');
                         }
                     };

                     if(isset(Yii::app()->session['Deletedetailorderretrievals'])) {
                         $deletedetails=Yii::app()->session['Deletedetailorderretrievals'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if(isset(Yii::app()->session['Deletedetailorderretrievals2'])) {
                         $deletedetails=Yii::app()->session['Deletedetailorderretrievals2'];
                         $respond=$respond&&$this->deleteDetails2($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail2 deletion');
                         }
                     };

                     if($respond) {
                         Yii::app()->session->remove('Orderretrievals');
                         Yii::app()->session->remove('Detailorderretrievals');
                         Yii::app()->session->remove('DeleteDetailorderretrievals');
                         $this->redirect(array('view','id'=>$model->id));
                     }
                 }
             }

             $this->render('update',array(
                     'model'=>$model, 'form_error'=>''
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
            $this->tracker->delete('orderretrievals', $id);

            $detailmodels=Detailorderretrievals::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailorderretrievals', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }

            $detailmodels=Detailorderretrievals2::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->delete('detailorderretrievals2', array('iddetail'=>$dm->iddetail));
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
              	
               Yii::app()->session->remove('Orderretrievals');
               Yii::app()->session->remove('Detailorderretrievals');
               Yii::app()->session->remove('Detailorderretrievals2');
               Yii::app()->session->remove('DeleteDetailorderretrievals');
               Yii::app()->session->remove('DeleteDetailorderretrievals2');
               $dataProvider=new CActiveDataProvider('Orderretrievals',
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
               
                $model=new Orderretrievals('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Orderretrievals']))
			$model->attributes=$_GET['Orderretrievals'];

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
                $this->tracker->restore('orderretrievals', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Orderretrievals');
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
                $this->tracker->restoreDeleted('orderretrievals', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Orderretrievals');
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
	 * @return Orderretrievals the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Orderretrievals::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function loadModelRegnum($regnum)
	{
		$model=Orderretrievals::model()->findByAttributes(array('regnum'=>$regnum));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Orderretrievals $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='orderretrievals-form')
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
             $model=new Orderretrievals;
             $model->attributes=Yii::app()->session['Orderretrievals'];

             $details=Yii::app()->session['Detailorderretrievals'];
             $this->afterInsertDetail($model, $details);

             $this->render('create',array(
                 'model'=>$model, 'form_error'=>''
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         } 
      }
      
      public function actionCreateDetail2()
      {
      //this action continues the process from the detail page
         if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                 Yii::app()->user->id))  {
             $model=new Orderretrievals;
             $model->attributes=Yii::app()->session['Orderretrievals'];

             $details=Yii::app()->session['Detailorderretrievals2'];
             $this->afterInsertDetail2($model, $details);

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

             $model=new Orderretrievals;
             $model->attributes=Yii::app()->session['Orderretrievals'];

             $details=Yii::app()->session['Detailorderretrievals'];
             $this->afterUpdateDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model, 'form_error'=>''
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
      public function actionUpdateDetail2()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {

             $model=new Orderretrievals;
             $model->attributes=Yii::app()->session['Orderretrievals'];

             $details=Yii::app()->session['Detailorderretrievals2'];
             $this->afterUpdateDetail2($model, $details);

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


             $model=new Orderretrievals;
             $model->attributes=Yii::app()->session['Orderretrievals'];

             $details=Yii::app()->session['Detailorderretrievals'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
      public function actionDeleteDetail2()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {


             $model=new Orderretrievals;
             $model->attributes=Yii::app()->session['Orderretrievals'];

             $details=Yii::app()->session['Detailorderretrievals2'];
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
             $detailmodel=new Detailorderretrievals;
             $detailmodel->attributes=$row;
             $respond=$detailmodel->insert();
             if (!$respond) {
                break;
             }
         }
         return $respond;
     }
     
     protected function saveNewDetails2(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailorderretrievals2;
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
                $detailmodel=Detailorderretrievals::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detailorderretrievals;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailorderretrievals', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailorderretrievals2::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                  die("cannot find data");  
                  //$detailmodel=new Detailorderretrievals2;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailorderretrievals2', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailorderretrievals::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->detailid);
                    $this->tracker->delete('detailorderretrievals', $detailmodel->iddetail);
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
                $detailmodel=Detailorderretrievals2::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->detailid2);
                    $this->tracker->delete('detailorderretrievals2', $detailmodel->iddetail);
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
         $sql="select * from detailorderretrievals where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();

         return $details;
        }
        
        protected function loadDetails2($id)
        {
         $sql="select * from detailorderretrievals2 where id='$id'";
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
            	$model->regnum='PB'.$idmaker->getRegNum($this->formid);
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
        
        protected function afterInsertDetail2(& $model, $details)
        {
        }
        

        protected function afterUpdateDetail(& $model, $details)
        {
        	//$this->sumDetail($model, $details);
        }
        
        protected function afterUpdateDetail2(& $model, $details)
        {
        }
        
        protected function afterDeleteDetail(& $model, $details)
        {
        	$this->sumDetail($model, $details);
        }
        
        protected function afterDeleteDetail2(& $model, $details)
        {
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
        	$model->attributes=Yii::app()->session['Orderretrievals'];
        	$model->total=$total;
        	$model->discount=$totaldisc;
        }
        
        private function loadInvoice($invnum, $id)
        {
        	$ganti = substr($invnum, 0, 1) == 'G';
        	if ($ganti === true) {
        		$tempnum = substr($invnum, 1);
        		$tempnum = str_pad($tempnum, 12, '0', STR_PAD_LEFT);
        		$master=Yii::app()->db->createCommand()
        		->select()->from('salesreplace2')->where('regnum = :p_regnum',
        				array(':p_regnum'=>$tempnum))->queryRow();
        	} else {
        		$master=Yii::app()->db->createCommand()
        		->select()->from('salespos')->where('regnum = :p_regnum',
        				array(':p_regnum'=>$invnum))->queryRow();
        	}
        	
        	$masterdata=Yii::app()->session['Orderretrievals'];
        	if ($master['idreceiver'] <> '') {
        		$receiver=Yii::app()->db->createCommand()
        			->select()->from('salesreceivers')
        			->where('id = :p_id', array(':p_id'=>$master['idreceiver']))
        			->queryRow();
        		if ($receiver !== FALSE) {
        			$masterdata['receivername']=$receiver['name'];
        			$masterdata['receiveraddress']=$receiver['address'];
        			$masterdata['receiverphone']=$receiver['phone'];
        		} else {
        			$masterdata['receivername']=$master['payer_name'];
        			$masterdata['receiveraddress']=$master['payer_address'];
        			$masterdata['receiverphone']=$master['payer_phone'];
        		}
        	} else {
        		$masterdata['receivername']=$master['payer_name'];
        		$masterdata['receiveraddress']=$master['payer_address'];
        		$masterdata['receiverphone']=$master['payer_phone'];
        	}
        	Yii::app()->session['Orderretrievals']=$masterdata;
        	$details=Yii::app()->db->createCommand()
        		->select('b.*')->from('salespos a')->join('detailsalespos b', 'b.id = a.id')
        		->where('a.regnum = :p_regnum',
				array(':p_regnum'=>$invnum))->queryAll();
        	$detailsdone=Yii::app()->db->createCommand()
        		->select('b.iditem, sum(b.qty) as sentqty')->from('orderretrievals a')
        		->join('detailorderretrievals b', 'b.id = a.id')
        		->where('a.invnum = :p_regnum',
        			array(':p_regnum'=>$invnum))
        		->group('b.iditem')->queryAll();
			$detailsdone2=Yii::app()->db->createCommand()
        		->select('b.iditem, sum(b.qty) as sentqty')->from('deliveryorders a')
        		->join('detaildeliveryorders b', 'b.id = a.id')
        		->where('a.invnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        				->group('b.iditem')
        		->queryAll();
        	foreach($details as $detail ) {
        		$detaildata['id']=$id;
        		$detaildata['iddetail']=idmaker::getCurrentID2();
        		$detaildata['iditem']=$detail['iditem'];
        		$detaildata['invqty']=$detail['qty'];
        		$detaildata['qty']=0;
        		$detaildata['leftqty']=$detail['qty'];
        		$detaildata['userlog']=Yii::app()->user->id;
				$detaildata['datetimelog']=idmaker::getDateTime();
        		$doneqty = 0;
        		foreach($detailsdone as $detaildone) {
        			if ($detaildone['iditem']==$detail['iditem']) {
        				$doneqty = $detaildone['sentqty'];
        			}
        		}
        		foreach($detailsdone2 as $detaildone2) {
        			if ($detaildone2['iditem']==$detail['iditem']) {
        				$doneqty += $detaildone2['sentqty'];
        			}
        		}
        		$detaildata['leftqty']=$detaildata['leftqty']-$doneqty;
        		$detailsdata[]=$detaildata;
        		
        		$detaildata2['id']=$id;
        		$detaildata2['iddetail']=idmaker::getCurrentID2();
        		$detaildata2['iditem']=$detail['iditem'];
        		$detaildata2['qty']=$detaildata['leftqty'];
        		$detaildata2['idwarehouse']='-';
        		$detaildata2['userlog']=Yii::app()->user->id;
        		$detaildata2['datetimelog']=idmaker::getDateTime();
        		$detailsdata2[]=$detaildata2;
        	} 
        	Yii::app()->session['Detailorderretrievals2'] = $detailsdata;
        	Yii::app()->session['Detailorderretrievals'] = $detailsdata2;
        }
    	
        private function addRecapItem($iditem, $qty) 
        {
        	foreach ($this->recapdetails as &$recap ) {
        		if ($recap['iditem'] == $iditem) {
        			$recap['qty'] += $qty;
        			return;
        		}
        	}
        	$temp['iditem'] = $iditem;
        	$temp['qty'] = $qty;
        	$this->recapdetails[] = $temp;	
        }
        
        private function addInvItem($iditem, $leftqty)
        {
        	foreach ($this->invdetails as &$inv ) {
        		if ($inv['iditem'] == $iditem) {
        			$inv['leftqty'] += $leftqty;
        
        			return;
        		}
        	}
        	$temp['iditem'] = $iditem;
        	$temp['leftqty'] = $leftqty;
        	$this->invdetails[] = $temp;
        }
               
        private function checkDetailsItemQty()
        {
			$details2 = Yii::app()->session['Detailorderretrievals2'];
			$details1 = Yii::app()->session['Detailorderretrievals'];
			
			foreach ($details1 as $deliverydata) {
				$this->addRecapItem($deliverydata['iditem'], $deliverydata['qty']);
        	}
        	
        	foreach ($details2 as $invdata) {
        		$this->addInvItem($invdata['iditem'], $invdata['leftqty']);
        	}
        	
        	foreach ($this->recapdetails as $deliverydata) {
        		$found = FALSE;
        		foreach($this->invdetails as $data) {
        			if ($data['iditem'] == $deliverydata['iditem']) {
        				$found = TRUE;
        				if ($data['leftqty'] < $deliverydata['qty'])
        					return FALSE;
        			}
        		}
        		if (! $found) {
        			return FALSE;
        		}
        	}
        	return TRUE;
        }
	
		public function actionPrintpb($id)
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
        		Yii::import('application.modules.orderretrievals.components.*');
        		require_once('printpb.php');
        		ob_clean();
        		
        		execute($model, $detailmodel, $receivable);
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }    
	
}
