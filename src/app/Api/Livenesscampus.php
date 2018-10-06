<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Livenesscampus as LivenesscampusModel;
use App\Model\Userliveness as UserlivenessModel;
use App\Model\Question as QuestionModel;
use App\Model\User as UserModel;
use App\Model\Group as GroupModel;
use App\Model\Major as MajorModel;
use App\Model\Label as LabelModel;
use App\Model\Campus as CampusModel;
/**
 * 活跃用户-学校关系接口类
 *
 * @author: ssh
 */

class Livenesscampus extends Api {

	public function getRules() {
        return array(
            'add' => array(
                'liveID' => array('name' => "liveID"),
                'campusID'=>array('name'=>"campusID"),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name'=> 'id','require'=>true),
                'liveID' => array('name' => "liveID",'default'=>null),
                'campusID'=>array('name'=>"campusID",'default'=>null),
            ),
            "getLivenessByCampusId" => array(
                "id" => array("name" => "id", "require" => true),
                "num" => array("name" => "num"),
                "page"  => array("name" => "page"),
            )
        );
	}

    /**
     * 获取所有内容
     * @desc 获取所有活跃用户-学校关系信息
     * 
     * @return array data 所有活跃用户-学校关系
     * 
     */
    public function getAll() {
        $model = new LivenesscampusModel();
        $data = $model->getAll();
        return $data;
    }

    /**
     * 根据ID获取
     * @desc 根据ID获取活跃用户-学校关系信息
     * @param int id 要获取的内容的id
     * 
     * @return data id 该id指定的内容
     */
    public function getById() {
        $model = new LivenesscampusModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据ID删除
     * @desc 根据ID删除活跃用户-学校关系信息
     * 
     * @param int id 要删除的的id
     * 
     * @return int data 删除的id
     */
    public function deleteById(){
        $model =new LivenesscampusModel();
        $data  =$model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加活跃用户-学校关系信息
     * @desc 增加活跃用户-学校关系的信息
     * 
     * @param int liveID 活跃用户id
     * @param int campusID 所属学校的id
     * 
     * @return array id 增加的活跃用户-学校关系的信息
     */
    public function add() {
        $insert = array(
            'liveID'=>$this->liveID,
            'campusID' => $this->campusID,
        );

        $model = new LivenesscampusModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新活跃用户-学校关系信息
     * @desc 更新活跃用户-学校关系的信息
     * 
     * @param int liveID 活跃用户id
     * @param int campusID 所属学校的id
     * 
     * @return array id 更新的活跃用户-学校关系的信息
     */
     
    public function updateById() {
        $model = new LivenesscampusModel();

        $data = array(
            "id" => $this->id,
            'liveID'=>$this->liveID,
            'campusID' => $this->campusID,
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
     * 根据学校ID获取学校动态
     * @param int id   学校id
     * @param int num  每页容量
     * @param int page 页数 
     */
    public function getLivenessByCampusId() {
        $model = new LivenesscampusModel();
        $userModel = new UserModel();
        $majorModel = new MajorModel();
        $userlivenessModel = new UserlivenessModel();
        $campusModel = new CampusModel();
        $start = ($this->page - 1) * $this->num; 
        $idData = $model -> getLiveIDByCampusIDLatest($this->id, $start, $this->num);
        $idArr = array();

        
        while($row = $idData -> fetch()) {
            array_push($idArr, $row["liveID"]);
        }
        //var_dump($idArr);
        $idArr = array_reverse($idArr);
        $data = $userlivenessModel -> getByIdArr($idArr);
        $result = array();
        while($row = $data -> fetch()) {
            $action = $row["action"];

            $targetModel = $this->getTypeModel($action);
            $row["userInfo"]   = $userModel -> getById($row["uid"]);

            if($targetModel == null) { //如果是用户加入该网站的话
                $targetModel = new CampusModel();
                $row["targetID"] = $row["userInfo"]["campusID"];
                
                $major = $majorModel -> getById($row["userInfo"]["majorID"]);
                $row["userInfo"]["majorName"] = $major["name"];
            }
            
            $row["targetInfo"] = $targetModel -> getLivenessInfoById($row["targetID"]);
            if($action == 4) { //如果是用户关注了
                $major = $majorModel -> getById($row["targetInfo"]["majorID"]);
                $campus = $campusModel -> getById($row["targetInfo"]["campusID"]);
                $shared = $userlivenessModel -> getUserSharedNum($row["targetInfo"]["id"]);
                $solved = $userlivenessModel -> getUserSolved($row["targetInfo"]["id"]);

                $row["targetInfo"]["majorInfo"]  = $major;
                $row["targetInfo"]["campusInfo"] = $campus;
                $row["targetInfo"]["shared"]     = $shared;
                $row["targetInfo"]["solved"]     = $solved;
            }
            array_push($result, $row);
        }
        return $result;
    }

    /**
     * 根据不同的action 返回不同的model
     */
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

