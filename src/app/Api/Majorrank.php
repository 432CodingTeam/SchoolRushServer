<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Majorrank as MajorrankModel;
/**
 * 专业接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Majorrank extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
        );
	}
	
	/**
	 * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
	 * @return string title 标题
	 * @return string content 内容
	 * @return string version 版本，格式：X.X.X
	 * @return int time 当前时间戳
	 */
	public function index() {
        return array(
            'title' => 'Hello ' . $this->username,
            'version' => PHALAPI_VERSION,
            'time' => $_SERVER['REQUEST_TIME'],
        );
    }


}
