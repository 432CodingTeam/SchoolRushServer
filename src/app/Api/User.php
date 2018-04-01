<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\User as UserModel;
use App\Model\Question as QuestionModel;
use App\Model\Usertoq as UsertoqModel;
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
            ),
            'getIdByName'=>array(
                'name'=>array('name'=>'name'),
            ),
             'getGoodAtRank'=>array(
                 'uid'=>array('name'=>'uid'),
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
     * 增加用户
     * @desc 增加用户信息 
     * @param string name 增加的用户名称
     * @param string pass 密码
     * @param int identify 身份
     * @param string email email
     * @param string tel 电话
     * @param int campusID 所在学校ID
     * @param int major 所在专业ID
     * @param int vice 副专业ID
     * @return array id 增加的用户Id
     */

    public function add() {
        $insert = array(
            'name'=>$this->name,
            'pass'=>$this->pass,
            'identify'=>$this->identify,
            'email'=>$this->email,
            'tel'=>$this->tel,
            'campusID'=>$this->campusID,
            'major'=>$this->major,
            'vice'=>$this->vice,
        );

        $model = new UserModel();

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
       $model = new UserModel();
       $data = $model->getIdByName($this->name);

       return $data;
    }
    public function updateById() {
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
        );

        $model = new UserModel();

        return $model->updateById($this->id,$data);
    }
    /**
     * 获取用户擅长排行
     * @desc 获取用户擅长的题型排行
     * @param int id 用户id
     * @return array arr 用户擅长的题型排行
     */
    public function  getGoodAtRank()
    {
        $model1=new QuestionModel();
        $model2=new UsertoqModel();
        $pq=$model2->getPQIdByuid($this->uid);
        $tp=array();
        foreach($pq as $pqs)
        {
            $tp[]=$pqs['id'];
        }
        $model=$model1->getById($tp)->fetchAll();//获取到所有通过的问题信息
        $arr=array(
            $arr[0]=array(1,0),
            $arr[1]=array(2,0),
            $arr[2]=array(3,0),
        );
        $num=array($num[0]=0,$num[1]=0,$num[2]=0,);
        for($i=0;$i<sizeof($model);$i++)
        {
            if($model[$i]["type"]==1){ $num[0]++;}
            else if($model[$i]["type"]==2){$num[1]++;}
            else {$num[2]++;}
        }
        $arr[0][1]=$num[0]/sizeof($model);
        $arr[1][1]=$num[1]/sizeof($model);
        $arr[2][1]=$num[2]/sizeof($model);
        array_multisort($num,SORT_DESC,$arr);
        return $arr;
    }
}
