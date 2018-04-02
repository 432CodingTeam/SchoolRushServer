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
            ),
            'updateById' => array(
                'id' => array('name' => 'id'),
                'name' => array('name' => "name"),
                'parent'=>array('name'=>"parent"),
                'ranklist'=>array('name'=>'ranklist'),
            ),
            'getIdByName'=>array(
                'name'=>array('name'=>'name'),
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
 * 获取所有的专业
 * @desc 获取所有的专业
 * @return array data 所有专业信息
 */
    public function getAll() {
        $model = new MajorModel();
        $data = $model->getAll();
        //循环每一行 添加label与value
        $res = array();
        while ($row = $data->fetch()) {
            $row["value"] = $row["id"];
            $row["label"] = $row["name"];
            array_push($res, $row);
        }
        return $res;
    }

    /**
     * 根据id获取专业信息
     * @desc 根据id获取专业信息
     * @param int id 要获取的专业的id
     * 
     * @return data data 该id指定的内容
     */

    public function getById() {
        $model = new MajorModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除专业
     * @desc 根据id删除专业
     * @param int id 要删除的专业id
     * 
     * @return int data 删除的专业id
     */

    public function deleteById()
    {
        $model = new MajorModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 根据名字获取id
     * @desc 根据名字获取id
     * @param string name 要获取的id的名字
     * @return int id 该名字对应的id
     */
     public function getIdByName()
     {
        $model = new MajorModel();
        $data = $model->GetIdByName($this->name);

        return $data;
     }
   /**
     * 增加专业
     * @desc 增加专业信息
     * 
     * @param string insert专业名称
     * @param int parent 专业的id
     * @param string ranklist 增加的专业排名（一般为0）
     * @return array id 增加的专业信息
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

    /**
     * 更新专业
     * @desc 根据id更新专业信息
     * 
     * @param string 专业名称
     * @param int parent 专业的id
     * @param string ranklist 更新的专业排名
     * @return data id 更新后的专业信息
     */
    public function updateById(){
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'parent' => $this->parent,
            'ranklist' => $this->ranklist
        );

        $model = new MajorModel();

        return $model->updateById($this->id,$data);
    }
}
