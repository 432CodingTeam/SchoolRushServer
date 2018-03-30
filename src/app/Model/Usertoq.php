<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Usertoq extends NotORM {

    protected function getTableName($id) {
        return 'usertoq';
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

    public function getPassingRate($id ){
        $model = $this->getORM();
        
        $passedq = $model->where('id',$id)->and('status',1);
        $allq = $model->where('id',$id)->and('status',array(0,1));
        $rate = $passedq / $allq;
        
        return $rate;
    }

    public function getTobeSolved($id){
        $model = $this->getORM();

        return $model->where('id',$id)->and('status',0);
    }

    public function getPassed($id){
        $model = $this->getORM();

        return $model->where('id',$id)->and('status',1);
    }
}