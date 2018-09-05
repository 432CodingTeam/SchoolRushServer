<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 问题分析 表 Model层
 * @createtime 18/9/5
 * @lastModify iimT
 */
class Analysis extends NotORM {
  protected function getTableName($id) {
    return 'analysis';
  }

  public function add($data) {
    $model = $this -> getORM();

    return $model -> insert($data);
  }

  public function getPageByQid($qid, $start, $length) {
    $model = $this -> getORM();
    //按照赞同数降序排列
    return $model -> where('qid', $qid) -> order("agree DESC") -> limit($start, $length);
  }

  /**
   * 增加赞同
   */
  public function addAgreeById($id) {
    $model = $this -> getORM();

    $model -> where('id', $id) -> update(array('agree' => new \NotORM_Literal("agree + 1")));

    return $this -> getORM() -> where('id', $id) -> fetchOne();
  }

  /**
   * 减少赞同
   */
  public function subAgreeById($id) {
    $model = $this -> getORM();

    return $model -> where('id', $id) -> update(array('agree' => new \NotORM_Literal("agree - 1")));
  }
}