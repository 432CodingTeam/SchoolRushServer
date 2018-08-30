<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 用户-评分关系表 Model层
 * @created 18/8/28
 * @lastModify iimT
 */
class UserMark extends NotORM {

    protected function getTableName($id) {
        return 'usermark';
    }

    /**
     * 添加一条记录
     * @param $data Array 需要包含qid 与uid
     */
    public function add($data) {
      $model = $this -> getORM();

      return $model -> insert($data);
    }

    /**
     * 根据id获取一条数据
     * @param $id ..
     */
    public function getById($id) {
      $model = $this -> getORM();

      return $model -> where("id", $id);
    }

    /**
     * 根据问题id获取记录
     */
    public function getByqId($qid) {
      $model = $this -> getORM();

      return $model -> where("qid", $qid);
    }

    /**
     * 根据用户id获取记录
     */
    public function getByUid($uid) {
      $model = $this -> getORM();

      return $model -> where("uid", $uid);
    }

    /**
     * 查询是否存在uid 与qid 对应的记录
     * @param uid 用户id
     * @param qid 问题id
     * @return 查询结果
     */
    public function hasMarked($uid, $qid) {
      $model = $this -> getORM();

      return $model -> where("uid", $uid) -> and("qid", $qid);
    }
}