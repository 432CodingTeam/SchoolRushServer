<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Campusmajorpassed as CampusmajorpassedModel;
/**
 * 学校-分类-通过数关系接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Campusmajorpassed extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'majorID' => array('name' => "majorID"),
                'campusID'=>array('name'=>"campusID"),
                'passed'=>array('name'=>'passed'),

            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array("name" => "id"),
                'majorID' => array('name' => "majorID"),
                'campusID'=>array('name'=>"campusID"),
                'passed'=>array('name'=>'passed'),
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
     * 获取所有学校通过数
     * @desc 获取所有学校通过数
     * @return array data 所有学校通过数
     */

    public function getAll() {
        $model = new CampusmajorpassedModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取学校通过数
     * @desc 根据id获取学校通过数
     * @param int id 要获取学校的id
     * @return data data 该id指定的内容
     */
    public function getById() {
        $model = new CampusmajorpassedModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除
     * @param int id 要删除学校的id
     * @return int data 删除的学校id
     */
    public function deleteById()
    {
        $model = new CampusmajorpassedModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加学校通过数
     * @desc 增加学校通过数
     * @param int majorId 专业id
     * @param int campusID 学校id
     * @param int passed 通过人数
     * @return data id 该学校的通过数信息
     */

    public function add() {
        $insert = array(
            'majorId'=>$this->majorID,
            'campusID'=>$this->campusID,
            'passed'=>$this->passed,
        );

        $model = new CampusmajorpassedModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新学校通过数
     * @desc 更新学校通过数
     * @param int majorId 专业id
     * @param int campusID 学校id
     * @param int passed 通过人数
     * @return data id 更新后该学校的通过数信息
     */
    public function updateById() {
        $data = array(
            "id" => $this->id,
            "majorID" => $this->majorID,
            "campusID" => $this->campusID,
            "passed" => $this->passed
        );

        $model = new CampusmajorpassedModel();
        return $model->updateById($this->id, $data);
    }
}
