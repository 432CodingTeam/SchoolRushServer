<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Groupactivity extends NotORM {

    protected function getTableName($id) {
        return 'groupactivity';
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

    public function getBygid($gid) {
        $model = $this->getORM();

        $data = $model->where("gid",$gid);
        $data = $data->fetchall();
        return $data;
    }
    public function getIdByName($name)
    {
        $model=$this->getORM();
        $data=$model->where("name",$name);
        //var_dump($data);
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
    public function getByLimit($start, $num, $gid) {
        $model = $this->getORM();
        $data = $model->where("gid",$gid)->limit($start, $num);
        
        return $data;
    }

    public function getAllByLimie($start, $num) {
        $model = $this->getORM();

        return $model -> select("id, title, questionsID, gid, starttime") -> order("starttime DESC") -> limit($start, $num);
    }
    
    /**
     * 根据数组中的id选择
     */
    public function getByIdArrLatest($idArr, $start, $num) {
        $model = $this->getORM();

        return $model -> order("starttime DESC") -> where("gid", $idArr) -> limit($start, $num);
    }
}