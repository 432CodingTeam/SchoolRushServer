<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Follow extends NotORM {

    protected function getTableName($id) {
        return 'follow';
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

    public function updateById($id,$data) {
        $model = $this->getORM();

        return $model->where("id", $id)->update($data);
    }
    public function getFollowNum($uid,$type){
        $model = $this->getORM();
        $cnt = $model->where("uid",$uid)->and("type",$type);
        return count($cnt);
    }

    public function getFollowerNum($uid){
        $model = $this->getORM();
        $cnt = $model->where("target",$uid)->and("type",1);
        return count($cnt);
    }

    public function isRepeat($uid, $type, $target) {
        $model = $this->getORM();

        $data = count($model -> where("uid", $uid) -> and("type", $type) -> and("target", $target));

        return $data != 0 ? true : false;
    }

    public function deleteByInfo($uid, $type, $target) {
        $model = $this->getORM();

        return $model -> where("uid", $uid) -> and("type", $type) -> and("target", $target) -> delete();
    }

    public function getFollowUserIds($uid, $start, $length) {
        $model = $this -> getORM();

        return $model -> select("target") -> where("uid", $uid) -> where("type", 1) -> limit($start, $length);
    }

    public function getFollowerUserIds($uid, $start, $length) {
        $model = $this -> getORM();

        return $model -> select("uid") -> where("target", $uid) -> where("type", 1) -> limit($start, $length);
    }
}