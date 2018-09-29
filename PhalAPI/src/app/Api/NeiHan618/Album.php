<?php
namespace App\Api\NeiHan618;

use PhalApi\Api;
use App\Model\NeiHan618\Album as AlbumModel;
use App\Model\NeiHan618\Pic as PicModel;
use App\Model\NeiHan618\AlbumPic as AlbumPicModel;

/**
 * nh618-相册接口类
 * @author dogstar 20170612
 */

class Album extends Api {

  public function getRules() {
    return array(
      'add' => array(
        'title' => array('name' => 'title', 'require' => true),
        'cover' => array('name' => 'cover', 'require' => true),
      ),
      'getPics' => array(
        'id' => array('name' => 'id', 'require' => true),
      ),
      'getCardInfo' => array(
        'id' => array('name' => 'id', 'require' => true),
      ),
      'getPage' => array(
        'page' => array('name' => 'page', 'require' => true, 'default' => 1),
        'length' => array('name' => 'length', 'require' => true, 'default' => 12),
      ),
      'addViewById' => array(
        'id' => array('name' => 'id', 'require' => true),
      ),
      'addLikeById' => array(
        'id' => array('name' => 'id', 'require' => true),
      ),
      'getPageByViewer' => array(
        'page' => array('name' => 'page', 'require' => true, 'default' => 1),
        'length' => array('name' => 'length', 'require' => true, 'default' => 12),
      ),
    );
  }

  /**
   * 添加一个相册
   */
  public function add() {
    $data = array(
      'title' => $this -> title,
      'cover' => $this -> cover
    );
    $model = new AlbumModel();
    
    return $model -> add($data);
  }

  /**
   * 根据id获取该相册的图片
   */
  public function getPics() {
    $albumPicModel = new AlbumPicModel();
    $picModel      = new PicModel();
    $pid           = array();
    $result        = $albumPicModel -> getPidsByAid($this -> id);

    while($row = $result -> fetch()) {
      array_push($pid, $row['pid']);
    }
    return $picModel -> getById($pid);
  }

  /**
   * 根据id获取相册的卡片信息
   */
  public function getCardInfo() {
    $picModel = new PicModel();
    $model    = new AlbumModel();
    $data     = $model -> getById($this -> id) -> fetchOne();
    $cover    = $picModel -> getById($data['cover']) -> fetchOne();

    $data['coverSrc'] = $cover['src'];

    return $data;
  }

  public function getPage() {
    $length     = $this -> length;
    $start      = ($this -> page - 1) * $length;
    $albumModel = new AlbumModel();
    //$albums     = $albumModel -> getPage($start, $length);
    $albums     = $albumModel -> getPageRand($start, $length);
    $data       = array();

    while($row = $albums -> fetch()) {
      $album          = $row;
      $picModel       = new PicModel();
      $cover          = $picModel -> getById($row['cover']) -> fetchOne();
      $album['cover'] = $cover['src'];

      array_push($data, $album);
    }

    return $data;
  }

  /**
   * 获取相册数量
   */
  public function getCount() {
    $albumModel = new AlbumModel();

    return $albumModel -> getCount();
  }

  public function addViewById() {
    $albumModel = new AlbumModel();

    return $albumModel -> addViewerById($this -> id);
  }

  public function addLikeById() {
    $albumModel = new AlbumModel();

    return $albumModel -> addLikeById($this -> id);
  }

  public function getPageByViewer() {
    $length     = $this -> length;
    $start      = ($this -> page - 1) * $length;
    $albumModel = new AlbumModel();
    $albums     = $albumModel -> getPageOrderByViewer($start, $length);
    $data       = array();

    while($row = $albums -> fetch()) {
      $album          = $row;
      $picModel       = new PicModel();
      $cover          = $picModel -> getById($row['cover']) -> fetchOne();
      $album['cover'] = $cover['src'];

      array_push($data, $album);
    }

    return $data;

  }
}