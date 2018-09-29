<?php
namespace App\Model\NeiHan618;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * nh618图片表 Model层
 * @createtime 18/9/5
 * @lastModify iimT
 */
class Pic extends NotORM {
  protected function getTableName($id) {
    return 'pic';
  }

  public function add($data) {
    $model = $this -> getORM();

    return $model -> insert($data);
  }

  /**
   * 根据id获取图片信息
   * @param id id int/array
   */
  public function getById($id) {
    $model = $this -> getORM();

    return $model -> where("id", $id);
  }

  public function getCount() {
    $model = $this -> getORM();

    return $model -> count("*");
  }

  public function addLikeById($id) {
    $model = $this -> getORM();

    return $model -> where('id', $id)->update(array('like' => new \NotORM_Literal("`like` + 1")));
  }
}