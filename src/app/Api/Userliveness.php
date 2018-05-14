<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Userliveness as UserlivenessModel;
use App\Model\Livenesscampus as LivenesscampusModel;
use App\Model\Question as QuestionModel;
use App\Model\User as UserModel;
use App\Model\Group as GroupModel;
use App\Model\Major as MajorModel;
use App\Model\Label as LabelModel;
use App\Model\Campus as CampusModel;
/**
 * 用户活跃信息接口类
 *
 * @author ssh
 */

class Userliveness extends Api {

	public function getRules() {
        return array(
            'add' => array(
                'time' => array("name" => "time"),
                'uid'=>array('name'=>"uid"),
                'action'=>array('name'=>"action"),
                'targetID'=>array('name'=>"targetID"),
                'describe'=>array('name'=>'describe'),
                'time'=>array('name'=>'time'),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name' => 'id','require'=>true),
                'uid'=>array('name'=>"uid",'require'=>true),
                'action'=>array('name'=>"action",'require'=>true),
                'targetID'=>array('name'=>"targetID",'require'=>true),
                'describe'=>array('name'=>'describe', 'default' => null),
                'time'=>array('name'=>'time', 'default' => null),
            ),
            'getByTime'=>array(
                'starttime'=>array('name'=>'starttime'),
                'endtime'=>array('name'=>'endtime'),
            ),
            "getLivenessById" => array(
                "uid"   => array("name" => "uid"),
                "page" => array("name" => "page"),
                "num"  => array("name" => "num"),
            ),
            'getCampusLiveness' => array(
                'campusID' => array("name" => "campusID"),
                'page'     => array("name" => "page"),
                'num'      => array("name" => "num")
            )
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
    public function getByTime()
    {
        $model=new UserlivenessModel();
        return $model->getByTime($this->starttime,$this->endtime);
    }

    /**
     * 获取用户最近动态
     * @param uid   用户id
     * @param page 页数
     * @param num 每页的数量
     * 
     * @return array 动态
     */
    public function getLivenessById() {
        $model = new UserlivenessModel();
        $start = $this->num * ($this->page - 1);
        $data = $model -> getByIdLimit($this->uid, $start, $this->num);
        $res = array();
        while($row = $data->fetch()) {
            $targetID = $row["targetID"];
            $action   = $row["action"];

            $model1 = $this->getTypeModel($action);
            $targetInfo = $model1->getById($targetID);
            $row["targetInfo"] = $targetInfo;

            array_push($res, $row);
        }
        return $res;
    }
    /**
     * 获取学校的动态
     * @param int campusID 学校ID
     * @param int page 页数
     * @param int num  每页的数量
     */
    public function getCampusLiveness(){
        $model = new UserlivenessModel();
        $model1 = new LivenesscampusModel();
        $start = $this->num * ($this->page - 1);
        $lives = $model1->getLiveIDByCampusID($this->campusID,$start,$this->num);

        while($line = $lives->fetch()){
            $liveID = $line["liveID"];
            $data = $model->getById($liveID);
            $res = array();
            while($row = $data->fetch()) {
                $targetID = $row["targetID"];
                $action   = $row["action"];

                $model2 = $this->getTypeModel($action);
                $targetInfo = $model2->getById($targetID);
                $row["targetInfo"] = $targetInfo;

                array_push($res, $row);
            }
        }
        return $res;
    }

    public function getTypeModel($action){
        switch($action){
            case "1":
                return null;
                break;
            case "2":
                $model = new QuestionModel();
                return $model;
                break;
            case "3":
                $model = new QuestionModel();
                return $model;
                break;
            case "4":
                $model = new UserModel();
                return $model;
                break;
            case "5":
                $model = new QuestionModel();
                return $model;
                break;
            case "6":
                $model = new LabelModel();
                return $model;
                break;
            case "7":
                $model = new CampusModel();
                return $model;
                break;
            case "8":
                $model = new MajorModel();
                return $model;
                break;
            case "9":
                $model = new GroupModel();
                return $model;
                break;
            default:
                break;
        }
    }

}
