<?php
namespace App\Api;

use PhalApi\Api;
use App\Common\Upload as QNUpload;
use App\Common\GD;

/**
 * 文件上传服务类
 *
 * @author: iimT
 */

class Upload extends Api {

	public function getRules() {
    return array(
        'uploadImg' => array(
            'file' => array(
                'name' => 'file',        // 客户端上传的文件字段
                'type' => 'file', 
                'require' => true, 
                'max' => 2097152,        // 最大允许上传2M = 2 * 1024 * 1024, 
                'range' => array('image/jpeg', 'image/png'),  // 允许的文件格式
                'ext' => 'jpeg,jpg,png', // 允许的文件扩展名 
                'desc' => '待上传的图片文件',
            ),
        ),
        "base64UploadQNY" => array(
            "img" => array("name" => "img"),
            "name" => array("name" => "name"),
        )
    );
	}
	
	/**
     * 图片文件上传
     * @desc 只能上传单个图片文件
     * @return int code 操作状态码，0成功，1失败
     * @return url string 成功上传时返回的图片URL
     */
    public function uploadImg() {
      $rs = array('code' => 0, 'url' => '');
      var_dump($this->file);
      $tmpName = $this->file['tmp_name'];

      $name = md5($this->file['name'] . $_SERVER['REQUEST_TIME']);
      $ext = strrchr($this->file['name'], '.');
      $uploadFolder = sprintf('%s/public/uploads/', API_ROOT);
      if (!is_dir($uploadFolder)) {
          mkdir($uploadFolder, 0777);
      }

      $imgPath = $uploadFolder .  $name . $ext;
      if (!move_uploaded_file($tmpName, $imgPath)) {
        $rs["msg"] = "移动文件失败！";
        return $rs;
      }
      $upload = new QNUpload();
      $rs['code'] = 1;
      $rs['url'] = $upload -> uploadToQNY($imgPath, $name . $ext);
      return $rs;
  }

  public function base64UploadQNY() {
      $GD = new GD();
      return $GD->base64Upload($this->img, md5($this->name . $_SERVER['REQUEST_TIME']));
  }

}
