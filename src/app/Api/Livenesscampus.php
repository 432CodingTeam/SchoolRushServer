<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Livenesscampus as LivenesscampusModel;
/**
 * 活跃用户-学校关系接口类
 *
 * @author: ssh
 */

class Livenesscampus extends Api {

	public function getRules() {
        return array(
            'add' => array(
                'liveID' => array('name' => "liveID"),
                'campusID'=>array('name'=>"campusID"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name'=> 'id','require'=>true),
                'liveID' => array('name' => "liveID",'default'=>null),
                'campusID'=>array('name'=>"campusID",'default'=>null),
            ),
        );
	}

    /**
     * 获取所有内容
     * @desc 获取所有活跃用户-学校关系信息
     * 
     * @return array data 所有活跃用户-学校关系
     * 
     */
    public function getAll() {
        $model = new LivenesscampusModel();
        $data = $model->getAll();
        return $data;
    }

    /**
     * 根据ID获取
     * @desc 根据ID获取活跃用户-学校关系信息
     * @param int id 要获取的内容的id
     * 
     * @return data id 该id指定的内容
     */
    public function getById() {
        $model = new LivenesscampusModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据ID删除
     * @desc 根据ID删除活跃用户-学校关系信息
     * 
     * @param int id 要删除的的id
     * 
     * @return int data 删除的id
     */
    public function deleteById(){
        $model =new LivenesscampusModel();
        $data  =$model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加活跃用户-学校关系信息
     * @desc 增加活跃用户-学校关系的信息
     * 
     * @param int liveID 活跃用户id
     * @param int campusID 所属学校的id
     * 
     * @return array id 增加的活跃用户-学校关系的信息
     */
    public function add() {
        $insert = array(
            'liveID'=>$this->liveID,
            'campusID' => $this->campusID,
        );

        $model = new LivenesscampusModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新活跃用户-学校关系信息
     * @desc 更新活跃用户-学校关系的信息
     * 
     * @param int liveID 活跃用户id
     * @param int campusID 所属学校的id
     * 
     * @return array id 更新的活跃用户-学校关系的信息
     */
     
    public function updateById() {
        $model = new LivenesscampusModel();

        $data = array(
            "id" => $this->id,
            'liveID'=>$this->liveID,
            'campusID' => $this->campusID,
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

