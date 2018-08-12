<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Group as GroupModel;
use App\Model\User as UserModel;
use App\Common\Upload as QNUpload;
use App\Model\GroupMajor as GroupMajorModel;
use App\Common\GD;
/**
 * 群组接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Group extends Api {

	public function getRules() {
        return array(
            'add'           => array(
                'name'          => array( 'name' => "name"),
                'creator'       => array( 'name' => "creator"),
                'introduce'     => array( 'name' => 'introduce'),
                'avatar'        => array( 'name' => 'avatar'),
                'mid'           => array( 'name' => 'mid'),
            ),
            'getById'       => array(
                'id'            => array( "name" => "id")
            ),
            'deleteById'    => array(
                'id'            => array( "name" => "id")
            ),
            'updateById'    => array(
                'id'            => array( 'name' => "id",        'require' => true),
                'name'          => array( 'name' => "name",      'require' => true),
                'creator'       => array( 'name' => "creator",   'default' => null),
                'members'       => array( 'name' => "members",   'default' => null),
                'introduce'     => array( 'name' => 'introduce', 'default' => null),
                'avatar'        => array( 'name' => 'avatar'),
            ),
            'getIdByName'   =>array(
                'name'          => array( 'name' =>"name"),
            ),
            'getIdByName'   =>array(
                'name'          => array( 'name' =>"name"),
            ),
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
        $model                  = new GroupModel();
        $data                   = $model->getById($this->id);
        $createrID              = (int)$data["creator"];
        $userModel              = new UserModel();
        $data["createrInfo"]    = $userModel -> getById($createrID); //TODO: 这里的数据太多，可以轻量化

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
        $data  = $model->deleteById($this->id);

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
       $data  = $model->getIdByName($this->name);

       return $data;
    }

    /**
     * 增加群组
     * @desc 增加群组
     * @param string name 群组名称
     * @param string creator 群组创建者
     * @param int members 群组人数（一般为0）
     * @param int mid 群组所属专业id
     * @return int id 增加群组id
     */

    public function add() {
        $userModel = new UserModel();
        
        if(substr($this->avatar, 0, 4) == "data") {
            //设置了新的小组头像
            $saveRes = $userModel->base64toImg($this->avatar, $this->creator + strtotime(date("Y-m-d H:i:s")));
            if(!$saveRes)
                return array("res"=>false, "error"=>"保存本地图片失败");
            //上传到七牛云 将avatar设置为外链地址
            $upload = new QNUpload();
            $upRes  = $upload->uploadToQNY($saveRes["filePath"],$saveRes["fileName"]);
            if(is_array($upRes)) {
                return array(
                    "res"       => false, 
                    "msg"       => "图片上传失败", 
                    "error"     => $upRes["error"]
                );
            }
            $this->avatar = $upRes;
        }
        $model  = new GroupModel();
        $insert = array(
            "name"          => $this -> name,
            "creator"       => $this -> creator,
            'introduce'     => $this -> introduce,
            'avatar'        => $this -> avatar,
        );
        $id = $model->add($insert);
        if(@$id['id']) {
            //插入mid 与 gid的关系
            $gid             = $id['id'];
            $groupMajorModel = new GroupMajorModel();
            $data            = array(
                'mid' => $this -> mid,
                'gid' => $gid
            );
            $groupMajorModel -> add($data);
        }
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
            "id"            => $this -> id,
            "name"          => $this -> name,
            "creator"       => $this -> creator,
            "members"       => $this -> members,
            'introduce'     => $this -> introduce,
            'avatar'        => $this -> avatar,
        );
    
        foreach($data as $key => $val) {
            if($val == NULL){
                $keys  = array_keys($data);
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
