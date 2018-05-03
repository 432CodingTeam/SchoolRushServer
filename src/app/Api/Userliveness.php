<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Userliveness as UserlivenessModel;
use App\Model\Question as QuestionModel;
/**
 * 用户活跃信息接口类
 *
 */

class Userliveness extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'time' => array("name" => "time"),
                'uid'=>array('name'=>"uid"),
                'answer'=>array('name'=>"answer"),
                'quiz'=>array('name'=>"quiz"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name' => 'id','require'=>true),
                'time' => array('name' => "time",'require'=>true),
                'uid'=>array('name'=>"uid",'require'=>true),
                'answer'=>array('name'=>"answer",'require'=>false,'default'=>null),
                'quiz'=>array('name'=>"quiz",'require'=>false,'default'=>null),
            ),
            'getByTime'=>array(
                'starttime'=>array('name'=>'starttime'),
                'endtime'=>array('name'=>'endtime'),
            ),
            "getLivenessById" => array(
                "id"   => array("name" => "id"),
                "page" => array("name" => "page"),
                "num"  => array("name" => "num"),
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
     * 获取所有用户活跃记录
     * @desc 获取所有用户活跃记录信息
     * @return array data 所有的用户记录
     * 
     */
    public function getAll() {
        $model = new UserlivenessModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取
     * @desc 根据id获取记录
     * @param int id 要获取的记录id
     * @return data data 改id指定的记录信息
     */

    public function getById() {
        $model = new UserlivenessModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除记录信息
     * @param int id 要删除的记录id
     * @return int data 要删除的记录id
     */
    public function deleteById()
    {
        $model = new UserlivenessModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加用户
     * @desc 增加记录信息 
     * @param date time 增加的记录时间
     * @param int uid 用户id
     * @param int answer 增加的用户答题数
     * @param int quiz 提问数
     * @return array id 增加的用户Id
     */

    public function add() {
        $insert = array(
            'time'=>$this->time,
            'uid'=>$this->uid,
            'answer'=>$this->answer,
            'quiz'=>$this->quiz,
        );

        $model = new UserlivenessModel();
        $id = $model->add($insert);
        return $id;
    }
/**
 * 根据id更新
 * 
 */
 
    public function updateById() {
        $data = array(
            'id'=>$this->id,
            'time'=>$this->time,
            'uid'=>$this->uid,
            'answer'=>$this->answer,
            'quiz'=>$this->quiz,
        );

        $model = new UserlivenessModel();

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
<<<<<<< HEAD
    public function getByTime()
    {
        $model=new UserlivenessModel();
        return $model->getByTime($this->starttime,$this->endtime);
    }

    /**
     * 获取用户最近动态
     * @param id   用户id
     * @param page 页数
     * @param num 每页的数量
     * 
     * @return array 动态
     */
    public function getLivenessById() {
        $model = new UserlivenessModel();
        $questionModel = new QuestionModel();
        $start = $this->num * ($this->page - 1);
        $data = $model -> getByIdLimit($this->id, $start, $this->num);
        $res = array();
        while($row = $data->fetch()) {
            $targetID = $row["targetID"];
            $action   = $row["action"];
            //TODO: 还有好多action对应的操作没有写，后面应该写成swicth的
            if($action == 5 || $action == 3 || $action == 2) {
                $actionInfo = $questionModel->getById($targetID);
                $actionInfo["view_title"] = $actionInfo["title"];
            }
            $row["actionInfo"] = $actionInfo;
            array_push($res, $row);
        }
        return $res;
    }
=======
>>>>>>> 89ca3eba16bd555ff880a0e492179a52fe4618d1
}
