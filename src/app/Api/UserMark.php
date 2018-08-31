<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\UserMark as UserMarkModel;

/**
 * 用户评分接口API类
 * @createtime 18/8/28
 * @author: iimT
 */
class UserMark extends Api {
  public function getRules() {
    return array(
      'add'           => array(
        'uid'           => array('name' => 'uid',     'require' => true),
        'qid'           => array('name' => 'qid',     'require' => true),
        'mark'           => array('name' => 'mark',   'require' => true),
      ),
      'hasMarked'           => array(
        'uid'           => array('name' => 'uid',     'require' => true),
        'qid'           => array('name' => 'qid',     'require' => true),
      ),
    );
  }


  /**
   * 添加一条记录
   * @param uid 用户id
   * @param qid 问题id
   * @param mark 评分
   * @return 添加的数据
   */
  public function add() {
    $model         = new UserMarkModel();
    $data          = array();
    $data["uid"]   = $this -> uid;
    $data["qid"]   = $this -> qid;
    $data["mark"]  = $this -> mark;

    return $model -> add($data);
  }

  /**
   * 查询是否存在指定uid与qid的记录
   * @param uid 用户id
   * @param qid 问题id
   * @return bool 是否存在
   */
  public function hasMarked() {
    $model        = new UserMarkModel();
    $result       = $model -> hasMarked($this -> uid, $this -> qid) -> fetchOne();

    if($result != null)
      return true;

    return false;
  }
}