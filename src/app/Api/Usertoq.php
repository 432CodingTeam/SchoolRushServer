<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Usertoq as UsertoqModel;
use App\Model\User as UserModel;
use App\Model\Campusmajorpassed as CampusmajorpassedModel;
use App\Model\Question as QuestionModel;
use App\Domain\Question as QuestionDomain;
/**
 * 用户通过题目关系表接口类
 *
 */

class Usertoq extends Api {

	public function getRules() {
        return array(
            'passedQuestion' => array(
                'uid'       => array('name' => "uid"),
                'qid'       => array('name' =>"qid"),
            ),
            'solveQuestion' => array(
                'uid'       => array('name' => "uid"),
                'qid'       => array('name' =>"qid"),
            ),
            'isPassed' => array(
                'uid'       => array('name' => "uid"),
                'qid'       => array('name' =>"qid"),
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
                'qid'=>array('name'=>"qid",'default'=>null),
                'status'=>array('name'=>'status','require'=>true),
                'passtime'=>array('name'=>'passtime','default'=>null),
                'starttime'=>array('name'=>'starttime','default'=>null),
            ),
            'getPassingRate' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getTobeSolved' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getPassed' => array(
                'uid' => array('name' => 'uid'),
                'page' => array('name' => 'page'),
                'num' => array('name' => 'num'),
            ),
            'getTobeSolvedNum' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getPassedNum' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getUpdatePassedTen' => array(
                'qid' => array('name' => 'qid'),
            ),
            'getRateOfCampus' => array(
                'uid' => array('name' => 'uid'),
            ),
            'getRankofCampus' => array(
                'uid' => array('name' => 'uid'),
            )
        );
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
     * 用户通过了某题目
     * @param int uid 用户id
     * @param int qid 通过题目id
     */
    public function passedQuestion() {
        $data = array(
            'uid'       => $this->uid,
            'qid'       => $this->qid,
            'status'    => 1,
            'passtime'  => date('Y-m-d H:i:s'),
        );

        $model = new UsertoqModel();
        $passedStatus = $model -> getPassedStatus($this->uid, $this->qid);
        if(!$passedStatus) {
            //没有查询到记录
            return $model->add($data);
        } else {
            $id = $passedStatus["id"];
            return $model->updateById($id, $data);
        }
    }
    /**
     * 用户正在解决某题目 【用户答案错误时】
     * @param int uid 用户id
     * @param int qid 通过题目id
     */
    public function solveQuestion() {
        $data = array(
            'uid'    => $this->uid,
            'qid'    => $this->qid,
            'status' => 0,
        );

        $model = new UsertoqModel();
        $passedStatus = $model -> getPassedStatus($this->uid, $this->qid);
        if(!$passedStatus) {
            //没有查询到记录
            return $model->add($data);
        } else {
            $id = $passedStatus["id"];
            return $model->updateById($id, $data);
        }
    }
    /**
     * 判断用户是否通过指定题目
     * @param int uid 用户id
     * @param int qid 问题id
     * 
     * @return bool 是否通过
     */
    public function isPassed(){
        $model = new UsertoqModel();
        return $passedStatus = $model -> getPassedStatus($this->uid, $this->qid);
    }
    /**
     * 获取用户的通过率
     * @param int uid 用户id
     * @return percent rate 通过率
     */
    public function getPassingRate(){

        $model = new UsertoqModel();

        $unpassed = $model->getTobeSolvedNum($this->uid);//用户未解决的问题数
        $passed   = $model->getPassedNum($this->uid);    //用户已解决的问题数

        return round($unpassed/($unpassed+$passed)*100,2)."%";
    }
    /**
     * 获取用户待解决的问题
     * @param int uid 用户id
     * @return array data 用户待解决的问题
     */
    public function getTobeSolved() {

        $model = new UsertoqModel();

        return $model->getTobeSolved($this->uid);
    }
    /**
     * 获取用户待解决的问题数量
     * @param int uid 用户id
     * @return array data 用户待解决的问题
     */
    public function getTobeSolvedNum() {

        $model = new UsertoqModel();

        return $model->getTobeSolvedNum($this->uid);
    }

     /**
      * 获取用户已经解决的问题
      * @param int uid 用户id
      * @return array data 用户已经解决的问题
      */
      public function getPassed(){

        $model = new UsertoqModel();
        $questionModel = new QuestionModel();
        $questionDomain = new QuestionDomain();
        $start = ($this->page - 1) * $this->num;
        $data = $model->getPassed($this->uid, $start, $this->num);
        $idArr = array();
        while($row = $data -> fetch()) {
            $qid = $row["qid"];
            array_push($idArr, $qid);
        }
        $q_res = $questionModel -> getByIdArr($idArr);

        return $questionDomain->getQCardInfo($q_res);
      }

      /**
      * 获取用户已经解决的问题数量
      * @param int uid 用户id
      * @return array data 用户已经解决的问题
      */
      public function getPassedNum(){

        $model = new UsertoqModel();

        return $model->getPassedNum($this->uid);
    }
      
      /**
      * 获取最新通过问题的前十个用户
      * @param int qid 题目id
      * @return array arr 最新通过问题的前十个用户id
      */
      public function getUpdatePassedTen(){
          $model = new UsertoqModel();
          $data = $model->getUpdatePassedTen($this->qid);
          $arr = array();
          foreach($data as $data){
              $arr[] = $data['uid'];
          }
          return $arr;
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
    /**
     * 用户对学校的贡献率
     * @desc 用户做题数占所在学校做题数的百分比
     * @author ssh
     * @param uid 用户id
     * @return percent 对学校的贡献率
     */
    public function getRateOfCampus(){
        $model = new UsertoqModel();

        $model1 = new UserModel();
        $data1 = $model1->getById($this->uid);
        $campusId = $data1["campusID"];//该用户的学校ID
        $model2 = new CampusmajorpassedModel();
        $campusPassed = $model2->getCampusPassed($campusId);//该学校的通过数
        $uidPassed = $model->getPassedNum($this->uid);//该用户通过数

        return round($uidPassed / $campusPassed*100,2).'%';
    }
}
