<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Campusmajorpassed extends NotORM {

    protected function getTableName($id) {
        return 'campusmajorpassed';
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
    public function updateById($id, $data){
        $model = $this->getORM();
        return $model->where("id",$id)->update($data);
    }
    public function getBymajorID($majorID)
    {
        $model=$this->getORM();
        return $model->where("majorID",$majorID);
    }
    public function getadayBymajorID($majorID)

    {
        $model=$this->getORM();
        $model1=$model->where("majorID",$majorID)->fetchAll();
        $arr=array();
        $num=array();
        for($i=0;$i<sizeof($model1);$i++)
        {
            $arr[$i]["campusID"]=$model1[$i]['campusID'];
            $arr[$i]["aday"]=$model1[$i]['aday'];
            $num[$i]=$model1[$i]['aday'];
        }
        array_multisort($num,SORT_DESC,$arr);
        return $arr;
    }
    public function getaweekBymajorID($majorID)

    {
        $model=$this->getORM();
        $model1=$model->where("majorID",$majorID)->fetchAll();
        $arr=array();
        $num=array();
        for($i=0;$i<sizeof($model1);$i++)
        {
            $arr[$i]["campusID"]=$model1[$i]['campusID'];
            $arr[$i]["aweek"]=$model1[$i]['aweek'];
            $num[$i]=$model1[$i]['aweek'];
        }
        array_multisort($num,SORT_DESC,$arr);
        return $arr;
    }
    public function getamonthBymajorID($majorID)

    {
        $model=$this->getORM();
        $model1=$model->where("majorID",$majorID)->fetchAll();
        $arr=array();
        $num=array();
        for($i=0;$i<sizeof($model1);$i++)
        {
            $arr[$i]["campusID"]=$model1[$i]['campusID'];
            $arr[$i]["amonth"]=$model1[$i]['amonth'];
            $num[$i]=$model1[$i]['amonth'];
        }
        array_multisort($num,SORT_DESC,$arr);
        return $arr;
    }
    public function getDayPassed()
    {
        $model=$this->getORM();
        $model1=$model->fetchall();
        foreach($model1 as $m)
        {
            $sum+=$m["aday"];
        }
        return $sum;
    }
    //添加
}