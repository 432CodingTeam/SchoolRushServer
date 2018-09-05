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
    public function getPassedAllById($id){
        $model=$this->getORM();
        $data=$model->where("id",$id)->where("status",1);
         return $data;
    }
    public function getById($id)
    {
        $model=$this->getORM();
        $data=$model->where("id",$id);
        return $data;
    }
    public function getByuid($uid)
    {
        $model=$this->getORM();
        $data=$model->where("uid",$uid);
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
    public function getPQIdByuid($uid)
    {
        $model=$this->getORM();
        $data= $model->where("uid",$uid)->and('status',1);
        //把data赋值成问题id
        return $data;
    }
    public function getPQuestionidByuid($uid)
    {
        $model=$this->getORM();
        $data= $model->where("uid",$uid)->and('status',1);
        //把data赋值成问题id
    }
    public function getUnPQIdByuid($uid)
    {
        $model=$this->getORM();
        return $model->where('uid',$uid)->and('status',0);
    }

    public function getTobeSolved($uid){
        $model = $this->getORM();

        return $model->where('uid',$uid)->and('status',0);
    }

    /**
     * 给待解决页面使用
     */
    public function getSolving($uid, $start, $length){
        $model = $this->getORM();

        return $model->where('uid', $uid)-> and('status',0) -> limit($start, $length);
    }

    /**
     * 给已解决页面使用
     */
    public function getSolved($uid, $start, $length){
        $model = $this->getORM();

        return $model->where('uid',$uid)->and('status',1) -> limit($start, $length);
    }

    public function getTobeSolvedNum($uid){
        $model = $this->getORM();

        return count($model->where('uid',$uid)->and('status',0));
    }

    public function getPassed($uid, $start = 0, $num = 100){
        $model = $this->getORM();

        return $model->where('uid',$uid)->and('status',1) -> limit($start, $num);
    }

    public function getPassedNum($uid){
        $model = $this->getORM();

        return count($model->where('uid',$uid)->and('status',1));
    }

    public function getUpdatePassedTen($qid){
        $model = $this->getORM();
        $data = $model->order("id DESC")->where('qid',$qid)->and('status',1)->limit(10);
        return $data;
    }

    public function getPassedStatus($uid, $qid) {
        $model = $this->getORM();
        $data = $model->where('qid',$qid)->and('uid',$uid) -> fetchOne();
        if(!@$data) return false;
        return $data['status'] == 1;
    }

    public function getUserAllId($uid) {
        $model = $this->getORM();
        return $model->select("qid")->where("uid", $uid)->fetchAll();
    }

    /**
     * 查看用户是否通过一组问题
     */
    public function checkUserPassQuestionArr($uid, $questionsArr) {
        $q_num = count($questionsArr);
        $model = $this->getORM();

        $count = count($model -> select("id") -> where("qid", $questionsArr) -> and("uid", $uid));
        return $q_num == $count ? true : false;
    }

    public function getUserPassed($uid, $qid) {
        $model = $this->getORM();

        $data = $model -> select("status") -> where("uid", $uid) -> where("qid", $qid)->fetchOne();
        if(!$data) return false;
        return $data["status"] == 1 ? true : false;
    }
    
}