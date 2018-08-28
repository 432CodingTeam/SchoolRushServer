<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 问题-标签关系表 Model层
 * @createtime 18/8/28
 * @lastModify iimT
 */
class QuestionLabel extends NotORM {

  protected function getTableName($id) {
    return 'questionlabel';
  }


  /**
   * 根据问题id获取该问题的标签
   * @param qid 问题id
   * @return 查询结果 [Array]
   */
  public function getQuestionLabelsId($qid) {
    $model = $this -> getORM();
    return $model -> select('lid') -> where('qid', $qid);
  }
  
  /**
   * 按页根据标签id获取问题id
   * @param lid 标签id
   * @param start 分页开始标记
   * @param length 页容量
   */
  public function getPageQIdByLid($lid, $start, $length) {
    $model = $this -> getORM();

    return $model -> select("qid") -> where("lid", $lid) -> limit($start, $length);
  }
}