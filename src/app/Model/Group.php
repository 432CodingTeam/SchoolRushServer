<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Group extends NotORM {

    protected function getTableName($id) {
        return '`group`';
    }

    public function getAll() {
        //获取label表的所有行
        $model = $this->getORM();
        $data = $model->select("*");
        return $data;
    }
    
    public function GetIdByName($name)
    {
        $model=$this->getORM();
        $data=$model->where("name",$name);
        //var_dump($data);
        return $data;
    }
    
    public function getById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id);
        return $data;
    }
    public function deleteById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id)->delete();
        return $data;
    }
    public function add($insert_data) {
        $model = $this->getORM();
        $id = $model->insert($insert_data);

        return $id;
    }
    public function updateById($id,$data){
        $model = $this->getORM();
        return $model->where("id",$id)->update($data);
    }
}