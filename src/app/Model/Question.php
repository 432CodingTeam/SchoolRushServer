<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Question extends NotORM {

    protected function getTableName($id) {
        return 'question';
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
    public function getQByuid($uid)
    {
        $model=$this->getORM();
        $data=$model->where("uid",$uid);
        return $data;
    }
    public function getQBykey()
    {
        $model=$this->getORM();
        $data=$model->where("q",$uid);
        return $data;
    }
    public function getByLimit($start, $num) {
        $model = $this->getORM();
        $data = $model->limit($start, $num);
        
        return $data;
    }

    public function getTotalNumByFilter($filter) {
        $model = $this->getORM();
        $data = $model->where($filter);
        return count($data);
    }
    
    public function getFilterByLimit($filter, $start, $num) {
        $model = $this->getORM();
        
        return $model->where($filter)->limit($start, $num);
    }

    public function getTypeById($id) {
        $model = $this->getORM();

        return $model->select('id, type')->where("id", $id)->fetchOne();
    }

    public function getByExceptId($arr) {
        $data = array();
        foreach($arr as $id) {
            array_push($data, (int)$id["qid"]);
        }
        $model = $this->getORM();

        return $model->order('id DESC')->where("NOT id", $data);
    }
    
    public function regularReplaceP($str){

        return preg_replace('/!\[.*\]\((.+)\)/',"[图片]",$str);
    }
    public function regularReplaceA($str){

        return preg_replace('/\[.*\]\((.+)\)/',"[链接]",$str);
    }

}