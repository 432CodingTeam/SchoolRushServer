<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Usertogroup as UsertogroupModel;
use App\Model\Userliveness as UserlivenessModel;
/**
 * 用户小组关系接口类
 *
 * @author: ssh
 */

class Usertogroup extends Api {

	public function getRules() {
        return array(
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name'=> 'id','require'=>true),
                'uid' => array('name' => "uid",'default'=>null),
                'gid'=>array('name'=>"gid",'default'=>null),
                'jiontime' => array('name' => "jiontime",'default'=>null),
            ),
            'add' => array(
                'uid' => array('name' => 'uid'),
                'gid' => array('name' => 'gid'),
            ),
            'delete' => array(
                'uid' => array('name' => 'uid'),
                'gid' => array('name' => 'gid')
            ),
            'Inquery' => array(
                'uid' => array('name' => 'uid'),
                'gid' => array('name' => 'gid')
            ),
            'getRecentJionU' => array(
                'gid' => array('name' => 'gid')
            ),
        );
	}
	
    /**
     * 获取所有内容
     * @desc 获取所有用户小组关系信息
     * 
     * @return array data 所有用户小组关系
     * 
     */
    public function getAll() {
        $model = new UsertogroupModel();
        $data = $model->getAll();
        return $data;
    }

    /**
     * 根据ID获取
     * @desc 根据ID获取用户小组关系信息
     * @param int id 要获取的内容的id
     * 
     * @return data id 该id指定的内容
     */
    public function getById() {
        $model = new UsertogroupModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据ID删除
     * @desc 根据ID删除用户小组关系信息
     * 
     * @param int id 要删除的的id
     * 
     * @return int data 删除的id
     */
    public function deleteById(){
        $model =new UsertogroupModel();
        $data  =$model->deleteById($this->id);

        return $data;
    }

    /**
     * 更新用户小组关系信息
     * @desc 更新用户小组关系的信息
     * 
     * @param int uid 用户id
     * @param int gid 所属小组的id
     * @param time jiontime 加入时间
     * 
     * @return array id 更新的用户小组关系的信息
     */
     
    public function updateById() {
        $model = new UsertogroupModel();

        $data = array(
            "id" => $this->id,
            'uid'=>$this->uid,
            'gid' => $this->gid,
            'jiontime' => $this->jiontime,
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
     * 用户加入小组
     * 
     * @param int uid 用户id
     * @param int gid 所属小组的id
     * 
     * @return array id 新增的用户-小组关系
     */
    public function add() {
        $insert = array(
            'uid'=>$this->uid,
            'gid'=>$this->gid
        );

        $model = new UsertogroupModel();

        $id = $model->add($insert);

        return $id;
    }
    /**
     * 用户退出小组
     * 
     * @param int uid 用户id
     * @param int gid 所属小组的id
     * 
     * @return array id 删除的用户-小组关系
     */
    public function delete(){
        $delete = array(
            'uid' => $this->uid,
            'gid' => $this->gid
        );

        $model = new UsertogroupModel();

        $id = $model->delete($delete);

        return $id;
    }

    /**
     * 查询用户是否加入小组
     * 
     * @param int uid 用户id
     * @param int gid 小组的id
     * 
     * @return true/false 加入/未加入
     */
    public function Inquery(){
        $model = new UsertogroupModel();

        $data = $model->Inquery($this->uid, $this->gid);
        
        // var_dump($data);
        if($data == null){
            return false;
        }
        else{
            return true;
        } 
    }
    /**
     * 获取最近加入小组的12个用户
     * 
     * @param int gid 小组的id
     * @return array recentJion 用户id
     */
    public function getRecentJionU(){
        $model = new UserlivenessModel();

        $data = $model->getRecentJionU($this->gid);
        foreach($data as $data){
            $arr[]=$data['uid'];
        }

        return $arr;
    }
}

