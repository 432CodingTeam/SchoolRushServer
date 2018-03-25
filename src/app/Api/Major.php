<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Major as MajorModel;
/**
 * 专业接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Major extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'name' => array('name' => "name"),
                'parent'=>array('name'=>"parent"),
                'ranklist'=>array('name'=>'ranklist'),

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
 * 获取所有的专业
 * @desc 获取所有的专业
 * @return array data 所有专业信息
 */
    public function getAll() {
        $model = new MajorModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取专业信息
     * @desc 根据id获取专业信息
     * @param int 要获取的专业的id
     * 
     * @return data 该id指定的内容
     */

    public function getById() {
        $model = new MajorModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除专业
     * @desc 根据id删除专业
     * @param int 要删除的专业id
     * 
     * @return array data 删除指定id后的内容
     */

    public function deleteById()
    {
        $model = new MajorModel();
        $data = $model->deleteById($this->id);

        return $data;
    }
   /**
     * 增加专业
     * @desc 增加专业信息
     * 
     * @param string 增加的专业名称
     * @param int   增加的专业的id
     * @param string 增加的专业排名（一般为0）
     * @return data 返回增加专业的id
     */
    public function add() {
        $insert = array(
            'name'=>$this->name,
            'parent'=>$this->parent,
            'ranklist'=>$this->ranklist,
        );

        $model = new MajorModel();

        $id = $model->add($insert);

        return $id;
    }

}
