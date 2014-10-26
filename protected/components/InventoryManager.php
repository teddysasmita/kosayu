<?php

class InventoryManager
{
	private $activeID;
	
	private function initiateID()
	{
		$this->activeID=Yii::app()->db->createCommand()->select('id')
			->from('inventorytakings')
			->where('status=:p_status', array(':p_status'=>'1'))
			->queryScalar();
	}
	
	public static function getID()
	{
		return $this->activeID;		
	}
	
	public function addEntry($idform, $id, $idatetime, $iditem, $idwarehouse, $qty)
	{
		$num=Yii::app()->db->createCommand()
			->insert('inventory'.$this->activeID, 
				array('idform'=>$idform, 'id'=>$id, 'idatetime'=>$idatetime, 'iditem'=>$iditem,
					'idwarehouse'=>$idwarehouse, 'qty'=>$qty) );
	}
	
	public function deleteEntry($id)
	{
		Yii::app()->db->createCommand()->delete('inventory'.$this->activeID,
			'id=:p_id', array(':p_id'=>$id));
	}
	
	public function updateEntry($id, $idatetime, $iditem, $idwarehouse, $qty)
	{
		Yii::app()->db->createCommand()->update('inventory'.$this->activeID,
			array('idatetime'=>$idatetime, 'iditem'=>$iditem,
					'idwarehouse'=>$idwarehouse, 'qty'=>$qty),
		 'id=:p_id', array('p_id'=>$id));
	}
	
	public static function setupDB($id)
	{
		$createsql=<<<EOS
	CREATE TABLE IF NOT EXISTS `inventory$id` (
		`id` varchar(21) NOT NULL,
		`idform` varchar(10) NOT NUll,
	  	`idatetime` char(19) NOT NULL,
	  	`iditem` varchar(21) NOT NULL,
	  	`idwarehouse` varchar(21) NOT NULL,
	  	`qty` double NOT NULL,
	  	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
EOS;
		Yii::app()->db->createCommand($createsql)->execute();
	}
	
	public function __construct()
	{
		$this->initiateID();
	}
	
}

?>