<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Campusmajorpassed as CampusmajorpassedModel;
use App\Model\Campus as CampusModel;
/**
 * 学校-分类-通过数关系接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Campusmajorpassed extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'majorID' => array('name' => "majorID"),
                'campusID'=>array('name'=>"campusID"),
                'aday'=>array('name'=>'aday'),
                'aweek'=>array('name'=>'aweek'),
                'amonth'=>array('name'=>'amonth'),
                'lastday'=>array('name'=>'lastday'),
                'lastweek'=>array('name'=>'lastweek'),
                'lastmonth'=>array('name'=>'lastmonth'),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array("name" => "id"),
                'majorID' => array('name' => "majorID"),
                'campusID'=>array('name'=>"campusID"),
                'aday'=>array('name'=>'aday'),
                'aweek'=>array('name'=>'aweek'),
                'amonth'=>array('name'=>'amonth'),
                'lastday'=>array('name'=>'lastday'),
                'lastweek'=>array('name'=>'lastweek'),
                'lastmonth'=>array('name'=>'lastmonth'),
            ),
            'dayRankList'=>array(
                'majorID'=>array('name'=>'majorID'),
            ),
            'weekRankList'=>array(
                'majorID'=>array('name'=>'majorID'),
            ),
            'monthRankList'=>array(
                'majorID'=>array('name'=>'majorID'),
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
     * 获取所有学校通过数
     * @desc 获取所有学校通过数
     * @return array data 所有学校通过数
     */

    public function getAll() {
        $model = new CampusmajorpassedModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取学校通过数
     * @desc 根据id获取学校通过数
     * @param int id 要获取学校的id
     * @return data data 该id指定的内容
     */
    public function getById() {
        $model = new CampusmajorpassedModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @desc 根据id删除
     * @param int id 要删除学校的id
     * @return int data 删除的学校id
     */
    public function deleteById()
    {
        $model = new CampusmajorpassedModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加学校通过数
     * @desc 增加学校通过数
     * @param int majorId 专业id
     * @param int campusID 学校id
     * @param int passed 通过人数
     * @return int id 增加的学校id
     */

    public function add() {
        $insert = array(
            'majorId'=>$this->majorID,
            'campusID'=>$this->campusID,
            'aday'=> $this->aday,
            'aweek'=> $this->aweek,
            'amonth'=> $this->amonth,
            'lastday'=> $this->lastday,
            'lastmonth'=> $this->lastmonth,
            'lastweek'=> $this->lastweek,
        );

        $model = new CampusmajorpassedModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新学校通过数
     * @desc 更新学校通过数
     * @param int majorId 专业id
     * @param int campusID 学校id
     * @param int passed 通过人数
     * @return data id 更新后该学校的通过数信息
     */
    public function updateById() {
        $data = array(
            "id" => $this->id,
            "majorID" => $this->majorID,
            "campusID" => $this->campusID,
            'aday'=> $this->aday,
            'aweek'=> $this->aweek,
            'amonth'=> $this->amonth,
            'lastday'=> $this->lastday,
            'lastmonth'=> $this->lastmonth,
            'lastweek'=> $this->lastweek,
        );

        $model = new CampusmajorpassedModel();
        return $model->updateById($this->id, $data);
    }
    /**
     * 日排行榜
     * @desc 获取不同题目分类中，不同学校做题数排行榜
     * @param int majorID 题目专业分类
     * @return array model 排行榜
     */
    public function dayRankList()
    {
        $model1=new CampusmajorpassedModel();
        $model2=new CampusModel();
        $model=$model1->getadayBymajorID($this->majorID);//获取该题目分类中每个学校id对应的做题数量
        
        for($i=0;$i<sizeof($model);$i++)//在信息中加上学校名称
        {
            $id=$model[$i]["campusID"];
            $m=$model2->getInforByID($id);
            $model[$i]["name"]=$m["name"];
        }
        
        return $model;
    }
      /**
     * 周排行榜
     * @desc 获取不同题目分类中，不同学校做题数排行榜
     * @param int majorID 题目专业分类
     * @return array model 排行榜
     */
    public function weekRankList()
    {
        $model1=new CampusmajorpassedModel();
        $model2=new CampusModel();
        $model=$model1->getaweekBymajorID($this->majorID);
        
        for($i=0;$i<sizeof($model);$i++)
        {
            $id=$model[$i]["campusID"];
            $m=$model2->getInforByID($id);
            $model[$i]["name"]=$m["name"];
        }
        return $model;
    }
      /**
     * 月排行榜
     * @desc 获取不同题目分类中，不同学校做题数排行榜
     * @param int majorID 题目专业分类
     * @return array model 排行榜
     */
    public function monthRankList()
    {
        $model1=new CampusmajorpassedModel();
        $model2=new CampusModel();
        $model=$model1->getamonthBymajorID($this->majorID);
        
        for($i=0;$i<sizeof($model);$i++)
        {
            $id=$model[$i]["campusID"];
            $m=$model2->getInforByID($id);
            $model[$i]["name"]=$m["name"];
        }
        return $model;
    }
}
