<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Question as QuestionModel;
/**
 * 专业接口类
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
                'type'=>array('name'=>"t"),
                'q'=>array('name'=>q),
                'A'=>array('name'=>A),
                'B'=>array('name'=>B),
                'C'=>array('name'=>C),
                'D'=>array('name'=>D),
                'TF'=>array('name'=>TF),
                'correct'=>array('name'=>co),
                'majorID'=>array('name'=>m),
                'challenges'=>array('name'=>ch),
                'passed'=>array('name'=>p),
                'level'=>array('name'=>l),
                'balels'=>array('name'=>b),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            )
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

    public function getAll() {
        $model = new QuestionModel();
        $data = $model->getAll();

        return $data;
    }

    public function getById() {
        $model = new QuestionModel();
        $data = $model->getById($this->id);

        return $data;
    }

    public function add() {
        $insert = array(
        'type'=>$this->type,
        'q'=>$this->q,
        'A'=>$this->A,
        'B'=>$this->B,
        'C'=>$this->C,
        'D'=>$this->D,
        'TF'=>$this->TF,
        'correct'=>$this->correct,
        'majorID'=>$this->majorID,
        'challenges'=>$this->challenges,
        'passed'=>$this->passed,
        'level'=>$this->level,
        'balels'=>$this->balels,
        );

        $model = new QuestionModel();

        $id = $model->add($insert);

        return $id;
    }

}
