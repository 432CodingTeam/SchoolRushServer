<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Majorrank extends NotORM {

    protected function getTableName($id) {
        return 'majorrank';
    }
    public function getMajorTopten($majorID)
    {
        $model=$this->getORM()->where("id",$majorID)->fetchOne();
        $arr=explode(",",$model["list"]);
        return $arr;
    }

}