<?php
namespace App\Model\NeiHan618;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * nh618相册图片表 Model层
 * @createtime 18/9/14
 * @lastModify iimT
 */
class AlbumPic extends NotORM {
  protected function getTableName($id) {
    return 'albumpic';
  }

  public function add($data) {
    $model = $this -> getORM();

    return $model -> insert($data);
  }

  //批量插入
  public function addMul($dataArr) {
    $model = $this -> getORM();

    return $model -> insert_multi($dataArr);
  }

  /**
   * 根据相册id获取图片id
   */
  public function getPidsByAid($id) {
    $model = $this -> getORM();

    return $model -> select('pid') -> where("aid", $id);
  }
}