<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Groupactivity as GroupactivityModel;
use App\Model\Usertogroup as UsertogroupModel;
use App\Model\Usertoq as UsertoqModel;
/**
 * 小组活动接口类
 *
 * @author: ssh
 */

class Groupactivity extends Api {

	public function getRules() {
        return array(
            'add' => array(
                'title' => array('name' => "title"),
                'questionsID'=>array('name'=>"questionsID"),
                'gid' => array('name' => "gid"),
                'starttime' => array('name' => "starttime"),
                'content' => array('name' => 'content'),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name'=> 'id','require'=>true),
                'title' => array('name' => "title",'require'=>true),
                'questionsID'=>array('name'=>"questionsID",'default'=>null),
                'gid' => array('name' => "gid",'default'=>null),
                'starttime' => array('name' => "starttime",'default'=>null),
                'content' => array('name' => 'content','default'=>null),
            ),
            'getGroupActivityPageById'=>array(
                'gid'=>array('name'=>'gid'),
                'page' => array('name' => 'page', "default" => 1),
                'num'  => array('name' => 'num', "default" => 20),
                
            ),
        );
	}

    /**
     * 获取所有内容
     * @desc 获取所有小组活动信息
     * 
     * @return array data 所有小组活动
     * 
     */
    public function getAll() {
        $model = new GroupactivityModel();
        $data = $model->getAll();
        return $data;
    }

    /**
     * 根据ID获取
     * @desc 根据ID获取小组活动信息
     * @param int id 要获取的内容的id
     * 
     * @return data id 该id指定的内容
     */
    public function getById() {
        $model = new GroupactivityModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据ID删除
     * @desc 根据ID删除小组活动信息
     * 
     * @param int id 要删除的的id
     * 
     * @return int data 删除的id
     */
    public function deleteById(){
        $model =new GroupactivityModel();
        $data  =$model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加小组活动信息
     * @desc 增加小组活动的信息
     * 
     * @param string title 标题
     * @param int questionsID 题集ID
     * @param int gid 所属小组的id
     * @param time starttime 开始时间
     * @param string content 活动的介绍
     * 
     * @return array id 增加的小组活动的信息
     */
    public function add() {
        $insert = array(
            'title'=>$this->title,
            'questionsID'=>$this->questionsID,
            'gid' => $this->gid,
            'starttime' => $this->starttime,
            'content' => $this->content,
        );

        $model = new GroupactivityModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新小组活动信息
     * @desc 更新小组活动的信息
     * 
     * @param string title 标题
     * @param int questionsID 题集ID
     * @param int gid 所属小组的id
     * @param time starttime 开始时间
     * @param string content 活动的介绍
     * 
     * @return array id 更新的小组活动的信息
     */
     
    public function updateById() {
        $model = new GroupactivityModel();

        $data = array(
            "id" => $this->id,
            'title'=>$this->title,
            'questionsID'=>$this->questionsID,
            'gid' => $this->gid,
            'starttime' => $this->starttime,
            'content' => $this->content,
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
     * 获取一页指定小组的信息
    * @desc根据小组id获取该小组所有活动信息，包括通过人数
     * @author lxx
     * @param int gid 小组id
     * @param int page 获取第几页，默认第一页
     * @param int num 一页几行，默认20行
     * 
     * @return array res 获取的一页小组活动信息
     */
    public function getGroupActivityPageById() {
        $model = new GroupactivityModel();
        $usertogroupModel=new UsertogroupModel();
        $usertoqModel=new UsertoqModel();

        $start = ($this->page - 1) * $this->num;
        $data =  $model->getByLimit($start, 20,$this->gid);

        $usertogroup=$usertogroupModel->getBygid($this->gid);//该小组的成员加入小组信息
        //一页的活动信息
        $res = array();
        foreach($data as $d)
        {
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
        return $res;
    }

}

