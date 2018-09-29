<?php
namespace App\Model\NeiHan618;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * nh618相册表 Model层
 * @createtime 18/9/5
 * @lastModify iimT
 */
class Album extends NotORM {
  protected function getTableName($id) {
    return 'album';
  }

  public function add($data) {
    $model = $this -> getORM();

    return $model -> insert($data);
  }

  public function getById($id) {
    $model = $this -> getORM();

    return $model -> where("id", $id);
  }

  public function getCount() {
    $model = $this -> getORM();

    return $model -> count("*");
  }

  public function getPage($start, $length) {
    $model = $this -> getORM();

    return $model -> select('*') -> limit($start, $length);
  }

  public function getPageRand($start, $length) {
    $model = $this -> getORM();

    return $model -> select('*') -> order("rand()") -> limit($start, $length);
  }

  public function addViewerById($id) {
    $model = $this -> getORM();

    return $model -> where('id', $id)->update(array('viewer' => new \NotORM_Literal("viewer + 1")));
  }

  public function addLikeById($id) {
    $model = $this -> getORM();

    return $model -> where('id', $id)->update(array('like' => new \NotORM_Literal("`like` + 1")));
  }

  public function getPageOrderByViewer($start, $length) {
    $model = $this -> getORM();

    return $model -> select('*') -> order("viewer DESC") -> limit($start, $length);
  }
}