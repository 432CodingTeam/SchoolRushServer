<?php
namespace App\Api;

use PhalApi\Api;
use App\Common\Upload as MyUpload;
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
        ),
        "base64UploadUPY" => array(
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
      $upload = new MyUpload();
      $rs['code'] = 1;
      $rs['url'] = $upload -> uploadToQNY($imgPath, $name . $ext);
      return $rs;
  }

  public function base64UploadQNY() {
        $GD = new GD();
        return $GD->base64Upload($this -> img, md5($this -> name . $_SERVER['REQUEST_TIME']));
  }


  /**
   * base64上传到又拍云 【测试版本】
   * @return 图片地址
   */
  public function base64UploadUPY() {
        $GD               = new GD();
        $upload           = new MyUpload();

        $image            = $GD -> uploadToLocal($this -> img, md5($this -> name . $_SERVER['REQUEST_TIME']));
        
        $bucketName       = "iimtimg";           // 上传的服务
        $operatorName     = "iimt";       // 操作员名称
        $operatorPwd      = "ATyangguang";         // 操作员密码
        $picSize          = filesize($image); // 被上传文件的大小
        /*文件在upyun上的存储路径*/ 
        $serverPath       = $image['fileName'];
        $url              = "/$bucketName/$serverPath";
        /* 生成签名 */
        $date             = gmdate('D, d M Y H:i:s \G\M\T');
        $sign             = md5("PUT&{$url}&{$data}&{$imgSize}&".md5($operatorPwd));

        $ch               = curl_init('http://v0.api.upyun.com'.$url); // 基本域名为智能选择线路(电信/联通/移动)
        
        $header           = array(
            'Expect:',
            'Date:'.$date,
            'Authorization: UpYun $operatorName:'.$sign
        );
        curl_setopt($ch, CURLOPT_INFILE,$header);
        /* 上传图片到upyun */
        curl_setopt($ch, CURLOPT_PUT, true);
        $fn               = fopen($image,'rb');
        curl_setopt($ch, CURLOPT_INFILE, $fn);
        curl_setopt($ch, CURLOPT_INFILESIZE, $picSize);
        curl_setopt($ch,CURLOPT_TIMEOUT,120);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // 设置以文件的方式返回数据，否则会自动输出返回结果
        /* 回应 */
        $result           = curl_exec($ch);
        if(curl_getinfo($ch,CURLINFO_HTTP_CODE) == 200){
            // return "";
            return $upload -> uploadToUPY($url);
        }else{
            $errorMessage = sprintf("UpYun API ERROR:%s",$result);
            return $errorMessage;
        }
        curl_close($ch);
    }

}
