<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Userliveness as UserlivenessModel;
/**
 * 用户接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Userliveness extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'time' => array("name" => "time"),
                'uid'=>array('name'=>"uid"),
                'answer'=>array('name'=>"answer"),
                'quiz'=>array('name'=>"quiz"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name' => 'id'),
                'time' => array('name' => "time"),
                'uid'=>array('name'=>"uid"),
                'answer'=>array('name'=>"answer"),
                'quiz'=>array('name'=>"quiz"),
            ),
            'getByTime'=>array(
                'starttime'=>array('name'=>'starttime'),
                'endtime'=>array('name'=>'endtime'),
            )
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
     * 获取所有用户活跃记录
     * @desc 获取所有用户活跃记录信息
     * @return array data 所有的用户记录
     * 
     */
    public function getAll() {
        $model = new UserlivenessModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取
     * @desc 根据id获取记录
     * @param int id 要获取的记录id
     * @return data data 改id指定的记录信息
     */

    public function getById() {
        $model = new UserlivenessModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除记录信息
     * @param int id 要删除的记录id
     * @return int data 要删除的记录id
     */
    public function deleteById()
    {
        $model = new UserlivenessModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加用户
     * @desc 增加记录信息 
     * @param date time 增加的记录时间
     * @param int uid 用户id
     * @param int answer 增加的用户答题数
     * @param int quiz 提问数
     * @return array id 增加的用户Id
     */

    public function add() {
        $insert = array(
            'time'=>$this->time,
            'uid'=>$this->uid,
            'answer'=>$this->answer,
            'quiz'=>$this->quiz,
        );

        $model = new UserlivenessModel();
        $id = $model->add($insert);
        return $id;
    }

 
    public function updateById() {
        $data = array(
            'id'=>$this->id,
            'time'=>$this->time,
            'uid'=>$this->uid,
            'answer'=>$this->answer,
            'quiz'=>$this->quiz,
        );

        $model = new UserlivenessModel();

        return $model->updateById($this->id,$data);
    }
    public function getByTime()
    {
        $model=new UserlivenessModel();
        return $model->getByTime($this->starttime,$this->endtime);
    }
}
