<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Comments as CommentsModel;
use App\Model\Question as QuestionModel;
use App\Model\User as UserModel;
/**
 * 学校接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Comments extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'uid'       => array("name"     => "uid"),
                'qid'       => array("name"     => "qid"),
                'reply'    => array("name"   => "reply"),
                'content'   => array("name"     => "content"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name'=> 'id','require'=>true),
                'qid' => array('name'=> 'qid','require'=>true),
                'uid' => array('name'=> 'uid','require'=>true),
                'replay' => array('name' => "replay",'require'=>true),
                'agree'=>array('name'=>"agree",'default'=>null,'require'=>false),
                'disagree' => array('name' => "disagree",'default'=>null,'require'=>false),
                'content' => array('name' => "content",'default'=>true),
            ),
            'getIdByName'=>array(
                'id'=>array('name'=>"id"),
            ),
            'addAgreeById'=>array(
                'id'=>array('name'=>'id'),
            ),
            'deleteAgreeById'=>array(
                'id'=>array('name'=>'id'),
            ),
            'addDisagreeById'=>array(
                'id'=>array('name'=>'id'),
            ),
            'deleteDisagreeById'=>array(
                'id'=>array('name'=>'id'),
            ),
            'getCommentsPage'=>array(
                'qid'=>array('name'=>'id'),
                'page' => array('name' => 'page'),
                'num' => array('name' => 'num', 'default' => 20),
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
     * 获取所有内容
     * @desc 获取所有评论信息
     * @author lxx
     * @return array data 所有信息
     * 
     */
    public function getAll() {
        $model = new CommentsModel();
        $data = $model->getAll();
        //循环每一行 添加label与value
        $res = array();
        while ($row = $data->fetch()) {
            $row["value"] = $row["id"];
            array_push($res, $row);
        }
        return $res;
    }

    /**
     * 根据ID获取
     * @desc 根据ID获取评论信息
     * @param int id 要获取的内容的id
     * @author lxx
     * @return data id 该id指定的内容
     */
    public function getById() {
        $model = new CommentsModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据ID删除
     * @desc 根据ID删除评论信息
     * @author lxx
     * @param int id 要删除的的id
     * 
     * @return int data 删除的id
     */
    public function deleteById(){
        $model =new CommentsModel();
        $data  =$model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加评论信息
     * @desc 增加评论的信息
     * @author lxx
     * @param int uid 用户id
     * @param int qid 问题id
     * @param int replay 回复的用户id
     * @param int agree 赞同的人数（一般为0）
     * @param int disagree 反对的人数(一般为0)
     * @return array id 增加的评论的信息
     */
    public function add() {
        $insert = array(
            'uid'=>$this->uid,
            'qid'=>$this->qid,
            'content'=>$this->content,
        );

        if($this->reply)
            $insert["reply"] = $this->replay; 
        $model = new CommentsModel();

        $data = $model->add($insert);

        return $data;
    }


    /**
     * 更新评论信息
     * @desc 根据id更新评论的信息
     * 
     * @author lxx
     * @param int uid 用户id
     * @param int qid 问题id
     * @param int replay 回复的用户id
     * @param int agree 赞同的人数（一般为0）
     * @param int disagree 反对的人数(一般为0)
     * @return array id 更新的评论的信息
     */
     
    public function updateById() {
        $model = new CommentsModel();

        $data = array(
            'id'=>$this->id,
            'uid'=>$this->uid,
            'qid'=>$this->qid,
            'time'=>date('Y-m-d H:i:s'),
            'replay' => $this->replay,
            'agree' => $this->agree,
            'disagree'=>$this->disagree,
            'content'=>$this->content,
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
     * 获取评论总数
     */
    public function getTotalNum(){
        $model = new CommentsModel();
        return $model->getTotalNum();
    }
    /**
     * @author lxx
     * 增加一个点赞数
     * @desc 根据评论id增加一个对该评论的点赞数
     * @param int id 评论id
     * @return data model 返回该条评论的所有信息
     */
    public function addAgreeById()
    {
        $model=new CommentsModel();
        $data=$model->getById($this->id);
        $data["agree"]++;
        $model->updateById($this->id,$data);
        return $model->getById($this->id);
    }
     /**
     * @author lxx
     * 减少一个点赞数
     * @desc 根据评论id减少一个对该评论的点赞数
     * @param int id 评论id
     * @return data model 返回该条评论的所有信息
     */
    public function deleteAgreeById()
    {
        $model=new CommentsModel();
        $data=$model->getById($this->id);
        if($data["agree"]>=0) $data["agree"]--;
        $model->updateById($this->id,$data);
        return $model->getById($this->id);
    }
      /**
     * @author lxx
     * 增加一个差评数
     * @desc 根据评论id增加一个对该评论的差评数
     * @param int id 评论id
     * @return data model 返回该条评论的所有信息
     */
    public function addDisagreeById()
    {
        $model=new CommentsModel();
        $data=$model->getById($this->id);
        $data["disagree"]++;
        $model->updateById($this->id,$data);
        return $model->getById($this->id);
    }
     /**
     * @author lxx
     * 减少一个差评数
     * @desc 根据评论id减少一个对该评论的差评数
     * @param int id 评论id
     * @return data model 返回该条评论的所有信息
     */
    public function deleteDisagreeById()
    {
        $model=new CommentsModel();
        $data=$model->getById($this->id);
        if($data["disagree"]>=0) $data["disagree"]--;
        $model->updateById($this->id,$data);
        return $model->getById($this->id);
    }
    /**
     * 获取一页的评论 默认为20条/页
     * @author lxx
     * @param page 页数
     * @param num 可选 多少条每页
     * @param qid 问题id
     * @return array 返回的一页
     */
    public function getCommentsPage() {
        $model = new CommentsModel();
        $model1=$model->getAll()->order('time DESC');
        
        $start = ($this->page - 1) * $this->num;
        $data =  $model1->limit($start, $this->num);

        $res = array();

        while($row = $model1->fetch()) {
            $uid = $row["uid"];
            //获取用户名 将用户名也添加到获取的数据中
            $userModel = new UserModel();
            $user = $userModel->getById($uid);
            $row["uName"] = $user["name"];
            //将题目描述添加到获取的数据中
            $questionModel =new QuestionModel();
            $qid=$row["qid"];
            $question=$questionModel->getById($qid);
            $row["question"] = $question["q"];
            array_push($res, $row);
        }

        return $res;
    }

}

