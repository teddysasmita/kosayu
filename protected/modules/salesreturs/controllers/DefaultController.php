<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC17';
	public $tracker;
	public $state;
	private $recapdetails = array();

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
                $error = '';
                    
                $model=new Salesreturs;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Salesreturs'])) {
                   Yii::app()->session['Salesreturs']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Salesreturs'];
                }
                
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);
               

                if (isset($_POST)){
                   if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Salesreturs'];
                      
                      $this->beforePost($model);
                      
                      if ($this->checkDetailsItemQty()) {
	                      $respond=$model->save();
	                      if($respond) {
	                          $this->afterPost($model);
	                      } else {
	                          throw new CHttpException(404,'There is an error in master posting');
	                      }
	                      
	                      if(isset(Yii::app()->session['Detailsalesreturs']) ) {
	                        $details=Yii::app()->session['Detailsalesreturs'];
	                        $respond=$respond&&$this->saveNewDetails($details);
	                      } 
	                      
	                      if(isset(Yii::app()->session['Detailsalesreturs2']) ) {
	                        $details=Yii::app()->session['Detailsalesreturs2'];
	                        $respond=$respond&&$this->saveNewDetails2($details);
	                      }
	                      
	                      if($respond) {
	                         Yii::app()->session->remove('Salesreturs');
	                         Yii::app()->session->remove('Detailsalesreturs');
	                         $this->redirect(array('view','id'=>$model->id));
	                      }
                      } else {
                      	$error = 'Ada kesalahan dalam detil pengiriman';
                      }
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Salesreturs'];
                         Yii::app()->session['Salesreturs']=$_POST['Salesreturs'];
                         $this->redirect(array('detailsalesreturs/create',
                            'id'=>$model->id, 'regnum'=>$model->regnum));
                      } else if($_POST['command']=='adddetail2') {
                         $model->attributes=$_POST['Salesreturs'];
                         Yii::app()->session['Salesreturs']=$_POST['Salesreturs'];
                         $this->redirect(array('detailsalesreturs2/create',
                            'id'=>$model->id, 'regnum'=>$model->regnum ));                          
                      } else if($_POST['command']=='loadInvoice') {
						$model->attributes=$_POST['Salesreturs'];
						Yii::app()->session['Salesreturs']=$_POST['Salesreturs'];
						$this->loadInvoice($model->invnum, $model->id);
						$model->attributes=Yii::app()->session['Salesreturs'];
                      } else if ($_POST['command']=='updateDetail') {
                         $model->attributes=$_POST['Salesreturs'];
                         Yii::app()->session['Salesreturs']=$_POST['Salesreturs'];
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

             $this->state='u';
             $this->trackActivity('u');

             $model=$this->loadModel($id);
             $this->afterEdit($model);
             
             Yii::app()->session['master']='update';

             if(!isset(Yii::app()->session['Salesreturs']))
                Yii::app()->session['Salesreturs']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Salesreturs'];

             if(!isset(Yii::app()->session['Detailsalesreturs'])) 
               Yii::app()->session['Detailsalesreturs']=$this->loadDetails($id);
             
             if(!isset(Yii::app()->session['Detailsalesreturs2'])) 
               Yii::app()->session['Detailsalesreturs2']=$this->loadDetails2($id);
             
            

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Salesreturs'];
                     // Uncomment the following line if AJAX validation is needed
                     $this->performAjaxValidation($model);
                     $this->beforePost($model);
                     $this->tracker->modify('salesreturs', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailsalesreturs'])) {
                         $details=Yii::app()->session['Detailsalesreturs'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['Detailsalesreturs2'])) {
                         $details=Yii::app()->session['Detailsalesreturs2'];
                         $respond=$respond&&$this->saveDetails2($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail2 posting');
                         }
                     };

                     if(isset(Yii::app()->session['DeleteDetailsalesreturs'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailsalesreturs'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailsalesreturs2'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailsalesreturs2'];
                         $respond=$respond&&$this->deleteDetails2($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail2 deletion');
                         }
                     };

                     if($respond) {
                         Yii::app()->session->remove('Salesreturs');
                         Yii::app()->session->remove('Detailsalesreturs');
                         Yii::app()->session->remove('DeleteDetailsalesreturs');
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
            $this->tracker->delete('salesreturs', $id);

            $detailmodels=Detailsalesreturs::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailsalesreturs', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }

            $detailmodels=Detailsalesreturs2::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->delete('detailsalesreturs', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Salesreturs');
               Yii::app()->session->remove('Detailsalesreturs');
               Yii::app()->session->remove('DeleteDetailsalesreturs');
               $dataProvider=new CActiveDataProvider('Salesreturs',
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
               
                $model=new Salesreturs('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Salesreturs']))
			$model->attributes=$_GET['Salesreturs'];

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
                $this->tracker->restore('salesreturs', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Salesreturs');
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
                $this->tracker->restoreDeleted('salesreturs', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Salesreturs');
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
	 * @return Salesreturs the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Salesreturs::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Salesreturs $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='salesreturs-form')
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
             $model=new Salesreturs;
             $model->attributes=Yii::app()->session['Salesreturs'];

             $details=Yii::app()->session['Detailsalesreturs'];
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
             $model=new Salesreturs;
             $model->attributes=Yii::app()->session['Salesreturs'];

             $details=Yii::app()->session['Detailsalesreturs2'];
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

             $model=new Salesreturs;
             $model->attributes=Yii::app()->session['Salesreturs'];

             $details=Yii::app()->session['Detailsalesreturs'];
             $this->afterUpdateDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
      public function actionUpdateDetail2()
      {
         if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                 Yii::app()->user->id))  {

             $model=new Salesreturs;
             $model->attributes=Yii::app()->session['Salesreturs'];

             $details=Yii::app()->session['Detailsalesreturs2'];
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


             $model=new Salesreturs;
             $model->attributes=Yii::app()->session['Salesreturs'];

             $details=Yii::app()->session['Detailsalesreturs'];
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


             $model=new Salesreturs;
             $model->attributes=Yii::app()->session['Salesreturs'];

             $details=Yii::app()->session['Detailsalesreturs2'];
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
             $detailmodel=new Detailsalesreturs;
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
             $detailmodel=new Detailsalesreturs2;
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
                $detailmodel=Detailsalesreturs::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detailsalesreturs;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailsalesreturs', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailsalesreturs2::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                  die("cannot find data");  
                  //$detailmodel=new Detailsalesreturs2;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailsalesreturs2', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailsalesreturs::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->__DETAILFORMID);
                    $this->tracker->delete('detailsalesreturs', $detailmodel->id);
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
                $detailmodel=Detailsalesreturs2::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->__DETAILFORMID);
                    $this->tracker->delete('detailsalesreturs2', $detailmodel->id);
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
         $sql="select * from detailsalesreturs where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();

         return $details;
        }
        
        protected function loadDetails2($id)
        {
         $sql="select * from detailsalesreturs2 where id='$id'";
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
            $idmaker->saveRegNum($this->formid, substr($model->regnum, 2));    
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            $model->regnum='SJ'.$idmaker->getRegNum($this->formid);
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
        	$model->attributes=Yii::app()->session['Salesreturs'];
        	$model->total=$total;
        	$model->discount=$totaldisc;
        }
        
        private function loadInvoice($invnum, $id)
        {
        	$master=Yii::app()->db->createCommand()
        		->select()->from('salespos')->where('regnum = :p_regnum', 
        		array(':p_regnum'=>$invnum))->queryRow(); 
        	
        	$masterdata=Yii::app()->session['Salesreturs'];
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
        	Yii::app()->session['Salesreturs']=$masterdata;
        	$details=Yii::app()->db->createCommand()
        		->select('b.*')->from('deliveryorders a')->join('detaildeliveryorders b', 'b.id = a.id')
        		->where('a.invnum = :p_invnum',
				array(':p_invnum'=>$invnum))->queryAll();
        	$detailsdone=Yii::app()->db->createCommand()
        		->select('b.*')->from('salesreturs a')->join('detailsalesreturs b', 'b.id = a.id')
        		->where('a.regnum = :p_regnum',
        			array(':p_regnum'=>$invnum))->queryAll();
        	foreach($details as $detail ) {
        		$detaildata['leftqty']=$detail['qty'];
        		foreach($detailsdone as $detaildone) {
        			if ($detaildone['iditem']=$detail['iditem']) {
        				$detaildata['leftqty']=$detaildata['leftqty']-$detaildone['qty'];		
        			}
        		}
        		
        		$detaildata2['id']=$id;
        		$detaildata2['iddetail']=idmaker::getCurrentID2();
        		$detaildata2['iditem']=$detail['iditem'];
        		$detaildata2['leftqty']=$detaildata['leftqty'];
        		$detaildata2['qty']=$detaildata['leftqty'];
        		$detaildata2['idwarehouse']='-';
        		$detaildata2['userlog']=Yii::app()->user->id;
        		$detaildata2['datetimelog']=idmaker::getDateTime();
        		$detailsdata2[]=$detaildata2;
        	} 
        	Yii::app()->session['Detailsalesreturs'] = $detailsdata2;
        }
    	
        private function addRecapItem($iditem, $qty) 
        {
        	foreach ($this->recapdetails as $recap ) {
        		if ($recap['iditem'] == $iditem) {
        			$recap['qty'] += $qty;
        			return;
        		}
        	}
        	$temp['iditem'] = $iditem;
        	$temp['qty'] = $qty;
        	$this->recapdetails[] = $temp;	
        }
               
        private function checkDetailsItemQty()
        {
			$details2 = Yii::app()->session['Detailsalesreturs2'];
			$details1 = Yii::app()->session['Detailsalesreturs'];
			
			foreach ($details1 as $deliverydata) {
				$this->addRecapItem($deliverydata['iditem'], $deliverydata['qty']);
        	}
        	
        	foreach ($this->recapdetails as $deliverydata) {
        		$found = FALSE;
        		foreach($details2 as $data) {
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
        		Yii::import('application.modules.salesreturs.components.*');
        		require_once('printsj.php');
        		ob_clean();
        		
        		execute($model, $detailmodel, $receivable);
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }    
	
}
