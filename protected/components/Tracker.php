<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tracker
 *
 * @author teddy
 * 
 * actions symbols are:
 *  c = create
 *  d = delete
 *  u = update
 *  l = list
 *  r = restore 
 *  n = undelete
 *  s = search
 */
class Tracker extends CComponent {
   //put your code here
   
    private $idtrack;
    private $idatetime;
    
    public function init() 
    {
        $idmaker=new idmaker();
        $this->idtrack=$idmaker->getCurrentID2();
        $this->idatetime=$idmaker->getDateTime();
    }
    
    public function logActivity($idform, $action) 
    {
        $iduser=Yii::app()->user->id;
        
        $data=array('id'=>$this->idtrack, 'iduser'=>Yii::app()->user->id, 
           'idatetime'=>$this->idatetime, 'idform'=> $idform, 'action'=>$action,
           'ipaddr'=>$_SERVER['REMOTE_ADDR']);
        Yii::app()->tracker->createCommand()->insert("userjournal", $data);
    }
    
    public function modify($tablename, $id) 
    {
        if(is_array($id)) {
            $tk=key($id);
            $data=Yii::app()->db->createCommand()->select()->from($tablename)
                ->where("$tk=:id",array(':id'=>$id[$tk]))->queryRow();
        } else if(is_string($id)){
            $data=Yii::app()->db->createCommand()->select()->from($tablename)
                ->where('id=:id',array(':id'=>$id))->queryRow();
        }
        $data['idtrack']=$this->idtrack;
        $data['userlogtrack']=Yii::app()->user->id;
        $data['datetimelogtrack']=$this->idatetime;
        $data['action']='u';
        Yii::app()->tracker->createCommand()->insert($tablename, $data);

        return $data['idtrack'];
    }
   
    public function delete($tablename, $id) 
    {
        
        if(is_array($id)) {
            $tk=key($id);
            $data=Yii::app()->db->createCommand()->select()->from($tablename)
                ->where("$tk=:id",array(':id'=>$id[$tk]))->queryRow();
        } else if(is_string($id)){
            $data=Yii::app()->db->createCommand()->select()->from($tablename)
                ->where('id=:id',array(':id'=>$id))->queryRow();
        }
        $data['idtrack']=$this->idtrack;
        $data['userlogtrack']=Yii::app()->user->id;
        $data['datetimelogtrack']=$this->idatetime;
        $data['action']='d';
        Yii::app()->tracker->createCommand()->insert($tablename, $data);

        return $data['idtrack'];
    }
   
    public function restore($tablename, $idtrack, $id='id') 
    {
        $data=Yii::app()->tracker->createCommand()->select()->from($tablename)
         ->where('idtrack=:idtrack',array(':idtrack'=>$idtrack))->queryRow();
        unset($data['idtrack']);
        unset($data['userlogtrack']);
        unset($data['datetimelogtrack']);
        unset($data['action']);
        /*$datalog=Yii::app()->tracker->createCommand()->select()->from('userjournal')
           ->where('id=:id', array(':id'=>$idtrack))->queryRow();
        */
        Yii::app()->db->createCommand()->update($tablename, $data, "$id=:myid", 
               array(':myid'=>$data[$id]));
    }
    
    public function restoreDeleted($tablename, $id='idtrack', $idtrack ) 
    {
        $datas=Yii::app()->tracker->createCommand()->select()->from($tablename)
         ->where("$id=:p_idtrack",array(':p_idtrack'=>$idtrack))->queryAll();
        foreach($datas as $data) {
        	Yii::app()->tracker->createCommand()->delete($tablename,"idtrack=:p_idtrack",
        		array(':p_idtrack'=>$data['idtrack']));
            unset($data['idtrack']);
            unset($data['userlogtrack']);
            unset($data['datetimelogtrack']);
            unset($data['action']);
            Yii::app()->db->createCommand()->insert($tablename, $data);
        }
    }
}

?>
