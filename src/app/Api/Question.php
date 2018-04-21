<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Question as QuestionModel;
use App\Model\Usertoq as UsertoqModel;
use App\Model\User as UserModel;
use App\Model\Major as MajorModel;
use App\Model\Label as LabelModel;
/**
 * 问题接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Question extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'type' => array('name'=>"type"),
                'q' => array('name'=>'q'),
                'A' => array('name'=>'A'),
                'B' => array('name'=>'B'),
                'C' => array('name'=>'C'),
                'D' => array('name'=>'D'),
                'F' => array('name'=>'F'),
                'correct' => array('name'=>'correct'),
                'majorID' => array('name'=>'majorId'),
                'challenges' => array('name'=>'challength'),
                'passed' => array('name'=>'passed'),
                'levels' => array('name'=>'levels'),
                'labels' => array('name'=>'labels'),
                'status' => array('name' => 'status'),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name' => 'id','require'=>true),
                'type' => array('name'=>"type",'require'=>false),
                'q' => array('name'=>'q','require'=>false,),
                'A' => array('name'=>'A','require'=>false,'default'=>null),
                'B' => array('name'=>'B','require'=>false,'default'=>null),
                'C' => array('name'=>'C','require'=>false,'default'=>null),
                'D' => array('name'=>'D','require'=>false,'default'=>null),
                'F' => array('name'=>'F','require'=>false,'default'=>null),
                'correct' => array('name'=>'correct','require'=>false,'default'=>null),
                'majorID' => array('name'=>'majorID','require'=>false),
                'challenges' => array('name'=>'challenges','require'=>false,'default'=>null),
                'passed' => array('name'=>'passed','require'=>false,'default'=>null),
                'levels' => array('name'=>'levels','require'=>false,'default'=>null),
                'labels' => array('name'=>'labels','require'=>false,'default'=>null),
                'toAnswer' => array('name'=>'toAnswer','require'=>false,'default'=>null),
                'status' => array('name' => 'status','require'=>false,'default'=>null),
            ),
            'getQByuid'=>array(
                'uid'=>array('name'=>'uid'),
                'num'=>array('name'=>'num'),
            ),
            'getPQByuid'=>array(
                'uid'=>array('name'=>'uid'),
                'num'=>array('name'=>'num'),
            ),
            'getAllPQByuid'=>array(
                'uid'=>array('name'=>'uid'),
            ),
            'getAllUnPQByuid'=>array(
                'uid'=>array('name'=>'uid'),
            ),
            'getQByKey'=>array(
                'key'=>array('name'=>'key'),
            ),
            'getPage' => array(
                'page' => array('name' => 'page', "default" => 1),
                'num' => array('name' => 'num', 'default' => 20),
            ),
            'getPageByFilter' => array(
                'type' => array('name' => 'type', "default" => -1),
                'status' => array('name' => 'status', "default" => -1),
                'page' => array('name' => 'page', "default" => 1),
                'num' => array('name' => 'num', 'default' => 20),
            ),
            "getTotalNum" => array(
                "status" => array("name" => "status"),
            ),
            'getFilterPage'=>array(
                "page"=>array("name"=>"page","require"=>true),
                "num"=>array("name"=>"num","require"=>true),
                "type"=>array("name"=>"type","require"=>false,"default"=>-1),
                "majorID"=>array("name"=>"majorID","require"=>false,"default"=>-1),
                "levels"=>array("name"=>"levels","require"=>false,"default"=>-1),
                "uid"=>array("name"=>"uid","require"=>false,"default"=>-1),

            ),
            'getPageinformation'=>array(
                'page' => array('name' => 'page'),
                'num' => array('name' => 'num', 'default' => 20),
            ),
            "getTypeById" => array(
                'id' => array("name" => "id"),
            ),
            'addLikeById'=>array(
                'id'=>array('name'=>'id'),
            ),
            'deleteLikeById'=>array(
                'id'=>array('name'=>'id'),
            ),
        );
	}
	
	/**
	 * 默认接口服务
     * @desc 默认接口服务， 标题
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
     * 获取所有的问题
     * @desc获取所有的问题
     * @return array data 获取的所有问题
     */

    public function getAll() {
        $model = new QuestionModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取
     * @desc 根据id获取问题
     * @param int id 要获取的问题的id
     * @param int type 问题的类型 1,2,3对应选择，判断，填空题
     * @param string q 问题的内容
     * @param string correct 正确答案
     * @param string toAnswer 给答题者的话
     * @return data data 该id指定的问题
     */

    public function getById() {
        $model = new QuestionModel();
        $userModel = new UserModel();

        $data = $model->getById($this->id);
        if(!$data) {
            //没有找到此ID的记录
            return array("res" => false, "msg" => "没有找到此题目。");
        }
        if($data["type"]==1){ //选择题
            $arr = array($data["A"],$data["B"],$data["C"],$data["D"]);
            $options = $arr;
            $correct = $data[$data["correct"]];
            //随机打乱数组
            for($i=0;$i<count($arr);$i++){
                $temp = $arr[$i];
                $rand = rand(0,3);
                $arr[$i] = $arr[$rand];
                $arr[$rand] = $temp;    
            }
            for($i=0;$i<count($arr);$i++){
                if($correct==$arr[$i]){               
                    break;
                }
            }
            $opt = array("A","B","C","D");

            $res = array(
                "q"=>$data["q"],
                "options"=>array(
                    "A"=>$arr[0],
                    "B"=>$arr[1],
                    "C"=>$arr[2],
                    "D"=>$arr[3],
                ),
                "correct"=>$opt[$i],
                "toAnswer"=>$data["toAnswer"],
            );
        }
        else if($data["type"]==2){  //判断题
            $res = array(
                "q"=>$data["q"],
                "correct"=>$data["correct"],
                "toAnswer"=>$data["toAnswer"],
            );
        }
        else if($data["type"]==3){ //填空题
            $arr =  explode("____",$data["q"]);
            $res = array(
                "q"=>array(
                    "pre"=>$arr[0],
                    "suf"=>$arr[1],
                ),
                "correct"=>$data["correct"],
                "toAnswer"=>$data["toAnswer"],
            );
        }
        $res["id"]      = $data["id"];
        $res["type"]    = $data["type"];
        //题目信息完成 组装出题人信息
        $uInfo = $userModel -> getById($data["uid"]);
        $res["user"] = $uInfo;
        return $res;
    }

    /**
     * 根据id删除
     * @desc根据id删除问题
     * @param int id 要删除的问题的id
     * @return int data 是否删除成功
     * 
     */

    public function deleteById()
    {
        $model = new QuestionModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加题目
     * @desc 增加题目
     * @param int  type 题目类型
     * @param string A 选项A
     * @param string B 选项B
     * @param string C 选项C
     * @param string D 选项D
     * @param string F 错误答案
     * @param string correct 正确答案
     * @param string majorID 所在分裂ID
     * @param string challenges 挑战人数
     * @param string passed 通过人数
     * @param int levels 问题难度星级
     * @param int labels 标签，用逗号隔开
     * @return int id 增加题目内容
     */

    public function add() {
        $insert = array(
            'type'=>$this->type,
            'q'=>$this->q,
            'A'=>$this->A, 
            'B'=>$this->B,
            'C'=>$this->C,
            'D'=>$this->D,
            'F'=>$this->F,
            'correct'=>$this->correct,
            'majorID'=>$this->majorID,
            'challenges'=>$this->challenges,
            'passed'=>$this->passed,
            'levels'=>$this->levels,
            'labels'=>$this->labels,
            'status' => $this->status,
        );

        $model = new QuestionModel();

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
     * 更新题目
     * @desc 更新题目
     * @param int  type 题目类型
     * @param string A 选项A
     * @param string B 选项B
     * @param string C 选项C
     * @param string D 选项D
     * @param string F 错误答案
     * @param string correct 正确答案
     * @param string majorID 所在分裂ID
     * @param string challenges 挑战人数
     * @param string passed 通过人数
     * @param int levels 问题难度星级
     * @param int labels 标签，用逗号隔开
     * @param int status 状态，1 审核通过，0 待审核
     * @return int id 更新题目内容
     */
    public function updateById() {
        $data = array(
            'type' => $this->type,
            'q' => $this->q,
            'A' => $this->A,
            'B' => $this->B,
            'C' => $this->C,
            'D' => $this->D,
            'F' => $this->F,
            'correct' => $this->correct,
            'majorID' => $this->majorID,
            'challenges' => $this->challenges,
            'passed' => $this->passed,
            'levels' => $this->levels,
            'labels' => $this->labels,
            'toAnswer' => $this->toAnswer,
            'status' => $this->status,
        );

        $model = new QuestionModel();

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
     * 获取用户最近提出的问题
     * @desc 根据用户id倒序获取用户最近提出的问题信息
     * @param int uid 用户id
     * @param int num 要获取的天数（在该天数内获取用户的提问）
     * @return array data 获取的问题信息
     */
    public function getQByuid()
    {
        $model=new QuestionModel();
        $model = $model->getQByuid($this->uid)->order('id DESC');
        return $model->limit($this->num);
    }
     /**
     * 获取用户最近解决的问题
     * @desc 根据用户id倒序获取用户最近解决的问题信息
     * @param int uid 用户的id
     * @param int num 要获取的天数（获取该天数以内的问题）
     * @return array data 获取的解决问题的信息
     */
    public function getPQByuid()
    {
        $model1=new QuestionModel();
        $model2=new UsertoqModel();
        $pq=$model2->getPQIdByuid($this->uid);
        $pqid=array();
        foreach($pq as $pq)
        {
            $pqid[]=$pq['qid'];
        }
        $model1=$model1->getById($pqid)->order('id DESC');
        return $model1->limit($this->num);
    }

    /**
     * 获取用户所有解决的问题
     * @desc 根据用户id倒序获取用户所有解决的问题
     * @param int uid 用户的id
     * @return array data 用户所有的解决的问题
     */
    public function getAllPQByuid()
    {
        $model1=new QuestionModel();
        $model2=new UsertoqModel();
        $pq=$model2->getPQIdByuid($this->uid);
        $pqid=array();
        foreach($pq as $pq)
        {
            $pqid[]=$pq['qid'];
        }
        
        $model1=$model1->getById($pqid)->order('id DESC');
        return $model1;
    }

    /**
     * 获取用户所有未解决的问题
     * @desc 根据用户id倒序获取用户所有未解决的问题
     * @param int uid 用户的id
     * @return array data 用户所有的未解决的问题
     */
    public function getAllUnPQByuid()
    {
        $model1=new QuestionModel();
        $model2=new UsertoqModel();
        $pq=$model2->getUnPQIdByuid($this->uid);
        $pqid=array();
        foreach($pq as $pq)
        {
            $pqid[]=$pq['qid'];
        }
        
        $model1=$model1->getById($pqid)->order('id DESC');
        return $model1;
    }
    /**
     * 按照关键字索引题目
     * @desc 根据题目内容关键字出现次数对题目进行排序（关键字按照空格分割，未出现关键字的题目不显示）
     * @param string key 关键字
     * @return array question 返回的题目
     */
    public function getQByKey()
    {
        $model=new QuestionModel();
        $question=$model->getAll()->fetchall();//将所有数据复制到该数组
        $keys=explode(' ',$this->key);//将传入的关键的按照空格分割
        for($i=0;$i<sizeof($question);$i++)//初始化数据
        {
            $question[$i]["num"]=0;
            $nums[$i]=0;
        }
        for($i=0;$i<sizeof($keys);$i++)//根据关键字来计数
        {
            for($j=0;$j<sizeof($question);$j++)
            {
                if(strstr($question[$j]["q"],$keys[$i]))
                {
                 $question[$j]["num"]=$question[$j]["num"]+1;//关键字个数
                 $nums[$j]++;
                }
            }
        }
     
       array_multisort($nums,SORT_DESC,$question);//按照关键字数出现次数排序
       $d=0;
       for($i=0;$i<sizeof($question);$i++)
       {
           /*if($question[$i]["num"]==0)
           { 
                unset($question[$i]);
               //$i=0;
            }//删除关键字为0的元素
           else {unset($question[$i]["num"]);}//删除计数元素*/
           if($question[$i]["num"]>0)
           {
               $q[$d]=$question[$i];
               unset($q[$d]["num"]);
               $d=$d+1;
           }
       }
       
       return $q;
    }

    /**
     * 获取一页数据 默认为20条/页
     * 
     * @param page 页数
     * @param num 可选 多少条每页
     * 
     * @return array 返回的一页
     */
    public function getPage() {
        $model = new QuestionModel();
        $majorModel = new MajorModel();
        $userModel = new UserModel();

        $start = ($this->page - 1) * $this->num;
        $data =  $model->getByLimit($start, $this->num);

        $res = array();

        while($row = $data->fetch()) {
            $uid = $row["uid"];
            $majorID = $row["majorID"];
            //获取用户名专业 将用户名专业也添加到获取的数据中
            //获取用户名 将用户名也添加到获取的数据中
            $userModel = new UserModel();
            $user = $userModel->getById($uid);
            $major = $majorModel->getById($majorID);
            $row["uName"] = $user["name"];
            $row["majorName"] = $major["name"];

            array_push($res, $row);
        }

        return $res;
    }

    /**
     * 根据筛选条件获取一页问题
     * @author ssh
     * @modify iimT
     * @param int type 问题类型 1 选择题 2 判断题 3 填空题
     * @param int status 审核状态 0 未审核 1 已审核
     * @return int 某类型问题某审核状态下的问题总数
     */
    public function getPageByFilter() {
        $model = new QuestionModel();
        $userModel = new UserModel();
        $majorModel = new MajorModel();

        $filter = array();

        if($this->type != -1) 
            $filter["type"] = $this->type;
        if($this->status != -1) 
            $filter["status"] = $this->status;
        $start = ($this->page - 1) * $this->num;
        $totalNum = $model->getTotalNumByFilter($filter);
        $data = $model->getByFilterLimit($filter, $start, $this->num);

        $page = array();

        while($row = $data->fetch()) {
            $uid = $row["uid"];
            $majorID = $row["majorID"];
            //获取用户名专业 将用户名专业也添加到获取的数据中
            $user = $userModel->getById($uid);
            $major = $majorModel->getById($majorID);
            $row["uName"] = $user["name"];
            $row["majorName"] = $major["name"];

            array_push($page, $row);
        }

        $res = array("page" => $page, "totalNum" => $totalNum);
        return $res;
    }

    /**
     * 获取问题数量
     * @author iimT
     * @param type 0未审核通过 1审核通过
     * 
     * @return int 数量
     */
    public function getTotalNum() {
        $model = new QuestionModel();

        $filter = array();
        if($this->status != null)
            $filter["status"] = $this->status;

        return $model->getTotalNumByFilter($filter);
    }

    /**
     * 根据筛选条件获取一页问题
     * @desc 只能筛选专业 类型 出题人 难度
     * @author lxx
     * @param page 第几页
     * @param num 一页几条
     * @param majorId int 筛选专业
     * @param type int 筛选问题类型
     * @param levels int 筛选问题难度
     * @param uid int 筛选出题人
     * 
     * @return array 返回的数据

     * TODO: 可以将Model中的getByLimit与getFilterByLimit合并为一个方法
     */
    public function getFilterPage() {
        $model = new QuestionModel();
        $userModel=new UserModel();
        $majorModel = new MajorModel();
        
        $filterData = array();


        if($this->majorID != -1)
            $filterData["majorID"] = $this->majorID;
        if($this->uid != -1)
            $filterData["uid"] = $this->uid;
        if($this->type != -1)
            $filterData["type"] = $this->type;
        if($this->levels != -1)
            $filterData["levels"] = $this->levels;

        $start = ($this->page - 1) * $this->num;
        $data = $model->getFilterByLimit($filterData, $start, $this->num);

        //添加majorName 与 userName字段
        $res = array();
        while($row = $data->fetch()) {
            $uid = $row["uid"];
            $majorId = $row["majorID"];
            
            $majorName = $majorModel->getById($majorId);
            $majorName = $majorName["name"];
            $row["majorName"] = $majorName;

            $userName = $userModel->getById($uid);
            $userName = $userName["name"];
            $row["userName"] = $userName;

            array_push($res, $row);
        }

        return $res;
    }
   /**
     * 根据页数获取一页数据 默认为20条/页
     * @author lxx
     * @param page 页数
     * @param num 可选 多少条每页
     * 
     * @return array 返回的一页
     */
    public function getPageInformation() {
        $model = new QuestionModel();
        $start = ($this->page - 1) * $this->num;
        $data =  $model->getByLimit($start, $this->num);

        $res = array();

        while($row = $data->fetch()) {
            $user                    = new UserModel();
            $major                   = new majorModel();
            $labelModel              = new LabelModel();

            $arr                     = $row;
            $arr["question"]         = $row["q"];
            $arr["passedrate"]       = $row["challenges"] == 0 ? "0%" : 100*($row["passed"]/$row["challenges"])."%";
            $user                    = $user->getById($row["uid"]);
            $arr["username"]         = $user["name"];
            $arr["useravatar"]       = $user["avatar"];
            $major1                  = $major->getById($row["majorID"]);
            $arr["majorName"]        = $major1["name"];
            $name                    = $major->getById($major1["parent"]);
            
            $arr["majorParentName"]  = $name["name"];
            $arr["labels"]           = $row["labels"] == null ? array() : explode(",",$row["labels"]);
            $label_arr = array();
            foreach( $arr["labels"] as $labelID) {
                $label = $labelModel -> getById($labelID);
                $_lebel = array();
                $_lebel["id"] = $label["id"];
                $_lebel["name"] = $label["name"];
                array_push($label_arr, $_lebel);
            }
            $arr["labelsInfo"]=$label_arr;
            array_push($res, $arr);
        }

        return $res;
    }

    /**
     * 根据问题ID获取问题类型
     * @param int id 问题id
     * 
     * return int 返回问题标记
     */
    public function getTypeById() {
        $model = new QuestionModel();

        return $model -> getTypeById($this -> id);
    }
     /**
     * @author lxx
     * 题目增加一个点赞数
     * @desc 根据题目id增加一个对该题目的点赞数
     * @param int id 题目id
     * @return data model 返回该条题目的所有信息
     */
    public function addLikeById()
    {
        $model=new QuestionModel();
        $data=$model->getById($this->id);
        $data["like"]++;
        $model->updateById($this->id,$data);
        return $model->getById($this->id);
    }
     /**
     * @author lxx
     * 减少一个点赞数
     * @desc 根据题目id减少一个对该题目的点赞数
     * @param int id 题目id
     * @return data model 返回该条题目的所有信息
     */
    public function deleteLikeById()
    {
        $model=new QuestionModel();
        $data=$model->getById($this->id);
        if($data["like"]>=0) $data["like"]--;
        $model->updateById($this->id,$data);
        return $model->getById($this->id);
    }
}
