<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AB6';
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
        }
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

            $model=new Inventorytakings;
            $this->afterInsert($model);

            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if(isset($_POST['Inventorytakings']))
            {
               $model->attributes=$_POST['Inventorytakings'];
               $this->beforePost($model);
               if($model->save()) {
                  $this->afterPost($model);
                  $this->redirect(array('view','id'=>$model->id));                 
               }    
            }

            $this->render('create',array('model'=>$model));
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

         // Uncomment the following line if AJAX validation is needed
         $this->performAjaxValidation($model);

         if(isset($_POST['Inventorytakings']))
         {
            $model->attributes=$_POST['Inventorytakings'];

            $this->beforePost($model);   
            $this->tracker->modify('inventorytakings', $id);
            if($model->save()) {
               $this->afterPost($model);
               $this->redirect(array('view','id'=>$model->id));
            }        
         }

         $this->render('update',array(
               'model'=>$model,
         ));
         } else {
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
            $this->tracker->delete('inventorytakings', $id);

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

            $dataProvider=new CActiveDataProvider('Inventorytakings');
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

            $model=new Inventorytakings('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Inventorytakings']))
               $model->attributes=$_GET['Inventorytakings'];

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
                $this->tracker->restore('inventorytakings', $idtrack);

                $dataProvider=new CActiveDataProvider('Inventorytakings');
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
               $this->tracker->restoreDeleted('inventorytakings', $idtrack);

               $dataProvider=new CActiveDataProvider('Inventorytakings');
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
	 * @return Inventorytakings the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
         $model=Inventorytakings::model()->findByPk($id);
         if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
         return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Inventorytakings $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
         if(isset($_POST['ajax']) && $_POST['ajax']==='inventorytakings-form')
         {
            echo CActiveForm::validate($model);
            Yii::app()->end();
         }
	}
        
      protected function afterInsert(& $model)
      {
         $idmaker=new idmaker();
         $model->id=$idmaker->getcurrentID2();  
         $model->idatetime=$idmaker->getDateTime();
      }
        
      protected function afterPost(& $model)
      {
      	/*$im=new InventoryManager();
		$im->setupDB($model->id);*/
      }

      protected function beforePost(& $model)
      {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
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

      protected function trackActivity($action)
      {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
      }
      
      public function actionPrintSummary($id)
      {
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {     
	      	$this->trackActivity('v');
	      	Yii::import("application.vendors.tcpdf.*");
	      	require_once('tcpdf.php');
	      	$this->render('printsummary',array(
	      			'model'=>$this->loadModel($id),
	      	));
      	} else  {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
      	
      }
      
      public function actionStockCard($id)
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {
      		$this->trackActivity('v');
      		
      		$detailData=Yii::app()->db->createCommand()
      			->select('b.iditem, b.idwarehouse, c.name, sum(b.qty) as totalqty')
      			->from('inputinventorytakings a')
      			->join('detailinputinventorytakings b', 'b.id=a.id')
      			->join('items c', 'c.id=b.iditem')
      			->where('a.idinventorytaking=:p_idit', array(':p_idit'=>$id))
      			->group('b.iditem, b.idwarehouse')
      			->order('c.name')
      			->queryAll();
      		$this->render('stockcard',array(
      				'detailData'=>$detailData, 'model'=>$this->loadModel($id)
      		));
      	} else  {
      		throw new CHttpException(404,'You have no authorization for this operation.');
      	};
      }
      
	public function actionPrintstockcard($iditem, $idwarehouse)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
			Yii::app()->user->id))  {
			$this->trackActivity('v');
	            
			$sql1=<<<EOS
	select a.idatetime, sum(b.qty) as qty, 'Stok Opname' as message, c.operationlabel, b.userlog from inputinventorytakings a 
	join detailinputinventorytakings b on b.id = a.id
	join inventorytakings c on c.id = a.idinventorytaking
	where b.iditem = :iditem and b.idwarehouse = :idwarehouse
	group by b.userlog   		
EOS;
			$command=Yii::app()->db->createCommand($sql1);
			$command->bindParam(':iditem', $iditem, PDO::PARAM_STR);
			$command->bindParam(':idwarehouse', $idwarehouse, PDO::PARAM_STR);
			$detailData=$command->queryAll();
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			$this->render('print_stockcard', array(
				'detailData'=>$detailData, 
				'itemname'=>lookup::ItemNameFromItemID($iditem),
				'warehousecode'=>lookup::WarehouseNameFromWarehouseID($idwarehouse) 			
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionPrintallstockcard($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$detailData=Yii::app()->db->createCommand()
				->select('b.iditem, b.idwarehouse, sum(b.qty) as totalqty')
				->from('inputinventorytakings a')
				->join('detailinputinventorytakings b', 'b.id=a.id')
				->where('a.idinventorytaking=:p_idit', array(':p_idit'=>$id))
				->group('b.iditem, b.idwarehouse')
				->order('b.iditem')
				->queryAll();
			$sql1=<<<EOS
	select a.idatetime, sum(b.qty) as qty, 'Stok Opname' as message, c.operationlabel, b.userlog from inputinventorytakings a
	join detailinputinventorytakings b on b.id = a.id
	join inventorytakings c on c.id = a.idinventorytaking
	where b.iditem = :iditem and b.idwarehouse = :idwarehouse
	group by b.userlog
EOS;
			$command=Yii::app()->db->createCommand($sql1);
			
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			$mypdf=new Stockcardpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, 
					PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$mypdf->init();
			foreach($detailData as $data) {
				
					
				$command=Yii::app()->db->createCommand($sql1);
				$command->bindParam(':iditem', $data['iditem'], PDO::PARAM_STR);
				$command->bindParam(':idwarehouse', $data['idwarehouse'], PDO::PARAM_STR);
				$stockData=$command->queryAll();
				
				$mypdf->LoadData(lookup::ItemNameFromItemID($data['iditem']), 
						lookup::WarehouseNameFromWarehouseID($data['idwarehouse']), 
						$stockData);	
				//echo 'boom<br>';
				$mypdf->display();	
				
			}
			$mypdf->Output('KartuStok'.'-'.date('Ymd').'.pdf', 'I');
			//$mypdf->Output('KartuStok'.$this->itemname.'-'.$this->warehousecode.'-'.date('Ymd').'.pdf', 'D');
 		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionPrintstockcard2()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			if (isset($_POST['yt0'])) {
				$checkboxes=$_POST['yw0_c3'];
				$sql1=<<<EOS
	select a.idatetime, sum(b.qty) as qty, 'Stok Opname' as message, c.operationlabel, b.userlog from inputinventorytakings a
	join detailinputinventorytakings b on b.id = a.id
	join inventorytakings c on c.id = a.idinventorytaking
	where b.iditem = :iditem and b.idwarehouse = :idwarehouse
	group by b.userlog
EOS;
				$command=Yii::app()->db->createCommand($sql1);
					
				Yii::import('application.vendors.tcpdf.*');
				require_once ('tcpdf.php');
				$mypdf=new Stockcardpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$mypdf->init();
				
				foreach($checkboxes as $cb) {
					$temp=explode('-', $cb);
					$command=Yii::app()->db->createCommand($sql1);
					$command->bindParam(':iditem', $temp[0], PDO::PARAM_STR);
					$command->bindParam(':idwarehouse', $temp[1], PDO::PARAM_STR);
					$stockData=$command->queryAll();
					
					$mypdf->LoadData(lookup::ItemNameFromItemID($temp[0]),
							lookup::WarehouseNameFromWarehouseID($temp[1]),
							$stockData);
					//echo 'boom<br>';
					$mypdf->display();
				}
				$mypdf->Output('KartuStok'.'-'.date('Ymd').'.pdf', 'I');
			}
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
}
