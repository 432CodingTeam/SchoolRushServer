<?php

namespace App\Domain;
use App\Model\Groupactivity as GroupactivityModel;
use App\Model\Group as GroupModel;
use App\Model\Usertoq as UsertoqModel;
use App\Model\Usertogroup as UsertogroupModel;

class Groupactivity {
  /**
   * 获取活动信息 包括 title 完成数/总数 发布时间 所属小组名
   */
  public function getSiteActivityByPage($start, $num) {
    $model = new GroupactivityModel();
    $groupModel = new GroupModel();
    $data = $model -> getAllByLimie($start, $num);

    $result = array();
    while($activity = $data->fetch()) {
      $aid = $activity["id"];
      $gid = $activity["gid"];
      $completeStatus = $this->getActivityCompleteInfo($aid);
      $activity["pass"] = $completeStatus["pass"];
      $activity["total"] = $completeStatus["total"];
      $activity["group"] = $groupModel -> getById($gid);

      array_push($result, $activity);
    }

    return $result;
  }
  /**
   * 查看活动完成情况
   * @param id 活动id
   */
  public function getActivityCompleteInfo($id) {
    $usertoGroupModel = new UsertogroupModel();
    $usertoQModel     = new UsertoqModel();
    $model            = new GroupactivityModel();
    $activity         = $model -> getById($id);
    $groupUserIdArr = $usertoGroupModel -> getGroupUserArr($activity["gid"]);
    $questionsIdArr = explode(",", $activity["questionsID"]);

    $total = count($groupUserIdArr);
    $pass_num = 0;
    foreach($groupUserIdArr as $uid) {
        $is_pass = $usertoQModel -> checkUserPassQuestionArr($uid, $questionsIdArr);
        if($is_pass) {
            $pass_num = $pass_num + 1;
        }
    }
    return array("pass" => $pass_num, "total" => $total);
  }
}