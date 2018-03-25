<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Userpassedq extends NotORM {

    protected function getTableName($id) {
        return 'userpassedq';
    }
    public function getAll(){
        $model=$this->getORM();
        $data=$model->select("*");
         return $data;
    }
    public function GetById($id)
    {
        $model=$this->getORM();
        $data=$model->where("id",$id);
        return data;
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

}