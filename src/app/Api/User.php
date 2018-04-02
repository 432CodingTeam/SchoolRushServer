<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\User as UserModel;
use App\Domain\Token as TokenDomain;
use App\Common\Upload;
/**
 * 用户接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class User extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'name' => array('name' => "name"),
                'pass'=>array('name'=>"pass"),
                'identify'=>array('name'=>"identify"),
                'email'=>array('name'=>"email"),
                'tel'=>array('name'=>"tel"),
                'campusID'=>array('name'=>"campusID"),
                'major'=>array('name'=>"major"),
                'vice'=>array('name'=>"vice"),
                'avatar'=>array('name'=>"avatar"),
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
                'pass'=>array('name'=>"pass"),
                'identify'=>array('name'=>"identify"),
                'email'=>array('name'=>"email"),
                'tel'=>array('name'=>"tel"),
                'campusID'=>array('name'=>"campusID"),
                'major'=>array('name'=>"major"),
                'vice'=>array('name'=>"vice"),
                'avatar'=>array('name'=>"avatar"),
            ),
            'getByName'=>array(
                'name'=>array('name'=>'name'),
            ),
            "login" => array(
                "name" => array("name" => "name"),
                "pass" => array("name" => "pass"),
            ),
            "logout" => array(
                "name" => array("name" => "name"),
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
     * 获取所有用户
     * @desc 获取所有用户信息
     * @return array data 获取的所有用户信息
     * 
     */
    public function getAll() {
        $model = new UserModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取
     * @desc 根据id获取用户信息
     * @param int id 要获取的用户id
     * @return data data 改id指定的用户信息
     */

    public function getById() {
        $model = new UserModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除用户信息
     * @param int id 要删除的用户id
     * @return int data 要删除的用户id
     */
    public function deleteById()
    {
        $model = new UserModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加用户 => 用户注册
     * @desc 增加用户信息 
     * @param string name 必须 增加的用户名称
     * @param string pass 必须 密码
     * @param int identify 身份
     * @param string email 必须 email
     * @param string tel 电话
     * @param int campusID 所在学校ID
     * @param int major 所在专业ID
     * @param int vice 副专业ID
     * @return array id 增加的用户Id false 表示用户名重复
     */

    public function add() {
        $model = new UserModel();
        
        //对用户名查重
        $isNameRepeat = $model->isRepeat("name", $this->name);
        $isMailRepeat = $model->isRepeat("email", $this->email);

        if($isNameRepeat)
            return array(
                "res" => false,
                "error" => "用户名重复"
            );
        if($isMailRepeat)
        return array(
            "res" => false,
            "error" => "邮箱重复"
        );
        
        $insert = array(
            'name'=>$this->name,
            'pass'=>$this->pass,
            'identify'=>$this->identify,
            'email'=>$this->email,
            'tel'=>$this->tel,
            'campusID'=>$this->campusID,
            'major'=>$this->major,
            'vice'=>$this->vice,
            'avatar'=> "",  //头像地址先留空 后面上传之后更新
        );
        
        $res = $model->add($insert);

        //在返回的数据中添加一条token
        $tokenModel = new TokenDomain();
        $tokenRes = $tokenModel->add($res["id"]);
        $res["token"] = $tokenRes["token"];
        return $res;
    }

    /**
     * 根据名字获取id
     * @desc 根据名字获取id
     * @param string name 要获取的id的名字
     * @return int id 该名字对应的id
     */

    public function getByName()
    {
        $model = new UserModel();
        $data = $model->getByName($this->name);

        return $data;
    }

    /**
     * 根据ID更新用户信息
     * @param id 用户id
     * @param name 用户名
     * @param pass 用户密码
     * @param identify 用户身份
     * @param email 用户邮箱
     * @param tel 用户电话
     * @param campusID 用户学校ID
     * @param major 用户专业ID
     * @param vice 用户兴趣专业ID
     * @param avatar base64编码的图片 将会存储为图片并保存到七牛云，最后将图片外链存到数据库中
     * 
     * @return res 1: 有更改 0:无更改 false: 更新失败
     */
    public function updateById() {
        $model = new UserModel();

        $base64 = $this->avatar;
        $saveRes = $model->base64toImg($base64, $this->id);
        if(!$saveRes) 
            return array("res"=>false, "error"=>"保存本地图片失败");
        //上传到七牛云 将avatar设置为外链地址
        $upload = new Upload();
        $upRes = $upload->uploadToQNY($saveRes["filePath"],$saveRes["fileName"]);
        if(array_key_exists("res", $upRes))
            return array("res"=>false, "msg"=>"图片上传失败", "error"=>$upRes["error"]);
        $this->avatar = $upRes;
        $data = array(
            'id'=>$this->id,
            'name'=>$this->name,
            'pass'=>$this->pass,
            'identify'=>$this->identify,
            'email'=>$this->email,
            'tel'=>$this->tel,
            'campusID'=>$this->campusID,
            'major'=>$this->major,
            'vice'=>$this->vice,
            'avatar'=>$this->avatar,
        );


        $id = $model->updateById($this->id,$data);
        return array("res"=>$id);
    }

    /**
     * 用户登陆
     * 
     * @param string name 用户名
     * @param string pass 用户密码
     * 
     * @return bool 成功信息 成功之后会返回token信息
     */
    public function login() {
        $userModel = new UserModel();

        //判断是邮箱还是用户名
        $emailPattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        preg_match($emailPattern, $this->name, $emailValid);
        $user = "";
        if(count($emailValid) == 0) { //是用户名
            $user = $userModel->getByName($this->name);
        } else { //是邮箱
            $user = $userModel->getByMail($this->name);
        }
        if(count($user) == 0)
            return array(
                "res" => false,
                "msg" => "没有此用户"
            );
        $user = $user->fetchOne();
        //判断用户名密码是否匹配
        if ($this->pass != $user["pass"])
            return array(
                "res" => false,
                "msg" => "用户名密码不匹配"
            );
        
        //新增一条token
        $tokenModel = new TokenDomain();
        $tokenRes = $tokenModel->add($user["id"]);
        return $tokenRes;
    }

    /**
     * 用户登出
     * 
     * @param string name 用户名
     * 
     * @return bool 删除成功 1
     */
    public function logout() {
        $userModel = new UserModel();

        $user = $userModel->getByName($this->name);
        if(count($user) == 0)
            return array(
                "res" => false,
                "msg" => "没有此用户"
            );
        $user = $user->fetchOne();
        
        //删除token
        $tokenModel = new TokenDomain();
        $tokenRes = $tokenModel->deleteByUid($user["id"]);

        return $tokenRes;
    }
}
