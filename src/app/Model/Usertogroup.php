<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Usertogroup extends NotORM {

    protected function getTableName($id) {
        return 'usertogroup';
    }

    public function getAll() {
        //获取label表的所有行
        $model = $this->getORM();
        $data = $model->select("*");
        return $data;
    }

    public function getById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id);
        $data = $data->fetchOne();
        return $data;
    }
    public function getByuid($uid) {
        $model = $this->getORM();

        $data = $model->where("uid",$uid);
        return $data;
    }
    public function deleteById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id)->delete();
        return $data;
    }

    public function updateById($id,$data) {
        $model = $this->getORM();

        return $model->where("id", $id)->update($data);
    }

    public function add($insert_data) {
        $model = $this->getORM();
        $id = $model->insert($insert_data);
        
        return $id;
    }

    public function delete($delete_data) {
        $model = $this->getORM();
        $id = $model->insert($delete_data);
        
        return $id;
    }

    public function Inquery($uid, $gid){
        $model = $this->getORM();
        
        return $model->where("uid",$uid)->and("gid",$gid)->fetch();
    }
    public function getBygid($gid) {
        $model = $this->getORM();

        $data = $model->where("gid",$gid);
        $data = $data->fetchall();
        return $data;
    }
}