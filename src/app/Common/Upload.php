<?php
namespace App\Common;

require_once "../vendor/php-sdk/autoload.php";
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Cdn\CdnManager;

class Upload {
    private $access_key = "CovGkjm--Qq7QrozUQ04Ds874KnmETGRzx6OfDOL";
    private $secret_key = "FEC2IJQ1WsWJpQTFHLtp7ODwk4WKn9ppkqrEL0f1";
    private $bucket_name = "schoolrush";
    private $cloudDomain = "http://p6a87gauo.bkt.clouddn.com/";
    
    //上传到七牛云 返回外链地址
    public function uploadToQNY($filePath, $imgName) {
        $Auth = new Auth($this->access_key, $this->secret_key);

        $expires = 3600;
        $policy = null;
        $fileToOverwrite = $imgName;  //要覆盖的文件名

        $token = $Auth->uploadToken($this->bucket_name, $fileToOverwrite, $expires, $policy, true);

        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $imgName, $filePath);

        if ($err !== NULL)
            return array("res"=>false, "error"=>$err); //上传失败
        
        unlink($filePath);//删除本地文件
        $url = $this->cloudDomain.$ret["key"];
        $this->refreshURL($Auth,$url); //刷新外链

        return $url;  //返回上传之后的外链
    }

    //刷新七牛云外链
    public function refreshURL($Auth,$url) {
        $urls = array($url);
        $cdnManager = new CdnManager($Auth);
        
        list($refreshResult, $refreshErr) = $cdnManager->refreshUrls($urls);
        
        if($refreshErr !== NULL)
            return false; //刷新失败
        return $refreshResult; //返回刷新成功的结果
    }
}