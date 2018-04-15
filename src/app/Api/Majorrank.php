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
            'getMajorRank'=>array(
                'majorID'=>array('name'=>'majorID'),
                'campusID'=>array('name'=>'campusID'),
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

    /**
     * 获取学校排名
     * @desc 获取学校在某个专业的排名
     * @author lxx
     * @param int campusID 学校id
     * @param int majorID 专业id
     * @return int data 学校排名（前十）
     */
    public function getMajorRank()
    {
        $model=new MajorrankModel();
        $model1=$model->getMajorTopten($this->majorID);
        $d=0;
        foreach($model1 as $m)
        {
            $d++;
            if($this->campusID==$m)
            return $d;
        }
    }
}
