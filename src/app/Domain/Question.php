<?php

namespace App\Domain;
use App\Model\Question as QuestionModel;
use App\Common\Utils;
use App\Model\User as UserModel;
use App\Model\Major as MajorModel;
use App\Model\Label as LabelModel;

class Question {
  
  
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
   */
  public function getQCardInfo($queryRes) {
    $model = new QuestionModel();
    $res = array();
    while($row = $queryRes->fetch()) {
      $user                    = new UserModel();
      $major                   = new majorModel();
      $labelModel              = new LabelModel();

      $que = $row["q"];//开始的问题内容
      $q  = $model->regularReplaceP($que); //图片匹配后的问题内容
      $q  = $model->regularReplaceA($q);  //链接匹配后的问题内容
      $q  = $model->regularReplaceExp($q); //将表达式去掉
      $q  = $model->regularReplaceCode($q);

      $arr                     = $row;
      $arr["q"]                = mb_substr($q,0,25,"UTF8");
      $arr["passedrate"]       = $row["challenges"] == 0 ? "0%" : 100*($row["passed"]/$row["challenges"])."%";
      $user                    = $user->getById($row["uid"]);
      $arr["username"]         = $user["name"];
      $arr["useravatar"]       = $user["avatar"];
      $major1                  = $major->getById($row["majorID"]);
      $arr["majorName"]        = $major1["name"];
      $name                    = $major->getById($major1["parent"]);
      $arr["majorParentName"]  = $name["name"];
      $arr["labels"]           = $row["labels"] == null ? array() : explode(",",$row["labels"]);
      $label_arr = array();
      foreach( $arr["labels"] as $labelID) {
          $label = $labelModel -> getById($labelID);
          $_lebel = array();
          $_lebel["id"] = $label["id"];
          $_lebel["name"] = $label["name"];
          array_push($label_arr, $_lebel);
      }
      $arr["labelsInfo"]       = $label_arr;
      array_push($res, $arr);
    }
    return $res;
  }
}