<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\GroupMajor as GroupMajorModel;

/**
 * 小组-所属专业关系表 API层
 * @createtime 18/8/12
 * @author: iimT
 */
class GroupMajor extends Api {
  public function getRules() {
    return array(
      'add'           => array(
        'uid'           => array('name' => 'uid',     'require' => true),
      ),
    );
  }

}