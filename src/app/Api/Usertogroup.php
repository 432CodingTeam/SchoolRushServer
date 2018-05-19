<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Usertogroup as UsertogroupModel;
use App\Model\Userliveness as UserlivenessModel;
use App\Model\User as UserModel;
use App\Model\Groupactivity as GroupactivityModel;
use App\Model\Usertoq as UsertoqModel;
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
            'getUserGroupactivitys'=>array(
                'uid'=>array('name'=>'uid'),
                'page' => array('name' => 'page', "default" => 1),
                'num'  => array('name' => 'num', "default" => 20),
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
    /**
     * 获取指定用户加入的小组活动
     * @desc 获取该用户参与小组的多有活动，返回指定的二十个活动（默认最新的20个）
     * @author lxx
     * @param int uid 用户id
     * @param int page 获取第几页，默认第一页
     * @param int num 一页几行，默认20行
     * 
     * @return array res 获取的一页用户所在小组的活动信息
     */
    public function getUserGroupactivitys(){

        $model1 = new GroupactivityModel();
        $usertogroupModel=new UsertogroupModel();
        $usertoqModel=new UsertoqModel();
        $group=$usertogroupModel->getByuid($this->uid);
        $alldata=array();
            $time=array();
            $res = array();
        foreach($group as $g){
            $data =  $model1->getBygid($g["gid"]);//获取该小组的所有活动
            $usertogroup=$usertogroupModel->getBygid($g["gid"]);//该小组的成员加入小组信息
     
            //一页的活动信息
            foreach($data as $d)
            {
                $time[]=$d["starttime"];
                $questionsId = explode(',',$d["questionsID"]); //获取该活动的问题列表
               
                    $passeduser=0;
                    foreach($usertogroup as $user)//成员人数
                    {
                        $usertoq=$usertoqModel->getByuid($user["uid"])->where("status",1)->fetchall();//该成员的所有通过答题情况
                        $passedquestion=array();
                        foreach($usertoq as $u)//将该用户通过的问题id保存在这个变量当中
                        {
                            $passedquestion[]=$u["qid"];
                        }
                        $cnt=0;
                        foreach($questionsId as $q)//在用户通过的问题中找活动中的问题
                        {
                            if(in_array($q,$passedquestion)){ $cnt++;}//问题在用户已通过问题中加一
                        }
                        if($cnt==count($questionsId)) $passeduser++;
                    }    
                   $d["passeduserNum"]=$passeduser;
                        array_push($res, $d);
            }
        }
        
    array_multisort($time,SORT_DESC,$res);
    
    $start = ($this->page - 1) *$this->num;
    return array_slice($res,$start,$this->num);//获取前二十
    }
}

