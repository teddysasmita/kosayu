<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC12';
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
                $this->state='c';
                $this->trackActivity('c');    
                    
                $model=new Purchasesstockentries;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Purchasesstockentries'])) {
                   Yii::app()->session['Purchasesstockentries']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Purchasesstockentries'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                   if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Purchasesstockentries'];
                      
                      
                      $this->beforePost($model);
                      $respond=true;
                      if ($respond) {
                         $respond=$model->save();
                         if(!$respond) {
                         	print_r($model->errors);
                             throw new CHttpException(404,'There is an error in master posting');
                         }

                         if(isset(Yii::app()->session['Detailpurchasesstockentries']) ) {
                           $details=Yii::app()->session['Detailpurchasesstockentries'];
                           $respond=$respond&&$this->saveNewDetails($details);
                         } 

                         if($respond) {
                            $this->afterPost($model);
                            Yii::app()->session->remove('Purchasesstockentries');
                            Yii::app()->session->remove('Detailpurchasesstockentries');
                            $this->redirect(array('view','id'=>$model->id));
                         } 
                         
                      } else {
                        throw new CHttpException(404,'Nomor Serial telah terdaftar.');
                     }     
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Purchasesstockentries'];
                         Yii::app()->session['Purchasesstockentries']=$_POST['Purchasesstockentries'];
                         $this->redirect(array('detailpurchasesstockentries/create',
                            'id'=>$model->id));
                      } else if ($_POST['command']=='setDO') {
                      	
                         $model->attributes=$_POST['Purchasesstockentries'];
                         Yii::app()->session['Purchasesstockentries']=$_POST['Purchasesstockentries'];
                         $this->loadDO($model->donum, $model->id);
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

             if(!isset(Yii::app()->session['Purchasesstockentries']))
                Yii::app()->session['Purchasesstockentries']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Purchasesstockentries'];

             if(!isset(Yii::app()->session['Detailpurchasesstockentries'])) 
               Yii::app()->session['Detailpurchasesstockentries']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Purchasesstockentries'];
                     $this->beforePost($model);
                     $this->tracker->modify('purchasesstockentries', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                     	throw new CHttpException(404,'There is an error in master posting ');
                     }

                     if(isset(Yii::app()->session['Detailpurchasesstockentries'])) {
                         $details=Yii::app()->session['Detailpurchasesstockentries'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     if(isset(Yii::app()->session['Deletedetailpurchasesstockentries'])) {
                         $deletedetails=Yii::app()->session['Deletedetailpurchasesstockentries'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                    
                     if($respond) {
                         Yii::app()->session->remove('Purchasesstockentries');
                         Yii::app()->session->remove('Detailpurchasesstockentries');
                         Yii::app()->session->remove('Deletedetailpurchasesstockentries');
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
            $this->tracker->delete('purchasesstockentries', $id);

            $detailmodels=Detailpurchasesstockentries::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailpurchasesstockentries', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Purchasesstockentries');
               Yii::app()->session->remove('Detailpurchasesstockentries');
               Yii::app()->session->remove('DeleteDetailpurchasesstockentries');
               $dataProvider=new CActiveDataProvider('Purchasesstockentries',
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
               
                $model=new Purchasesstockentries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Purchasesstockentries']))
			$model->attributes=$_GET['Purchasesstockentries'];

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
                $this->tracker->restore('purchasesstockentries', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasesstockentries');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('purchasesstockentries')
                	->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                	->queryScalar();
                $this->tracker->restoreDeleted('detailpurchasesstockentries', "id", $id );
                $this->tracker->restoreDeleted('purchasesstockentries', "idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasesstockentries');
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
	 * @return Purchasesstockentries the loaded model
	 * @throws CHttpException
	 */
	public function loadModelRegnum($regnum)
	{
		$model=Purchasesstockentries::model()->findByAttributes(array('regnum'=>$regnum));
		if($model===null)
			throw new CHttpException(405,'The requested page does not exist.');
		return $model;
	}

	public function loadModel($id)
	{
		$model=Purchasesstockentries::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(405,'The requested page does not exist.');
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Purchasesstockentries $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purchasesstockentries-form')
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
             $model=new Purchasesstockentries;
             $model->attributes=Yii::app()->session['Purchasesstockentries'];

             $details=Yii::app()->session['Detailpurchasesstockentries'];
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

             $model=new Purchasesstockentries;
             $model->attributes=Yii::app()->session['Purchasesstockentries'];

             $details=Yii::app()->session['Detailpurchasesstockentries'];
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


             $model=new Purchasesstockentries;
             $model->attributes=Yii::app()->session['Purchasesstockentries'];

             $details=Yii::app()->session['Detailpurchasesstockentries'];
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
     	Yii::import('application.modules.sellingprice.models.*');
     	require_once('Sellingprices.php');
     	
         foreach ($details as $row) {
             $detailmodel=new Detailpurchasesstockentries;
             $detailmodel->attributes=$row;
             $respond=$detailmodel->insert();
             $this->setSellingPrice($row['iddetail'], idmaker::getDateTime(), 
             		idmaker::getRegNum('AC11'), $row['iditem'], $row['sellprice'], 
             		'Bp Welly T', Yii::app()->user->id);	
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
             $detailmodel=Detailpurchasesstockentries::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailpurchasesstockentries;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailpurchasesstockentries', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailpurchasesstockentries::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d');
                 $this->tracker->delete('detailpurchasesstockentries', $detailmodel->id);
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
      $sql="select * from detailpurchasesstockentries where id='$id'";
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
         /*$idmaker=new idmaker();
         $idmaker->saveRegNum($this->formid, $model->regnum);
         
         $this->setStatusPO($model->idpurchaseorder,
            Yii::app()->session['Detailpurchasesstockentries']);
         */
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         //$model->regnum=$idmaker->getRegNum($this->formid);
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
        Yii::app()->session->remove('Detailpurchasesstockentries');
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
        Yii::app()->session['Detailpurchasesstockentries']=$details;
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
      
	private function setSellingPrice($id, $idatetime, $regnum, $iditem, $sellprice, 
			$approvalby, $userlog ) 
	{
		$sellingprice = new Sellingprices();
		$sellingprice->id = $id;
		$sellingprice->regnum = $regnum;
		$sellingprice->idatetime = $idatetime;
		$sellingprice->iditem = $iditem;
		$sellingprice->normalprice = $sellprice;
		$sellingprice->minprice = $sellprice;
		$sellingprice->approvalby = $approvalby;
		$sellingprice->userlog= $userlog;
		$sellingprice->datetimelog=$idatetime;
		$sellingprice->insert();
	}
	
	public function actionPrintlpb($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id)) {
			$this->trackActivity('p');
			 
			$model=$this->loadModel($id);
			$detailmodel=$this->loadDetails($id);
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.purchasesstockentries.components.*');
			require_once('printlpb.php');
			ob_clean();
	
			execute($model, $detailmodel);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}
		
	public function actionFinditem()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id)) {
			$this->trackActivity('p');
			$iditem = '';
			$founddata = array();
			$selectfields = <<<EOS
			b.iddetail, a.regnum, a.idatetime, a.ponum, b.buyprice, b.qty, 
			concat(c.firstname, ' ', c.lastname) as suppliername
EOS;
			if (isset($_GET['iditem'])) {
				$iditem = $_GET['iditem'];
				$founddata = Yii::app()->db->createCommand()
					->select($selectfields)->from('purchasesstockentries a')
					->join('detailpurchasesstockentries b', 'b.id = a.id')
					->join('suppliers c', 'c.id = a.idsupplier')
					->where('b.iditem = :p_iditem', array(':p_iditem'=>$iditem))
					->order('a.regnum desc')
					->queryAll();
				$serial = Yii::app()->db->createCommand()
					->select('b.serialnum')->from('stockentries a')->join('detailstockentries b', 'b.id = a.id')
					->where("a.transid = :p_transid and b.serialnum <> 'Belum Diterima' and b.iditem = :p_iditem");
				foreach ($founddata as & $data) {
					$serial->bindParam(':p_transid', $data['regnum']);
					$serial->bindParam(':p_iditem', $iditem);
			
					$result = $serial->queryColumn();
					if ($result !== FALSE)
						$data['serialnums'] = implode(', ', $result);
					else
						$data['serialnums'] = '';
				}
			}
			$this->render('finditem', array('founddata' => $founddata, 'iditem'=>$iditem));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}	
	}
      
	public function actionFindbrand()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id)) {
					$this->trackActivity('p');
					$brand = '';
					$founddata = array();
					$selectfields = <<<EOS
			b.iddetail, a.regnum, a.idatetime, a.ponum, b.buyprice, b.qty, b.iditem, 
			concat(c.firstname, ' ', c.lastname) as suppliername
EOS;
					if (isset($_GET['brand'])) {
						$brand = $_GET['brand'];
						$founddata = Yii::app()->db->createCommand()
							->select($selectfields)->from('purchasesstockentries a')
							->join('detailpurchasesstockentries b', 'b.id = a.id')
							->join('suppliers c', 'c.id = a.idsupplier')
							->join('items d', 'd.id = b.iditem')
							->where('d.brand = :p_brand', array(':p_brand'=>$brand))
							->order('a.regnum desc')
							->queryAll();
						$serial = Yii::app()->db->createCommand()
							->select('b.serialnum')->from('stockentries a')
							->join('detailstockentries b', 'b.id = a.id')
							->where("a.transid = :p_transid and b.serialnum <> 'Belum Diterima' and b.iditem = :p_iditem");
						foreach ($founddata as & $data) {
							$serial->bindParam(':p_transid', $data['regnum']);
							$serial->bindParam(':p_iditem', $data['iditem']);
								
							$result = $serial->queryColumn();
							if ($result !== FALSE)
								$data['serialnums'] = implode(', ', $result);
							else
								$data['serialnums'] = '';
						}
					}
					$this->render('findbrand',array('founddata' => $founddata, 'brand'=>$brand));
				} else {
					throw new CHttpException(404,'You have no authorization for this operation.');
				}
	}
}