<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Follow as FollowModel;
/**
 * 关注接口类
 *
 * @author: ssh
 */

class Follow extends Api {

	public function getRules() {
        return array(
            'add' => array(
                'uid' => array('name' => "uid"),
                'type'=>array('name'=>"type"),
                'target' => array('name' => "target"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name'=> 'id','require'=>true),
                'uid' => array('name' => "uid",'require'=>true),
                'type'=>array('name'=>"type",'require'=>true),
                'target' => array('name' => "target",'require'=>true),
            ),
            'getFollowNum' => array(
                'uid' => array('name'=>'uid'),
                'type' => array('name'=>'type'),
            )
        );
	}
    /**
     * 获取所有内容
     * @desc 获取所有学校信息
     * 
     * @return array data 所有学校
     * 
     */
    public function getAll() {
        $model = new FollowModel();
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
     * 根据ID获取
     * @desc 根据ID获取用户关注信息
     * @param int id 要获取的内容的id
     * 
     * @return data id 该id指定的内容
     */
    public function getById() {
        $model = new FollowModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据ID删除
     * @desc 根据ID删除用户关注信息
     * 
     * @param int id 要删除的的id
     * 
     * @return int data 删除的id
     */
    public function deleteById(){
        $model =new FollowModel();
        $data  =$model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加用户关注信息
     * @desc 增加用户关注的信息
     * 
     * @param int uid 用户ID
     * @param int type 关注标签 1 关注用户 2 关注标签
     * @param int target 被关注的用户/标签的ID
     * @return array id 增加的用户关注的标签
     */
    public function add() {
        $insert = array(
            'uid'=>$this->uid,
            'type'=>$this->type,
            'target' => $this->target,
        );

        $model = new FollowModel();

        $id = $model->add($insert);

        return $id;
    }
    /**
     * 更新用户关注信息
     * @desc 根据id更新用户关注的信息
     * @param int uid 用户ID
     * @param int type 关注标签 1 关注用户 2 关注标签
     * @param int target 被关注的用户/标签的ID
     * @return data id 更新后的用户关注的信息
     */
     
    public function updateById() {
        $model = new FollowModel();

        $data = array(
            "id" => $this->id,
            'uid'=>$this->uid,
            'type'=>$this->type,
            'target' => $this->target,
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
     * @author ssh
     * 获取用户关注总数
     * @param int uid 用户id
     * @param int type 关注用户/标签
     * @return int 该用户关注用户/标签总数
     */
    public function getFollowNum(){
        $model = new FollowModel();
        return $model->getFollowNum($this->uid,$this->type);
    }
}

