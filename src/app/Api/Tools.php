<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Campus as CampusModel;
use App\Model\Token as TokenModel;
use App\Domain\Token as TokenDomain;
use App\Common\Upload as MyUpload;
use App\Common\GD as codeGD;
/**
 * 用户接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Tools extends Api {
    public function getRules() {
        return array(
            'add' => array()
        );
    }

    public function getCaptcha(){
        $code = new codeGD;
        $image = $code->getUserVerificationCodeRandom(4);

        return $image;
    }


}