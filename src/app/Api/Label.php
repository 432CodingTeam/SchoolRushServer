<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Label as LabelModel;
/**
<<<<<<< HEAD
 * 标签服务类
=======
 * 标签类
>>>>>>> c23b7bb6410626681acffaf8d793dceccb34ef28
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
    /**
     * 获取所有标签
     * @desc 获取所有标签
     * @return array data 所有标签
     */
    public function getAll() {
        $model = new LabelModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取
     * @desc 根据id获取内容
     * @param int id 要获取内容的id
     * @return data data 该id指定的内容
     */

    public function getById() {
        $model = new LabelModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除数据库中的内容
     * @param int id 要删除标签的id
     * @return data data 该id指定的内容
     */

    public function deleteById()
    {
        $model = new LabelModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加标签
     * @desc 增加一条标签
     * @param string name 增加的标签内容
     * @return int id 增加的标签id
     */

    public function add() {
        $insert = array(
        'name'=>$this->name,
        );

        $model = new LabeLModel();

        $id = $model->add($insert);

        return $id;
    }

}
