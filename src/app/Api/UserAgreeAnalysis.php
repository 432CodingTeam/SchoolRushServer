<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\User as UserModel;
use App\Model\Question as QuestionModel;
use App\Model\Usertoq as UsertoqModel;
use App\Model\Major as MajorModel;
use App\Model\Campus as CampusModel;
use App\Model\Token as TokenModel;
use App\Domain\Token as TokenDomain;
use App\Common\Upload as MyUpload;
use App\Common\GD;
/**
 * 用户赞同问题分析-接口类
 *
 * @author: iimT
 */

class UserAgreeAnalysis extends Api {
	public function getRules() {
    return array(
      'add' => array(
          'name' => array('name' => "name"),
      ),
    );
  }
  
}