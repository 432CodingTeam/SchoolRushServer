<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Operation as OperationModel;
/**
 * 操作接口类
 *
 * @author: ssh
 */

class Operation extends Api {

	public function getRules() {
        return array(
            'add' => array(
                'uid' => array('name' => "uid"),
                'operatetime'=>array('name'=>"operatetime"),
                'type'=>array('name'=>"type"),
                'desc'=>array('name'=>'desc'),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name' => "id",'require'=>true),
                'uid' => array('name' => "uid",'require'=>true),
                'operatetime'=>array('name'=>"operatetime",'require'=>true),
                'type'=>array('name'=>"type",'default'=>null),
                'desc'=>array('name'=>'desc','default'=>null),
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
     * 获取所有操作信息
     * @desc 获取所有信息
     * @return array data 所有操作的信息
     */

    public function getAll() {
        $model = new OperationModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取操作信息
     * @desc 根据id获取所有操作的信息
     * @return data data 查询的操作信息
     */

    public function getById() {
        $model = new OperationModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除
     * @param int id 要删除操作的id
     * @return int 删除操作的id
     */

    public function deleteById()
    {
        $model = new OperationModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加操作
     * @desc 增加操作
     * @param int uid 操作人
     * @param time operationtime 操作时间
     * @param int type 操作类型
     * @param string desc 操作说明
     * @return data id 增加的操作
     */

    public function add() {
        $insert = array(
            "uid"=>$this->uid,
            "operatetime"=>$this->operatetime,
            "type"=>$this->type,
            "desc"=>$this->desc,
        );

        $model = new OperationModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新操作
     * @desc 更新操作
     * @param int uid 操作人
     * @param time operationtime 操作时间
     * @param int type 操作类型
     * @param string desc 操作说明
     * @return data id 更新的操作
     */
    public function updateById(){
        $model = new OperationModel();

        $data = array(
            "id"=>$this->id,
            "uid"=>$this->uid,
            "operatetime"=>$this->operatetime,
            "type"=>$this->type,
            "desc"=>$this->desc,
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
}
