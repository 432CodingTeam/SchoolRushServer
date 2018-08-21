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
    
    public function getIdByName($name)
    {
        $model=$this->getORM();
        $data=$model->where("name",$name);
        //var_dump($data);
        return $data;
    }
    
    public function getById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id)->fetchOne();
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
    public function getTotalNum(){
        $model = $this->getORM();

        return $model->count("id");
    }

    public function getByIds($ids) {
        $model = $this -> getORM();

        return $model -> where("id", $ids);
    }
}