<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 用户-问题分析赞同 表 Model层
 * @createtime 18/9/5
 * @lastModify iimT
 */
class UserAgreeAnalysis extends NotORM {
  protected function getTableName($id) {
    return 'user_agree_analysis';
  }

  public function add($data) {
    $model = $this -> getORM();

    return $model -> insert($data);
  }

  /**
   * 根据uid 和qid 删除记录
   */
  public function deleteByInfo($uid, $aid) {
    $model = $this -> getORM();

    return $model -> where('uid', $uid) -> and('aid', $aid) -> delete();
  }

  /**
   * 查找指定用户是否对指定问题分析点赞过
   */
  public function hasAgree($uid, $aid) {
    $model = $this -> getORM();

    $res   = $model -> where('uid', $uid) -> and('aid', $aid) -> fetchOne();
    
    if(!@$res['id']) return false;
    return true; 
  }
}