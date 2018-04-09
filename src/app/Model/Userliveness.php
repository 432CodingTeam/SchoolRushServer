<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Userliveness extends NotORM {

    protected function getTableName($id) {
        return 'userliveness';
    }
    public function getAll(){
        $model=$this->getORM();
        $data=$model->select("*");
         return $data;
    }
    public function getById($id)
    {
        $model=$this->getORM();
        $data=$model->where("id",$id);
        return $data;
    }
    public function deleteById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id)->delete();
        return $data;
    }
    public function add($insert_data)
    {
        $model=$this->getORM();
        $id=$model->insert($insert_data);
        return $id;
    }

    public function updateById($id,$data) {
        $model = $this->getORM();

        return $model->where("id", $id)->update($data);
    }
    public function getByTime($starttime,$endtime)
    {
        $model=$this->getORM();
       //$start_time=date($starttime,time());
        //$end_time=date($endtime,time());
       // $data=array();
        //$data=$map['publishtime']=array('between','$start_ttime,$end_time');
      
        $start_time=strtotime('starttime');
        $end_time=strtotime('endtime');
        for($i=strtotime($start_time);$i<=strrtotime($end_time);$i+=86400)
        {
        }
        return $data;
    }
}