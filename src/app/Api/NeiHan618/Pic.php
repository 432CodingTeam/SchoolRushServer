<?php
namespace App\Api\NeiHan618;

use PhalApi\Api;
use App\Model\NeiHan618\Album as AlbumModel;
use App\Model\NeiHan618\Pic as PicModel;
use App\Model\NeiHan618\AlbumPic as AlbumPicModel;

/**
 * nh618-图片接口类
 * @author iimT
 */

class Pic extends Api {

  public function getRules() {
    return array(
      'add' => array(
        'src' => array('name' => 'src', 'require' => true),
      ),
      'addLikeById' => array(
        'id' => array('name' => 'id', 'require' => true),
      )
    );
  }

  /**
   * 添加一条图片记录
   */
  public function add() {
    $data  = array('src' => $this -> src);
    $model = new PicModel();
    return $model -> add($data);
  }

  /**
   * 获取图片数量
   */
  public function getCount() {
    $albumModel = new PicModel();

    return $albumModel -> getCount();
  }

  public function addLikeById() {
    $albumModel = new PicModel();

    return $albumModel -> addLikeById($this -> id);
  }

}