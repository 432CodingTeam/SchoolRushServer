<?php

namespace App\Domain;
use App\Model\Question as QuestionModel;
use App\Common\Utils;
use App\Model\User as UserModel;
use App\Model\Major as MajorModel;
use App\Model\Label as LabelModel;
use App\Model\QuestionLabel as QuestionLabelModel;

class Question {
  
  /**
   * 按照传入的关键词数组搜索问题
   * @param keyArr 关键词数组
   * @return 搜索结果 [Array]
   */
  public function searchByKeyArr($keyArr) {
    $model = new QuestionModel();
    $Utils = new Utils();
    $result = array();
    foreach($keyArr as $key) {
      $res = $model->searchByKey($key);
      while($row = $res->fetch()) {
        $id = $row["id"];
        if(!array_key_exists($id, $result)) {
          $result[$id] = 1;
          continue;
        }
        $result[$id]++;
      }
    }
    $result = $Utils->quickSortSearchRes($result);
    return $result;
  }

  /**
   * 取到的问题列表，补充所有问题卡片的信息
   * @author iimT
   * @param queryRes
   */
  public function getQCardInfo($queryRes) {
    $model = new QuestionModel();
    $res = array();
    while($row = $queryRes->fetch()) {
      $user                    = new UserModel();
      $major                   = new majorModel();
      $labelModel              = new LabelModel();
      $questionLabelModel      = new QuestionLabelModel();

      $que = $row["q"];//开始的问题内容
      $q   = $model -> regularReplaceP($que); //图片格式化之后
      $q   = $model -> regularReplaceA($q);   //链接格式化之后
      $q   = $model -> regularReplaceExp($q); //表达式格式化
      $q   = $model -> regularReplaceCode($q);//代码格式化

      $arr                     = $row;
      //问题主体截取，防止UTF8字段截取出现乱码使用mb_substr()
      $arr["q"]                = mb_substr($q,0,25,"UTF8");
      $arr["passedrate"]       = $row["challenges"] == 0 ? "0%" : 100*($row["passed"]/$row["challenges"])."%";
      $user                    = $user -> getById($row["uid"]);
      $arr["username"]         = $user["name"];
      $arr["useravatar"]       = $user["avatar"];
      $major1                  = $major -> getById($row["majorID"]);
      $arr["majorName"]        = $major1["name"];
      $name                    = $major -> getById($major1["parent"]);
      $arr["majorParentName"]  = $name["name"];
      //TODO: 添加labels
      $arr["labels"]           = $questionLabelModel -> getQuestionLabelsId($arr["id"]);
      
      $label_arr               = array();
      foreach( $arr["labels"] as $a_label) {
        $labelID        = $a_label["lid"];
        $label          = $labelModel -> getById($labelID);
        $_label         = array();
        $_label["id"]   = $label["id"];
        $_label["name"] = $label["name"];

        array_push($label_arr, $_label);
      }
      $arr["labelsInfo"]  = $label_arr;
      array_push($res, $arr);
    }
    return $res;
  }

  public function getQuestionsByLabel($lid, $start, $length) {
    $model = new QuestionModel();
    $questionLabelModel = new QuestionLabelModel();

    $Questions = $questionLabelModel -> getPageQIdByLid($lid, $start, $length);
    $Qids      = array();
    while($row = $Questions -> fetch()) {
      array_push($Qids, $row["qid"]);
    }
    
    return $model -> getByIdArr($Qids);
  }
}