<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 群-专业关系表 Model层
 * @createtime 18/8/12
 * @lastModify iimT
 */
class GroupMajor extends NotORM {
  protected function getTableName($id) {
    return 'groupmajor';
  }

  /**
   * 添加一条记录
   * @param data 数据
   * @return 插入结果 完整数据
   */
  public function add($data) {
    $model = $this -> getORM();

    return $model -> insert($data);
  }

}