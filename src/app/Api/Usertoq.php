<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Usertoq as UsertoqModel;
/**
 * 用户通过题目关系表接口类
 *
 */

class Usertoq extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'uid' => array('name' => "uid"),
                'qid'=>array('name'=>"qid"),

            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array("name" => 'id','require'=>true),
                'uid' => array('name' => "uid",'require'=>true),
                'qid'=>array('name'=>"qid",'require'=>false,'default'=>null),
                'status'=>array('name'=>'status','require'=>true),
            ),
            'getPassingRate' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getTobeSolved' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getPassed' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getTopTen' => array(
                'qid' => array('name' => 'qid'),
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
     * 获取用户通过题目数
     * @desc 获取所有的用户通过题目的胡
     * @return array data 获取的所有用户通过数
     */
    public function getAll() {
        $model = new UsertoqModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取
     * @desc 根据提供的id获取该id指定的信息
     * @param int data 要获取的信息的id
     * @return array data 该id指定得信息
     */

    public function getById() {
        $model = new UsertoqModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据提供的id删除对应的信息
     * @param int id 要删除信息的id
     * @return int data 删除的id
     */

    public function deleteById()
    {
        $model = new UsertoqModel();
        $data = $model->deleteById($this->id);

        return $data;
    }
    /**
     * 增加用户通过题目数
     * @param int uid 用户id
     * @return int qid 通过题目id
     */
    public function add() {
        $insert = array(
            'uid' => $this->uid,
            'qid' => $this->qid,
        );

        $model = new UsertoqModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 根据名字获取id
     * @desc 根据名字获取id
     * @param string name 要获取的id的名字
     * @return int id 该名字对应的id
     */

  
    /**
     * 更新用户通过题目数
     * @param int uid 用户id
     * @return int qid 通过题目id
     */
    public function updateById() {
        $data = array(
            'uid'=>$this->uid,
            'qid'=>$this->qid,
            'status'=>$this->status,
        );

        $model = new UsertoqModel();

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
     * 获取用户的通过率
     * @param int uid 用户id
     * @return float rate 通过率
     */
    public function getPassingRate(){

        $model = new UsertoqModel();

        return $model->getPassingRate($this->uid);
    }
    /**
     * 获取用户待解决的问题
     * @param int uid 用户id
     * @return array data 用户待解决的问题
     */
    public function getTobeSolved(){

        $model = new UsertoqModel();

        return $model->getTobeSolved($this->uid);
    }

     /**
      * 获取用户已经解决的问题
      * @param int uid 用户id
      * @return array data 用户已经解决的问题
      */
      public function getPassed(){

          $model = new UsertoqModel();

          return $model->getPassed($this->uid);
      }
      
      /**
      * 获取最新通过问题的前十个用户
      * @param int qid 题目id
      * @return array data 最新通过问题的前十个用户id
      */
      public function getTopTen(){
          $model = new UsertoqModel();
        
          $data = $model->getTopTen($this->qid);
          $arr=array();
          foreach($data as $data){
            $arr[]=$data['uid'];
        }
        $top=array_reverse($arr);
        for($i=0;$i<10;$i++){
            $topten[$i] = $top[$i];
        }
          return $topten;
      }
       /**
      * 获取最近8小时的答题情况
     * @desc 最新一小时取自当前时间
     * @author lxx
     * @return array 返回的答题情况
     */
    public function getDayAnswers(){
        $model=new UsertoqModel();
        $arr=array();
        $d=0;
        $start=mktime(date('H'),0,0,date('m'),date('d'),date('Y'));  //当前的时间戳
        $end=mktime(date('H'),59,59,date('m'),date('d'),date('Y'));  
       $arr[date("Y-m-d H",$start)]= $model->getQuestionByTime(date("Y-m-d H:i:s",$start),date('Y-m-d H:i:s',$end));//最新一小时的数据

       while($d<7){//之后七小时的数据
        $d++;
        $start=strtotime("-1 hour",$start);  //当前的时间戳
        $end=strtotime("-1 hour",$end);  
        $arr[date("Y-m-d H",$start)]=$model->getQuestionByTime(date('Y-m-d H:i:s',$start),date('Y-m-d H:i:s',$end));

       }
       return $arr;
    }
}
