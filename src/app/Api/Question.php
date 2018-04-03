<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Question as QuestionModel;
use App\Model\Usertoq as UsertoqModel;
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
                'balels' => array('name'=>'balels'),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name' => 'id'),
                'type' => array('name'=>"type"),
                'q' => array('name'=>'q'),
                'A' => array('name'=>'A'),
                'B' => array('name'=>'B'),
                'C' => array('name'=>'C'),
                'D' => array('name'=>'D'),
                'F' => array('name'=>'F'),
                'correct' => array('name'=>'correct'),
                'majorID' => array('name'=>'majorID'),
                'challenges' => array('name'=>'challenges'),
                'passed' => array('name'=>'passed'),
                'levels' => array('name'=>'levels'),
                'balels' => array('name'=>'balels'),
                'toAnswer' => array('name'=>'toAnswer')
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
        $data = $model->getById($this->id);
        if($data["type"]==1){
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
                "id"=>$data["id"],
                "q"=>$data["q"],
                "arr"=>array(
                    "A"=>$arr[0],
                    "B"=>$arr[1],
                    "C"=>$arr[2],
                    "D"=>$arr[3],
                ),
                "correct"=>$opt[$i],
                "toAnswer"=>$data["toAnswer"],
            );
        }
        else if($data["type"]==2){
            $res = array(
                "id"=>$data["id"],
                "q"=>$data["q"],
                "correct"=>$data["correct"],
                "toAnswer"=>$data["toAnswer"],
            );
        }
        else if($data["type"]==3){
            $arr =  explode("____",$data["q"]);
            $res = array(
                "id"=>$data["id"],
                "q"=>array(
                    "pre"=>$arr[0],
                    "suf"=>$arr[1],
                ),
                "correct"=>$data["correct"],
                "toAnswer"=>$data["toAnswer"],
            );
        }
        return $res;
    }

    /**
     * 根据id删除
     * @desc根据id删除问题
     * @param int id 要删除的问题的id
     * @return int data 要删除的问题id
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
     * @param int balels 标签，用逗号隔开
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
        'balels'=>$this->balels,
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
     * @param int balels 标签，用逗号隔开
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
            'balels' => $this->balels,
            'toAnswer' => $this->toAnswer,
        );

        $model = new QuestionModel();

        return $model->updateById($this->id,$data);
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
}
