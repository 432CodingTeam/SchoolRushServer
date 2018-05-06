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
    public function getByIdArray($id) {
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

    public function getByExceptId($arr, $start, $num) {
        $data = array();
        foreach($arr as $id) {
            array_push($data, (int)$id["qid"]);
        }
        $model = $this->getORM();

        return $model->order('id DESC')->where("NOT id", $data)->limit($start, $num);
    }
    
    public function regularReplaceP($str){
<<<<<<< HEAD
<<<<<<< HEAD
        
        return preg_replace('/!\[.*\]\((.+)\)/',"[图片]",$str);
=======
=======
>>>>>>> 45a4c0acdb4a270286ecaa0210b6671711842e27
        $res = preg_replace('/!\[.*\]\((.+)\)/','[图片]',$str);
        if(!$res) return $str;

        return $res;
<<<<<<< HEAD
>>>>>>> 95216cf8bfc9a02bf09c98be6df65e8e7645b37e
=======
>>>>>>> 45a4c0acdb4a270286ecaa0210b6671711842e27
    }
    public function regularReplaceA($str){
        $res =  preg_replace('/\[.*\]\((.+)\)/','[链接]',$str);
        if(!$res) return $str;

        return $res;
    }

    public function regularReplaceExp($str) {
        $res = preg_replace('/\$\$[\s\S]*?\$\$/','[表达式]', $str);
        if(!$res) return $str;
        return $res;
    }

    public function regularReplaceCode($str) {
        $res = preg_replace('/```[\s\S]*?```/','[代码]', $str);
        if(!$res) return $str;
        
        return $res;
    }

}