<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Group as GroupModel;
/**
 * 群组接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Group extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'name' => array('name' => "name"),
                'creator'=>array('name'=>"creator"),
                'members'=>array('name'=>"members"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name' => "id",'require'=>true),
                'name' => array('name' => "name",'require'=>true),
                'creator'=>array(   'name'=>"creator",'require'=>true,'default'=>null),
                'members'=>array('name'=>"members",'require'=>false,'default'=>null),
            ),
            'getIdByName'=>array(
                'name'=>array('name'=>"name"),
            ),
            'getIdByName'=>array(
                'name'=>array('name'=>"name"),
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
     * 获取所有群组信息
     * @desc 获取所有信息
     * @return array data 所有群组的信息
     */

    public function getAll() {
        $model = new GroupModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取群组信息
     * @desc 根据id获取所有群组的信息，群组人数限制100
     * @param int id 查询的群组id
     * @return data data 查询的群组信息
     */

    public function getById() {
        $model = new GroupModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除
     * @param int id 要删除群组的id
     * @return int 删除群组的id
     */

    public function deleteById()
    {
        $model = new GroupModel();
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
       $model = new GroupModel();
       $data = $model->getIdByName($this->name);

       return $data;
    }

    /**
     * 增加群组
     * @desc 增加群组
     * @param string name 群组名称
     * @param string creator 群组创建者
     * @param int members 群组人数（一般为0）
     * @return int id 增加群组id
     */

    public function add() {
        $insert = array(
            "name"=>$this->name,
            "creator"=>$this->creator,
            "members"=>$this->members,
        );

        $model = new GroupModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新群组
     * @desc 根据id更新群组
     * @param string name 群组名称
     * @param string creator 群组创建者
     * @param int members 群组人数（一般为0）
     * @return data id 更新后群组信息
     */
    public function updateById(){
        $model = new GroupModel();

        $data = array(
            "id"=>$this->id,
            "name"=>$this->name,
            "creator"=>$this->creator,
            "members"=>$this->members
        );
    
        foreach($data as $key => $val) {
            if($val == NULL){
                $keys = array_keys($data);
                $index = array_search($key, $keys);

                array_splice($data, $index, 1);
            }
        }

        $id = $model->updateById($this->id,$data);
        return array("res"=>$id);
    }
    /**
     * 获取群组总数
     */
    public function getTotalNum(){
        $model = new GroupModel();
        return $model->getTotalNum();
    }
}
