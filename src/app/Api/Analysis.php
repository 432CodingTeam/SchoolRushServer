<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Analysis as AnalysisModel;
use App\Model\User as UserModel;
use App\Model\UserAgreeAnalysis as UserAgreeAnalysisModel;
/**
 * 用户赞同问题分析-接口类
 *
 * @author: iimT
 */

class Analysis extends Api {
	public function getRules() {
    return array(
      'addAgreeById' => array(
        'id' => array('name' => "id"),
        'uid' => array('name' => "uid"),
      ),
      'cancelAgreeById' => array(
        'id' => array('name' => "id"),
        'uid' => array('name' => "uid"),
      ),
      'getPageByQid' => array(
        'qid' => array('name' => 'qid'),
        'page' => array('name' => 'page'),
        'length' => array('name' => 'length'),
      ),
      'hasAgree' => array(
        'aid' => array('name' => 'aid'),
        'uid' => array('name' => 'uid'),
      ),
    );
  }

  /**
   * 增加点赞数
   */
  public function addAgreeById() {
    $model = new AnalysisModel();
    $userAgree = new UserAgreeAnalysisModel();

    //如果用户已经点过赞了，直接返回
    if($userAgree -> hasAgree($this -> uid, $this -> id)) return;

    //更新赞同数
    $newAnalysis = $model -> addAgreeById($this -> id);

    var_dump($newAnalysis);
    //插入用户点赞记录
    $data = array(
      'uid' => $this -> uid,
      'aid' => $newAnalysis['id']
    );

    $userAgree -> add($data);
    return $newAnalysis;
  }

  /**
   * 按页获取用户分析答案
   */
  public function getPageByQid() {
    $model = new AnalysisModel();
    $userModel = new UserModel();

    $length = $this -> length;
    $start  = ($this -> page - 1) * $length;

    $analysis   = $model -> getPageByQid($this -> qid, $start, $length);

    $data = array();
    while($row = $analysis -> fetch()) {
      $user = $userModel -> getById($row['uid']);

      $newData = array();
      
      $newData['uid']     = $user['id'];
      $newData['name']    = $user['name'];
      $newData['avatar']  = $user['avatar'];
      $newData['id']      = $row['id'];
      $newData['content'] = $row['content'];
      $newData['agree']   = $row['agree'];

      array_push($data, $newData);
    }

    return $data;
  }

  /**
   * 取消用户点赞
   */
  public function cancelAgreeById() {
    $model = new AnalysisModel();
    $userAgree = new UserAgreeAnalysisModel();

    $model -> subAgreeById($this -> id);

    $userAgree -> deleteByInfo($this-> uid, $this -> id);

    return true;
  }

  /**
   * 用户是否点过赞
   */
  public function hasAgree() {
    $userAgree = new UserAgreeAnalysisModel();
    
    return $userAgree -> hasAgree($this -> uid, $this -> aid);
  }
  
}