<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of idmaker
 *
 * @author teddy
 */

class idmaker extends CComponent 
{
   //put your code here
   
   public static function getCurrentID2()
   {
      /*
      $connection=Yii::app()->db;
      $command=$connection->createCommand();
      $command->text="select concat(date_format(sysdate(), '%y'), ".
             "hex(cast(date_format(sysdate(), '%m') as unsigned)), ".
             "date_format(sysdate(),'%d%H%i%s%f')) as ts";
      $newid=$command->queryScalar();
      */
	list($usec, $sec) = explode(' ', microtime());
      //$curdate=new DateTime($sec);
      //$month=$curdate->format('n');
      $month=date('n', $sec);
      switch ($month) {
          case 10:
              $hexmonth='a';
              break;
          case 11:
              $hexmonth='b';
              break;
          case 12:
              $hexmonth='c';
              break;
          default:
              $hexmonth=$month;
              break;
      }
      $usec=$usec*1000000;
      $newid=date('y', $sec).$hexmonth.date('dHis', $sec).$usec;
      //$newid=$curdate->format('y').$hexmonth.$curdate->format('dHis').$usec;
      //$rand=mt_rand(1,999);
      //$newid=$newid.str_pad($rand, 6, '0',STR_PAD_LEFT);
      
      /*$iterator=Yii::app()->db->createCommand()->select()->from('iditerator')->queryRow();
      if($iterator['id']===$newid) {
          $iterator['iterator']=$iterator['iterator']+1;
      } else {
          $iterator['id']=$newid;
          $iterator['iterator']=0;    
      }
      Yii::app()->db->createCommand()->truncateTable('iditerator');
      Yii::app()->db->createCommand()->insert('iditerator', $iterator);
      $newid=$newid.str_pad($iterator['iterator'], 3,'0',STR_PAD_LEFT);
      */
      return $newid;
   }
   
   public static function getDateTime()
   {
      return Date('Y/m/d H:i:s');
   }
   
   public static function getCurrentID(){
      switch(date('m')) {
         case '01':
            $curmonth='A';
            break;
         case '02':
            $curmonth='B';
            break;
         case '03':
            $curmonth='C';
            break;
         case '04':
            $curmonth='D';
            break;
         case '05':
            $curmonth='E';
            break;
         case '06':
            $curmonth='F';
            break;
         case '07':
            $curmonth='G';
            break;
         case '08':
            $curmonth='H';
            break;
         case '09':
            $curmonth='I';
            break;
         case '10':
            $curmonth='J';
            break;
         case '11':
            $curmonth='K';
            break;
         case '12':
            $curmonth='L';
            break;
      };
      $result=Date('y').$curmonth.Date('dHisu');
      
      return $result;
   }
   
   public static function getRegNum($formid)
   {
      $sql="select val from information where id='$formid'";
      $temp=Yii::app()->db->createCommand($sql)->queryScalar()+1;
      return $temp;      
   }
   
   public static function saveRegNum($formid, $regnum)
   {
      $sql="update information set val='$regnum' where id='$formid'";
      Yii::app()->db->createCommand($sql)->execute();
   }
   
   public static function lookUpAuthType($type)
   {
      switch ($type) {
         case 0:
            return 'Tindakan/Operasi';
            break;
         case 1:
            return 'Form/Tugas';
            break;
         case 2:
            return 'Posisi/Peran';
            break;
      }
   }
   
   public static function lookUpUserState($state)
   {
       switch ($state) {
           case 0:
               return 'Tidak Aktif';
               break;
           case 1:
               return 'Aktif';
               break;
       }
   }
      
   public static function decodeUpdateUserAuthUrl($iduser, $id)
   {
      return Yii::app()->createUrl('auths/updateforuser', array('iduser'=>$iduser,'id'=>$id));
   }
   
   public static function decodeDeleteAdoptedOperationUrl($data)
   {
      return Yii::app()->createUrl('user/rbac/deleteadoptedoperation', array('id'=>$data['id']));
   }
   
   public static function decodeDeleteAdoptedTaskUrl($data)
   {
      return Yii::app()->createUrl('user/rbac/deleteadoptedtask', array('id'=>$data['id']));
   }
   
   public function decodeDeleteAdoptedRoleUrl($data)
   {
      return Yii::app()->createUrl('user/rbac/deleteadoptedrole', array('id'=>$data['id']));
   }
   
   public static function decodeDeleteAssignedOperationUrl($data)
   {
      return Yii::app()->createUrl('user/default/deleteassignedoperation', array('id'=>$data['id']));
   }
   
   public static function decodeDeleteAssignedTaskUrl($data)
   {
      return Yii::app()->createUrl('user/default/deleteassignedtask', array('id'=>$data['id']));
   }
   
   public static function decodeDeleteAssignedRoleUrl($data)
   {
      return Yii::app()->createUrl('user/default/deleteassignedrole', array('id'=>$data['id']));
   }
   
   
   
}

?>
