<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Campusmajorpassed as CampusmajorpassedModel;
use App\Model\Campus as CampusModel;
use App\Model\Major as MajorModel;
/**
 * 学校-分类-通过数关系接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Campusmajorpassed extends Api {

	public function getRules() {
        return array(
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
                'id'       => array("name" => "id",'require'=>true),
                'majorID'  => array('name' => "majorID",'require'=>true),
                'campusID' => array('name'=>"campusID",'require'=>true),
                'aday'     => array('name'=>'aday', 'default' => null),
                'aweek'    => array('name'=>'aweek', 'default' => null),
                'amonth'   => array('name'=>'amonth', 'default' => null),
                'lastday'  => array('name'=>'lastday', 'default' => null),
                'lastweek' => array('name'=>'lastweek', 'default' => null),
                'lastmonth'=> array('name'=>'lastmonth', 'default' => null),
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
            'getCampusPassed'=>array(
                'campusID'=>array('name' => 'campusID'),
            ),
            'getTopFiveMajor'=>array(
                'campusID' => array('name' => 'campusID'),
            ),
            'getCampusPassedNum'=>array(
                'cid' => array('name' => 'cid'),
            ),
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
        $model = new CampusmajorpassedModel();

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
    /**
     * 日答题量
     * @desc 获取一天中所有通过的题目数量
     * @author lxx
     * @return int data 一天中通过的题目数量
     */
    public function getDayPassed()
    {
        $model=new CampusmajorpassedModel();
        return $model->getDayPassed();
    }
    /**
     * 学校的问题通过总数
     * @author ssh
     * @desc 根据学校ID来获取学校总问题通过数
     * @param int campusID 学校ID
     * @return int allpassed 总的通过数
     */
    public function getCampusPassed(){
        $model = new CampusmajorpassedModel();

        $data  = $model->getCampusPassed($this->campusID);
        //$allpassed = $data["aday"] + $data["lastday"];
        return $data;
    }
    /*
     * 答题通过数学校前五专业
     * @desc 获取答题数在学校中排名前五的专业，返回答题数，专业id和专业名称
     * @author lxx
     * @return array b 前五专业的信息
     */
    public function getTopFiveMajor()
    {
        $model=new CampusmajorpassedModel();
        $model1=$model->getBycampusID($this->campusID);
        if(!$model1) {
            return array("res" => false, "message" => "没有数据");
        }
        $model2=new MajorModel();
        $b=array();
        $rule=array();
        foreach($model1 as $v)//$b获取到该学校每个专业的题目通过量
        {
            if(isset($b[$v['majorID']])){
                 $b[$v['majorID']]['pquestionCount']+=$v['aday'];
               
            }
            else{
                 $b[$v['majorID']]["pquestionCount"]=$v['aday'];
                $b[$v['majorID']]['majorID']=$v['majorID'];
                $b[$v['majorID']]['majorName']=$model2->getNameByID($v['majorID']);
            }

        }
        foreach($b as $a)//倒序
        {
            $rules[]=$a['pquestionCount'];
        }
        array_multisort($rules,SORT_DESC,$b);
        $b=array_slice($b,0,5);//取前五个
        return $b;
    }

    /**
     * 获取某学校解决问题总数
     * @param int cid 学校id
     * @return int 总数量
     */
    public function getCampusPassedNum() {
        $model = new CampusmajorpassedModel();

        $res = $model -> getBycampusID($this->cid);
        $total = 0;
        while($row = $res->fetch()) {
            $num = $row["total"];
            $total = $total + $num;
        }
        return $total;
    }
}

