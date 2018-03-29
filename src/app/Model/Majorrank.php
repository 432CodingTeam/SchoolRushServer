<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Majorrank extends NotORM {

    protected function getTableName($id) {
        return 'majorrank';
    }


}