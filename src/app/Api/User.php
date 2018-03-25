<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\User as UserModel;
/**
 * 专业接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class User extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'name' => array('name' => "n"),
                'pass'=>array('name'=>"p"),
                'identify'=>array('name'=>"i"),
                'email'=>array('name'=>"e"),
                'tel'=>array('name'=>"t"),
                'campusID'=>array('name'=>"c"),
                'major'=>array('name'=>"m"),
                'vice'=>array('name'=>"v"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
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

    public function getAll() {
        $model = new UserModel();
        $data = $model->getAll();

        return $data;
    }

    public function getById() {
        $model = new UserModel();
        $data = $model->getById($this->id);

        return $data;
    }
    public function deleteById()
    {
        $model = new UserModel();
        $data = $model->deleteById($this->id);

        return $data;
    }
    public function add() {
        $insert = array(
            'name'=>$this->name,
            'pass'=>$this->pass,
            'identify'=>$this->identify,
            'email'=>$this->email,
            'tel'=>$this->tel,
            'campusID'=>$this->campusID,
            'major'=>$this->major,
            'vice'=>$this->vice,
        );

        $model = new UserModel();

        $id = $model->add($insert);

        return $id;
    }

}
