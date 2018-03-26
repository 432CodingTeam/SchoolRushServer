<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Label as LabelModel;
/**
 * 标签服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Label extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'name'=>array('name'=>"n"),
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
        $model = new LabelModel();
        $data = $model->getAll();

        return $data;
    }

    public function getById() {
        $model = new LabelModel();
        $data = $model->getById($this->id);

        return $data;
    }
    public function deleteById()
    {
        $model = new LabelModel();
        $data = $model->deleteById($this->id);

        return $data;
    }
    public function add() {
        $insert = array(
        'name'=>$this->name,
        );

        $model = new LabeLModel();

        $id = $model->add($insert);

        return $id;
    }

}
