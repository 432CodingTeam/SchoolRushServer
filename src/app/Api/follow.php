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
            ),
            'getFollowerNum' => array(
                'uid' => array('name'=>'uid'),
            ),
            'isFollowed' => array(
                'uid' => array('name' => "uid"),
                'type'=>array('name'=>"type"),
                'target' => array('name' => "target"),
            ),
            'unFollow' => array(
                'uid' => array('name' => "uid"),
                'type'=>array('name'=>"type"),
                'target' => array('name' => "target"),
            ),
            'getFollowedIds' => array(
                'id' => array('name' => 'id', 'require' => true),
                'page' => array('name' => 'page', 'require' => false, 'default' => 1),
            ),
            'getFollowerIds' => array(
                'id' => array('name' => 'id', 'require' => true),
                'page' => array('name' => 'page', 'require' => false, 'default' => 1),
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

        $isRepeat = $model -> isRepeat($this->uid, $this->type, $this->target);
        if($isRepeat) {
            return array("msg" => "已经关注了。");
        }
        $id = $model->add($insert);

        return $id;
    }
    /**
     * 用户是否已经关注了
     * 
     */
    public function isFollowed() {
        $model = new FollowModel();

        return $model -> isRepeat($this->uid, $this->type, $this->target);
    }

    /**
     * 取消关注
     */
    public function unFollow() {
        $model = new FollowModel();

        return $model -> deleteByInfo($this->uid, $this->type, $this->target);
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
     * 用户关注了多少指定类型的
     * @param int uid 用户id
     * @param int type 关注用户/标签
     * @return int 该用户关注用户/标签总数
     */
    public function getFollowNum(){
        $model = new FollowModel();
        return $model->getFollowNum($this->uid,$this->type);
    }

    /**
     * @author ssh
     * 用户被多少人关注
     * @param int uid 用户id
     * @param int type 关注用户/标签
     * @return int 该用户关注用户/标签总数
     */
    public function getFollowerNum(){
        $model = new FollowModel();
        return $model->getFollowerNum($this->uid);
    }

    /**
     * @author iimT
     * @desc 获取用户关注的用户id，每页20个
     * @param bigint id 用户id
     * @param int    page 页数 默认为1
     * @return array 关注的用户数组
     */
    public function getFollowedIds() {
        $model  = new FollowModel();
        $length = 20;
        $start  = ($this->page - 1) * $length;
        $result = $model -> getFollowUserIds($this->id, $start, $length);
        $data   = array();
        foreach ($result as $val) {
            array_push($data, $val['target']);
        }
        return $data;
    }

    /**
     * @author iimT
     * @desc 获取用户关注的用户id，每页20个
     * @param bigint id 用户id
     * @param int    page 页数 默认为1
     * @return array 关注的用户数组
     */
    public function getFollowerIds() {
        $model  = new FollowModel();
        $length = 20;
        $start  = ($this->page - 1) * $length;
        $result = $model -> getFollowerUserIds($this->id, $start, $length);
        $data   = array();
        foreach ($result as $val) {
            array_push($data, $val['uid']);
        }
        return $data;
    }
}

