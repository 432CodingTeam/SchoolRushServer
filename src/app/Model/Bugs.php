<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 更新记录/BUG提交/建议 表 Model层
 * @createtime 18/8/11
 * @lastModify iimT
 */
class Bugs extends NotORM {
  protected function getTableName($id) {
    return 'bugs';
  }


  /**
   * 添加一条记录
   */
  public function add($data) {
    $model = $this -> getORM();

    return $model -> insert($data);
  }

  /**
   * 根据类型逐页获取
   * @author iimT
   * @param type 类型 1 更新记录 2 BUG提交 3 建议
   * @param start 开始索引
   * @param length 长度
   */
  public function getBylimit($type, $start, $length) {
    $model = $this -> getORM();

    return $model -> order('id ASC') -> where("type", $type) -> limit($start, $length);
  }

  /**
   * 不限类型逐页获取
   * @author iimT
   * @param start 开始索引
   * @param length 长度
   */
  public function getAllByLimit($start, $length) {
    $model = $this -> getORM();

    return $model -> order('id DESC') -> limit($start, $length);
  }
}