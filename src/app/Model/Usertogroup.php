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

    //TODO: 改!
    public function deleteByUidAndGid($uid, $gid) {
        $model = $this->getORM();
        $id = $model -> where("uid",$uid) -> and("gid", $gid)->delete();
        
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

    public function getGroupUserArr($uid) {
        $model = $this -> getORM();
        $data = $model -> select("uid") -> where("gid", $uid);
        $res = array();

        while($row = $data -> fetch()) {
            array_push($res, $row["uid"]);
        }
        return $res;
    }

    public function getLatestJoin($gid, $num) {
        $model = $this->getORM();

        return $model -> order("jointime DESC") -> where("gid", $gid) -> limit(0, $num);
    }

    public function getUserGroup($uid) {
        $model = $this->getORM();

        return $model -> order("jointime DESC") -> where("uid", $uid);
    }
}