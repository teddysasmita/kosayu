<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $formid='AB19';
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
                    
                $model=new Employees;
                $this->afterInsert($model);
                
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Employees']))
		{
			$model->attributes=$_POST['Employees'];
                        $this->beforePost($model);
			if($model->save()) {
                            $this->afterPost($model);
                            $this->redirect(array('view','id'=>$model->id));                 
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
                
		// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST['Employees']))
			{
				$model->attributes=$_POST['Employees'];
                         
				$this->beforePost($model);   
				$this->tracker->modify('employees', $id);
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
                
                    $this->trackActivity('d');
                $model=$this->loadModel($id);
                $this->beforeDelete($model);
                $this->tracker->delete('employees', $id);
                
                $model->delete();
                $this->afterDelete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
                
	            $dataProvider=new CActiveDataProvider('Employees',
	            	array(
                     'criteria'=>array(
                        'order'=>'datetimelog desc, id desc'
                     )
                  ));
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
               
                $model=new Employees('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Employees']))
			$model->attributes=$_GET['Employees'];

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
                $this->tracker->restore('employees', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Employees');
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
                $this->tracker->restoreDeleted('employees', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Employees');
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
	 * @return Employees the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Employees::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Employees $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='employees-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getcurrentID2();  
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
        }
        
        protected function afterPost(& $model)
        {
            
        }
        
        protected function beforePost(& $model)
        {
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
        
       	public function actionPrintstockcard($id)
       	{
       		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
	            $this->trackActivity('v');
	            
	            if (isset($_POST['idwarehouse'])) {
	          		if (strlen($_POST['idwarehouse'])) {
	            		$sql1=<<<EOS
	select a.idatetime, sum(b.qty) as qty, 'Stok Opname' as message, c.operationlabel, b.userlog from inputinventorytakings a 
	join detailinputinventorytakings b on b.id = a.id
	join inventorytakings c on c.id = a.idinventorytaking
	where b.iditem = :iditem and b.idwarehouse = :idwarehouse
	group by b.userlog   		
EOS;
	            		$command=Yii::app()->db->createCommand($sql1);
	          			$command->bindParam(':iditem', $_POST['id'], PDO::PARAM_STR);
	          			$command->bindParam(':idwarehouse', $_POST['idwarehouse'], PDO::PARAM_STR);
	            		$detailData=$command->queryAll();
	            		Yii::import('application.vendors.tcpdf.*');
						require_once ('tcpdf.php');
						Yii::import('application.modules.item.views.default.*');
						require_once ('print_stockcard.php');
						ob_clean();
						execute(lookup::ItemNameFromItemID($_POST['id']), 
                			lookup::WarehouseNameFromWarehouseID($_POST['idwarehouse']),
                			$detailData
                		);
	          		}
	            } 
	            //else {
	            	$warehouses=Yii::app()->db->createCommand()
	            		->select('id, code')->from('warehouses')->queryAll();
		            $this->render('stockcard',array(
						'warehouses'=>$warehouses, 'id'=>$id
					));
	            //};
       		} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	};
       	}
       	
       	public function actionPrintblankstockcard($id)
       	{
       		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
       				Yii::app()->user->id))  {
       			$this->trackActivity('v');
       			 
       			if (isset($_POST['idwarehouse'])) {
       				if (strlen($_POST['idwarehouse'])) {
       					Yii::import('application.vendors.tcpdf.*');
       					require_once ('tcpdf.php');
       					Yii::import('application.modules.item.views.default.*');
       					require_once ('print_stockcard.php');
       					ob_clean();
       					execute(lookup::ItemNameFromItemID($_POST['id']), 
       						lookup::WarehouseNameFromWarehouseID($_POST['idwarehouse']),
		            		array());
       				}
       			}
       									//else {
       			$warehouses=Yii::app()->db->createCommand()
       				->select('id, code')->from('warehouses')->queryAll();
       			$this->render('stockcard',array( 'warehouses'=>$warehouses, 
       				'id'=>$id));
       		} else {
       			throw new CHttpException(404,'You have no authorization for this operation.');
       		};
       	}
       	
       	public function actionExport2xcl()
       	{
       		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
       				Yii::app()->user->id))  {
       			$this->trackActivity('v');
				
       			if (isset($_POST['selectemployees'])) {
       				//print_r($_POST);
       				$query = 'select id,code,name from employees';
       				if ($_POST['selectemployees']['brand'] !== '')
       					$where[] = 'brand = \''.$_POST['selectemployees']['brand'].'\'';
       				if ($_POST['selectemployees']['object'] !== '')
       					$where[] = 'objects = \''.$_POST['selectemployees']['object'].'\'';
       				if ($_POST['selectemployees']['model'] !== '')
       					$where[] = 'model = \''.$_POST['selectemployees']['model'].'\'';
       				if (isset($where)) {
       					$query .= ' where ';
       					$query = $query . implode(' and ', $where);
       				};
       				$xl = new PHPExcel();
       				$xl->getProperties()->setCreator("Program GSI Malang")
	       				->setLastModifiedBy("Program GSI Malang")
	       				->setTitle("Daftar Barang")
	       				->setSubject("Daftar Barang")
	       				->setDescription("Daftar Barang")
	       				->setKeywords("Nama Barang")
	       				->setCategory("Daftar");
       				$data = Yii::app()->db->createCommand($query)->queryAll();
       				$headersfield = array( 'id', 'code', 'name');
       				$headersname = array('ID', 'Kode', 'Nama Barang');
       				for( $i=0;$i<count($headersname); $i++ ) {
       					$xl->setActiveSheetIndex(0)
       					->setCellValueByColumnAndRow($i,1, $headersname[$i]);
       				}
       				for( $i=0; $i<count($data); $i++){
       					for( $j=0; $j<count($headersfield); $j++ ) {
       						$cellvalue = $data[$i][$headersfield[$j]];
       						if ($headersfield[$j] == 'idsales')
       							$cellvalue = lookup::SalesPersonNameFromID($data[$i]['idsales']);
       						else if ($headersfield[$j] == 'iditem')
       							$cellvalue = lookup::ItemNameFromItemID($data[$i]['iditem']);
       						$xl->setActiveSheetindex(0)
       						->setCellValueByColumnAndRow($j,$i+2, $cellvalue);
       					}
       				}
       					
       				$xl->getActiveSheet()->setTitle('Laporan Penjualan');
       				$xl->setActiveSheetIndex(0);
       				header('Content-Type: application/pdf');
       				header('Content-Disposition: attachment;filename="nama_barang.xlsx"');
       				header('Cache-Control: max-age=0');
       				$xlWriter = PHPExcel_IOFactory::createWriter($xl, 'Excel2007');
       				$xlWriter->save('php://output');
       			} else 
       				$this->render('selectemployees');
       		} else {
       			throw new CHttpException(404,'You have no authorization for this operation.');
       		};
       	}
	
	public function actionShowSales($id) 
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			
			$datasales = Yii::app()->db->createCommand()
				->select("a.id, a.idatetime, a.regnum, a.userlog, b.iddetail, b.price, b.discount, b.qty, (b.discount*b.qty) as totaldisc, ((b.price-b.discount)*b.qty) as totalprice")
				->from('salespos a')->join('detailsalespos b', 'b.id = a.id')
				->where('b.iditem = :p_iditem', array(':p_iditem'=>$id))
				->queryAll();
			
			$this->render('viewsales', array('id'=>$id, 'data'=> $datasales));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
		
	
	}
}
