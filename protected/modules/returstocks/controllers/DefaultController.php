<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC50';
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
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-Append', Yii::app ()->user->id )) {
			$this->state = 'create';
			$this->trackActivity ( 'c' );
			
			$model = new Returstocks ();
			$this->afterInsert ( $model );
			
			Yii::app ()->session ['master'] = 'create';
			// as the operator enter for the first time, we load the default value to the session
			if (! isset ( Yii::app ()->session ['Returstocks'] )) {
				Yii::app ()->session ['Returstocks'] = $model->attributes;
			} else {
				// use the session to fill the model
				$model->attributes = Yii::app ()->session ['Returstocks'];
			}
			
			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation ( $model );
			
			if (isset ( $_POST )) {
				if (isset ( $_POST ['yt0'] )) {
					// The user pressed the button;
					$model->attributes = $_POST ['Returstocks'];
					
					$this->beforePost ( $model );
					$respond = true;
					if ($respond) {
						$respond = $model->save ();
						if (! $respond) {
							print_r ( $model->errors );
							throw new CHttpException ( 404, 'There is an error in master posting' );
						}
						
						if (isset ( Yii::app ()->session ['Detailreturstocks'] )) {
							$details = Yii::app ()->session ['Detailreturstocks'];
							$respond = $respond && $this->saveNewDetails ( $details );
						}
						
						if (isset ( Yii::app ()->session ['Detailreturstocks2'] )) {
							$details = Yii::app ()->session ['Detailreturstocks2'];
							$respond = $respond && $this->saveNewDetails2 ( $details );
						}
						
						if ($respond) {
							$this->afterPost ( $model );
							Yii::app ()->session->remove ( 'Returstocks' );
							Yii::app ()->session->remove ( 'Detailreturstocks' );
							Yii::app ()->session->remove ( 'Detailreturstocks2' );
							$this->redirect ( array (
									'view',
									'id' => $model->id 
							) );
						}
					} else {
						throw new CHttpException ( 404, 'Nomor Serial telah terdaftar.' );
					}
				} else if (isset ( $_POST ['command'] )) {
					// save the current master data before going to the detail page
					if ($_POST ['command'] == 'adddetail') {
						$model->attributes = $_POST ['Returstocks'];
						Yii::app ()->session ['Returstocks'] = $_POST ['Returstocks'];
						$this->redirect ( array (
								'detailreturstocks/create',
								'id' => $model->id 
						) );
					} else if ($_POST ['command'] == 'setDO') {
						
						$model->attributes = $_POST ['Returstocks'];
						Yii::app ()->session ['Returstocks'] = $_POST ['Returstocks'];
						$this->loadDO ( $model->donum, $model->id );
					}
				}
			}
			
			$this->render ( 'create', array (
					'model' => $model 
			) );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
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

             if(!isset(Yii::app()->session['Returstocks']))
                Yii::app()->session['Returstocks']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Returstocks'];

             if(!isset(Yii::app()->session['Detailreturstocks'])) 
               Yii::app()->session['Detailreturstocks']=$this->loadDetails($id);
             
             if(!isset(Yii::app()->session['Detailreturstocks2'])) {
             	Yii::app()->session['Detailreturstocks2']=$this->loadDetails2($id);
             }
             if (count(Yii::app()->session['Detailreturstocks2']) == 0) {
             	$this->loadSerialNums($model->regnum, $model->id);
             }
             	
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Returstocks'];
                     $this->beforePost($model);
                     $this->tracker->modify('returstocks', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                     	throw new CHttpException(404,'There is an error in master posting ');
                     }

                     if(isset(Yii::app()->session['Detailreturstocks'])) {
                         $details=Yii::app()->session['Detailreturstocks'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['Detailreturstocks2'])) {
                     	$details=Yii::app()->session['Detailreturstocks2'];
                     	$respond=$respond&&$this->saveDetails2($details);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail posting');
                     	}
                     };
                     
                     if(isset(Yii::app()->session['Deletedetailreturstocks'])) {
                         $deletedetails=Yii::app()->session['Deletedetailreturstocks'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if(isset(Yii::app()->session['Deletedetailreturstocks2'])) {
                     	$deletedetails=Yii::app()->session['Deletedetailreturstocks2'];
                     	$respond=$respond&&$this->deleteDetails2($deletedetails);
                     	if(!$respond) {
                     		throw new CHttpException(404,'There is an error in detail deletion');
                     	}
                     };
                    
                     if($respond) {
                         Yii::app()->session->remove('Returstocks');
                         Yii::app()->session->remove('Detailreturstocks');
                         Yii::app()->session->remove('Deletedetailreturstocks');
                         Yii::app()->session->remove('Detailreturstocks2');
                         Yii::app()->session->remove('Deletedetailreturstocks2');
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

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('returstocks', $id);

            $detailmodels=Detailreturstocks::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailreturstocks', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }
            
            $detailmodels=Detailreturstocks2::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
            	$this->tracker->init();
            	$this->tracker->delete('detailreturstocks2', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Returstocks');
               Yii::app()->session->remove('Detailreturstocks');
               Yii::app()->session->remove('DeleteDetailreturstocks');
               Yii::app()->session->remove('Detailreturstocks2');
               Yii::app()->session->remove('DeleteDetailreturstocks2');
               $dataProvider=new CActiveDataProvider('Returstocks',
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
               
			$model=new Returstocks('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Returstocks']))
				$model->attributes=$_GET['Returstocks'];

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
                $this->tracker->restore('returstocks', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Returstocks');
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
                $this->tracker->restoreDeleted('returstocks', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Returstocks');
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
	 * @return Returstocks the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Returstocks::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function loadModelRegnum($regnum)
	{
		$model=Returstocks::model()->findByAttributes(array('regnum'=>$regnum));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Returstocks $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='returstocks-form')
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
             $model=new Returstocks;
             $model->attributes=Yii::app()->session['Returstocks'];

             $details=Yii::app()->session['Detailreturstocks'];
             $this->afterInsertDetail($model, $details);

             $this->render('create',array(
                 'model'=>$model,
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
      		$model=new Returstocks;
      		$model->attributes=Yii::app()->session['Returstocks'];
      
      		$details=Yii::app()->session['Detailreturstocks2'];
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
      
      		$model=new Returstocks;
      		$model->attributes=Yii::app()->session['Returstocks'];
      
      		$details=Yii::app()->session['Detailreturstocks'];
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

             $model=new Returstocks;
             $model->attributes=Yii::app()->session['Returstocks'];

             $details=Yii::app()->session['Detailreturstocks2'];
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


             $model=new Returstocks;
             $model->attributes=Yii::app()->session['Returstocks'];

             $details=Yii::app()->session['Detailreturstocks'];
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
             $detailmodel=new Detailreturstocks;
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
     		$detailmodel=new Detailreturstocks2;
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
             $detailmodel=Detailreturstocks::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailreturstocks;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailreturstocks', array('iddetail'=>$detailmodel->iddetail));
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
     		$detailmodel=Detailreturstocks2::model()->findByPk($row['iddetail']);
     		if($detailmodel==NULL) {
     			$detailmodel=new Detailreturstocks2;
     		} else {
     			if(count(array_diff($detailmodel->attributes,$row))) {
     				$this->tracker->init();
     				$this->tracker->modify('detailreturstocks2', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailreturstocks::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d');
                 $this->tracker->delete('detailreturstocks', $detailmodel->id);
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
     		$detailmodel=Detailreturstocks2::model()->findByPk($row['iddetail']);
     		if($detailmodel) {
     			$this->tracker->init();
     			$this->trackActivity('d');
     			$this->tracker->delete('detailreturstocks2', $detailmodel->id);
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
		$sql="select * from detailreturstocks where id='$id'";
		$details=Yii::app()->db->createCommand($sql)->queryAll();

		return $details;
     }
     
     protected function loadDetails2($id)
     {
     	$sql="select * from detailreturstocks2 where id='$id'";
     	$details=Yii::app()->db->createCommand($sql)->queryAll();
     
     	return $details;
     }


     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         if ($this->state == 'create')
         	$idmaker->saveRegNum($this->formid, substr($model->regnum, 2)); 
         
         /*$this->setStatusPO($model->idpurchaseorder,
            Yii::app()->session['Detailreturstocks']);
         */
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         if ($this->state == 'create')
			$model->regnum='PR'.$idmaker->getRegNum($this->formid);
     }

     protected function    beforeDelete(& $model)
     {

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
     
      private function loadDO($donum, $id)
      {
        $details=array();

        $sql=<<<EOS
        select a.qty from detailpurchasesorders a
        join purchasesorders b on b.id = a.id
        where b.regnum = :p_regnum and a.iditem = :p_iditem
EOS;
        $mycommand=Yii::app()->db->createCommand($sql);
        
        $dataPO=Yii::app()->db->createCommand()
           ->select('count(*) as totalqty, b.iditem, a.idwarehouse, a.transid')
           ->from('detailstockentries b')
           ->join('stockentries a', 'a.id=b.id')
           ->where('a.donum = :donum and b.serialnum <> :serialnum', 
           		array(':donum'=>$donum, 'serialnum'=>'Belum Diterima') )
           ->group('b.iditem, a.idwarehouse')
           ->queryAll();
        Yii::app()->session->remove('Detailreturstocks');
         foreach($dataPO as $row) {
			$detail['iddetail']=idmaker::getCurrentID2();
			$detail['id']=$id;
			$detail['iditem']=$row['iditem'];
			$detail['qty']=$row['totalqty'];
			$detail['idwarehouse']=$row['idwarehouse'];
			$detail['idpurchaseorder']=$row['transid'];
			$mycommand->bindParam(':p_regnum', $row['transid'], PDO::PARAM_STR);
			$mycommand->bindParam(':p_iditem', $row['iditem'], PDO::PARAM_STR);
			$orderqty=$mycommand->queryScalar();	
			$detail['leftqty']=$orderqty-$row['totalqty'];
			$detail['userlog']=Yii::app()->user->id;
			$detail['datetimelog']=idmaker::getDateTime();
			
			$details[]=$detail; 
        }
        Yii::app()->session['Detailreturstocks']=$details;
      }

      private function loadSerialNums($regnum, $id) 
      {
		$stockexits = Yii::app()->db->createCommand()
			->select('b.serialnum, b.iditem, b.iddetail')
			->from('stockexits a')
			->join('detailstockexits b', 'b.id = a.id')
			->where('a.transid = :p_transid', array(":p_transid"=>$regnum))
			->queryAll();
		$details = array();
		foreach ($stockexits as $retur) {
			$detail['id'] = $id;
			$detail['iddetail'] = $retur['iddetail'];
			$detail['iditem'] = $retur['iditem'];
			$detail['serialnum'] = $retur['serialnum'];
			$detail['userlog'] = Yii::app()->user->id;
         	$detail['datetimelog']=idmaker::getDateTime();
			
         	$details[] = $detail;
		}	
      	Yii::app()->session['Detailreturstocks2'] = $details;
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
      
      
      public function actionSummary($id)
      {
      	$this->trackActivity('v');
      	$this->render('summary',array(
      			'model'=>$this->loadModel($id),
      	));
      
      }
      
      public function actionPrintsummary($id)
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {     
	      	$this->trackActivity('v');
	      	Yii::import("application.vendors.tcpdf.*");
	      	require_once('tcpdf.php');
	      	$this->render('printsummary',array(
	      			'model'=>$this->loadModel($id),
	      	));
      	} else	 {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
      }
      
	
	public function actionPrintlpb($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id)) {
			$this->trackActivity('p');
			 
			$model=$this->loadModel($id);
			$detailmodel=$this->loadDetails($id);
			$detailmodel2=$this->loadDetails2($id);
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.returstocks.components.*');
			require_once('printretur.php');
			ob_clean();
	
			execute($model, $detailmodel, $detailmodel2);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
		 
		 
		 
	}
      
}