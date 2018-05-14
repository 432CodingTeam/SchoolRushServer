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
            'add' => array(
                'name'=>array('name'=>"name"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array("name" => "id",'require'=>true),
                'name' => array("name" => "name",'require'=>true),
            ),
            'getIdByName'=>array(
                'name'=>array('name'=>'name'),
            ),
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

        /**
     * 根据名字获取id
     * @desc 根据名字获取id
     * @param string name 要获取的id的名字
     * @return int id 该名字对应的id
     */
    public function getIdByName()
    {
       $model = new LabeLModel();
       $data = $model->getIdByName($this->name);

       return $data;
    }

    /**
     * 更新标签
     * @desc 根据id更新标签
     * @param string name 标签名称
     * @return data id 更新后标签信息
     */
    public function updateById() {
        $data = array(
            "id" => $this->id,
            "name" => $this->name,
        );

        $model = new LabelModel();

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

}
