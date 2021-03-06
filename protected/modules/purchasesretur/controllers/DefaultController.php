<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC10';
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
                    
                $model=new Purchasesreturs;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Purchasesreturs'])) {
                   Yii::app()->session['Purchasesreturs']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Purchasesreturs'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                   if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Purchasesreturs'];
                      
                      
                      $this->beforePost($model);
                      $respond=$model->save();
                      if(!$respond) {
                          throw new CHttpException(404,'There is an error in master posting');
                      }

                      if(isset(Yii::app()->session['Detailpurchasesreturs']) ) {
                        $details=Yii::app()->session['Detailpurchasesreturs'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } 

                      if($respond) {
                         $this->afterPost($model);
                         Yii::app()->session->remove('Purchasesreturs');
                         Yii::app()->session->remove('Detailpurchasesreturs');
                         $this->redirect(array('view','id'=>$model->id));
                      } 
                           
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Purchasesreturs'];
                         Yii::app()->session['Purchasesreturs']=$_POST['Purchasesreturs'];
                         $this->redirect(array('detailpurchasesreturs/create',
                            'id'=>$model->id));
                      } else if ($_POST['command']=='setPO') {
                         $model->attributes=$_POST['Purchasesreturs'];
                         Yii::app()->session['Purchasesreturs']=$_POST['Purchasesreturs'];
                         $this->loadPO($model->idpurchaseorder, $model->id);
                         $details=Yii::app()->session['Detailpurchasesreturs'];
                         $this->afterInsertDetail($model, $details);
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

             if(!isset(Yii::app()->session['Purchasesreturs']))
                Yii::app()->session['Purchasesreturs']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Purchasesreturs'];

             if(!isset(Yii::app()->session['Detailpurchasesreturs'])) 
               Yii::app()->session['Detailpurchasesreturs']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Purchasesreturs'];
                     $this->beforePost($model);
                     $this->tracker->modify('purchasesreturs', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailpurchasesreturs'])) {
                         $details=Yii::app()->session['Detailpurchasesreturs'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailpurchasesreturs'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailpurchasesreturs'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                    
                     if($respond) {
                         Yii::app()->session->remove('Purchasesreturs');
                         Yii::app()->session->remove('Detailpurchasesreturs');
                         Yii::app()->session->remove('DeleteDetailpurchasesreturs');
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
            $this->tracker->delete('purchasesreturs', $id);

            $detailmodels=Detailpurchasesreturs::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailpurchasesreturs', array('iddetail'=>$dm->iddetail));
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

         Yii::app()->session->remove('Purchasesreturs');
         Yii::app()->session->remove('Detailpurchasesreturs');
         Yii::app()->session->remove('DeleteDetailpurchasesreturs');
         $dataProvider=new CActiveDataProvider('Purchasesreturs',
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
         $model=new Purchasesreturs('search');
         $model->unsetAttributes();  // clear any default values
         if(isset($_GET['Purchasesreturs']))
            $model->attributes=$_GET['Purchasesreturs'];

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
                $this->tracker->restore('purchasesreturs', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasesreturs');
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
                $this->tracker->restoreDeleted('purchasesreturs', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Purchasesreturs');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
        public function actionPrint($id)
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
        			Yii::app()->user->id))  {
        					
				$masterdata = $this->loadModel($id);
        		if ($masterdata) {
        			$detaildata = $this->loadDetails($id);
        		};
        		Yii::import('application.vendors.tcpdf.*');
        		require_once ('tcpdf.php');
        		Yii::import('application.modules.purchasesretur.components.*');
        		require_once('print_purchasesretur.php');
        		ob_clean();
        					
        		execute($masterdata, $detaildata);
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
        		->select('a.idatetime, a.regnum, a.idsupplier, b.iditem, b.batchcode, b.qty, b.price, b.discount')
        		->from('purchasesreturs a')->join('detailpurchasesreturs b', 'b.id = a.id')
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
        	Yii::import('application.modules.purchasesreturs.components.*');
        	require_once('print_purchasesreturreport.php');
        	ob_clean();
        
        	execute($reportdata);
        } else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        }
	}
        
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Purchasesreturs the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Purchasesreturs::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Purchasesreturs $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purchasesreturs-form')
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
             $model=new Purchasesreturs;
             $model->attributes=Yii::app()->session['Purchasesreturs'];

             $details=Yii::app()->session['Detailpurchasesreturs'];
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

             $model=new Purchasesreturs;
             $model->attributes=Yii::app()->session['Purchasesreturs'];

             $details=Yii::app()->session['Detailpurchasesreturs'];
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


             $model=new Purchasesreturs;
             $model->attributes=Yii::app()->session['Purchasesreturs'];

             $details=Yii::app()->session['Detailpurchasesreturs'];
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
             $detailmodel=new Detailpurchasesreturs;
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
             $detailmodel=Detailpurchasesreturs::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailpurchasesreturs;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailpurchasesreturs', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailpurchasesreturs::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailpurchasesreturs', $detailmodel->id);
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
      $sql="select * from detailpurchasesreturs where id='$id'";
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
         $model->total=0;
         $model->discount=0;
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         $idmaker->saveRegNum($this->formid, $model->regnum);
         
         if ($this->state == 'c')
         	Action::addStock($model->id, $model->idatetime, $model->regnum, 'Retur Beli');
         else if ($this->state == 'u')
         	Action::updateStock($model->id, $model->idatetime);
         
         $details = $this->loadDetails($model->id);
         
         foreach($details as $d) {
         	if ($this->state == 'c')
         		Action::addDetailStock($d['iddetail'], $d['id'], $d['iditem'], $d['batchcode'], - $d['qty']);
         	else if ($this->state == 'u')
         		Action::updateDetailStock($d['iddetail'], $d['iditem'], $d['batchcode'], - $d['qty']);
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
     	Action::deleteDetailStock2($model->id);
     }

     protected function afterDelete(& $model)
     {

     }

     protected function afterEdit(& $model)
     {

     }

     protected function afterInsertDetail(& $model, $details)
     {
     	$total=0;
     	$totaldisc=0;
     	foreach ($details as $row) {
     		$total=$total+(($row['price'])*$row['qty']);
     		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
     	}
     	$model->attributes=Yii::app()->session['Purchasesreturs'];
     	$model->total=$total;
     	$model->discount=$totaldisc;
     }


     protected function afterUpdateDetail(& $model, $details)
     {
     	$total=0;
     	$totaldisc=0;
     	foreach ($details as $row) {
     		$total=$total+(($row['price']+$row['cost1']+$row['cost2'])*$row['qty']);
     		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
     	}
     	$model->attributes=Yii::app()->session['Purchasesreturs'];
     	$model->total=$total;
     	$model->discount=$totaldisc;
     }

     protected function afterDeleteDetail(& $model, $details)
     {
     	$total=0;
     	$totaldisc=0;
     	foreach ($details as $row) {
     		$total=$total+(($row['price']+$row['cost1']+$row['cost2'])*$row['qty']);
     		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
     	}
     	$model->attributes=Yii::app()->session['Purchasesreturs'];
     	$model->total=$total;
     	$model->discount=$totaldisc;
     }


     protected function trackActivity($action)
     {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
     }
     
      
     
      
}