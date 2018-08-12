<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Bugs as BugsModel;
use App\Model\User as UserModel;

/**
 * 更新/BUG/建议接口API类
 * @createtime 18/8/11
 * @author: iimT
 */
class Bugs extends Api {
  public function getRules() {
    return array(
      'add'           => array(
        'uid'           => array('name' => 'uid',     'require' => true),
        'content'       => array('name' => 'content', 'require' => true),
        'type'          => array('name' => 'type',    'require' => true)
      ),
      'getAllByPage'  => array(
        'page'          => array('name' => 'page',    'require' => true, 'default' => 1),
        'length'        => array('name' => 'length',  'require' => true, 'default' => 10)
      ),
    );
  }
  
  /**
   * 添加一条记录
   * @param uid 用户id
   * @param content 内容
   * @param type 类型
   */
  public function add() {
    $model = new BugsModel();
    $data  = array(
      'uid'     => $this -> uid,
      'content' => $this -> content,
      'type'    => $this -> type
    );
    return $model -> add($data);
  }

  /**
   * 全部逐页获取
   * @author iimT
   * @param length 每页长度
   * @param page   页数
   * @return array 
   */
  public function getAllByPage() {
    $model      = new BugsModel();
    $userModel  = new UserModel();
    $length     = $this -> length;
    $start      = ($this -> page - 1) * $length;
    $res        =  $model -> getAllByLimit($start, $length);
    $result     = array();

    while($row = $res -> fetch()) {
      $uid            = $row['uid'];
      $user         = $userModel -> getAvatarByUid($uid);
      $row['avatar']  = $user['avatar'];
      $userModel      = new UserModel();

      array_push($result, $row);
    }
    return $result;
  }
}