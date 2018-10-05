<?php
namespace App\Api\NeiHan618;

use PhalApi\Api;
use App\Model\NeiHan618\Album as AlbumModel;
use App\Model\NeiHan618\Pic as PicModel;
use App\Model\NeiHan618\AlbumPic as AlbumPicModel;

/**
 * nh618-相册-图片接口类
 * @author dogstar 20170612
 */

class AlbumPic extends Api {

  public function getRules() {
    return array(
      'add' => array(
        'aid' => array('name' => 'aid', 'require' => true),
        'pid' => array('name' => 'pid', 'require' => true),
      ),
      'addMul' => array(
        'aid' => array('name' => 'aid', 'require' => true),
        'pids' => array('name' => 'pids', 'require' => true),
      ),
    );
  }

  /**
   * 添加一条相册-图片记录
   */
  public function add() {
    $model = new AlbumPicModel();
    $data  = array(
      'aid' => $this -> aid,
      'pid' => $this -> pid,
    );
    return $model -> add($data);
  }

  /**
   * 批量添加相册-图片记录
   * @param aid 相册id int
   * @param pid 多个图片id array 
   */
  public function addMul() {
    $model = new AlbumPicModel();
    $data = array();
    $pids  = json_decode($this -> pids);
    foreach($pids as $pid) {
      array_push($data, array(
        'aid' => $this -> aid,
        'pid' => $pid
      ));
    }

    return $model -> addMul($data);
  }
}