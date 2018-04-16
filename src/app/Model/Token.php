<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Token extends NotORM {

    protected function getTableName($id) {
        return 'token';
    }

    public function getByToken($token) {
        $model = $this->getORM();

        $data = $model->where("token",$token);
        return $data;
    }

    public function getByUid($uid) {
        $model = $this->getORM();

        $data = $model->where("uid",$uid);
        return $data;
    }
    public function deleteByToken($token) {
        $model = $this->getORM();

        $res = $model->where("token",$token)->delete();
        return $res;
    }

    public function deleteByUid($uid) {
        $model = $this->getORM();

        $res = $model->where("uid",$uid)->delete();
        return $res;
    }
    public function add($insert_data) {
        $model = $this->getORM();
        $res = $model->insert($insert_data);
        
        return $res;
    }

    public function updateById($id, $data)
    {
        $model=$this->getORM();
        $res = $model->where("id",$id)->update($data);
        return $res;
    }
    public function getAll()
    {
        $model=$this->getORM();
        return $model->fetchall();
    }
}