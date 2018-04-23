<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Tipoff as TipoffModel;
/**
 * 举报接口类
 *
 * @author: ssh
 */

class Tipoff extends Api {

	public function getRules() {
        return array(
            'index' => array(
                'username' 	=> array('name' => 'username'),
            ),
            'add' => array(
                'type' => array('name' => "type"),
                'reason'=>array('name'=>"reason"),
                'target' => array('name' => "target"),
                'review' => array('name' => "review"),
                'reviewuser' => array('name' => 'reviewuser'),
                'reviewtime' => array('name' => 'reviewtime'),
                'time' => array('name' => 'time'),
            ),
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id' => array("name" => "id")
            ),
            'updateById' => array(
                'id' => array('name'=> 'id','require'=>true),
                'type' => array('name' => "type",'require'=>true),
                'reason'=>array('name'=>"reason",'require'=>false,'default'=>null),
                'target' => array('name' => "target",'require'=>true,'default'=>null),
                'review' => array('name' => "review",'require'=>true),
                'reviewuser' => array('name' => 'reviewuser','require'=>false,'default'=>null),
                'reviewtime' => array('name' => 'reviewtime','require'=>false,'default'=>null),
                'time' => array('name' => 'time','require'=>true),
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
     * 获取所有内容
     * @desc 获取所有举报信息
     * 
     * @return array data 所有举报信息
     * 
     */
    public function getAll() {
        $model = new TipoffModel();
        $data = $model->getAll();
        //循环每一行 添加label与value
        $res = array();
        while ($row = $data->fetch()) {
            $row["value"] = $row["id"];
            $row["label"] = $row["name"];
            array_push($res, $row);
        }
        return $res;
    }

    /**
     * 根据ID获取
     * @desc 根据ID获取举报信息
     * @param int id 要获取的内容的id
     * 
     * @return data id 该id指定的内容
     */
    public function getById() {
        $model = new TipoffModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据ID删除
     * @desc 根据ID删除举报信息
     * 
     * @param int id 要删除的的id
     * 
     * @return int data 删除的id
     */
    public function deleteById(){
        $model =new TipoffModel();
        $data  =$model->deleteById($this->id);

        return $data;
    }

    /**
     * 增加举报信息
     * @desc 增加举报的信息
     * 
     * @param int type 举报的类型（用户/问题/评论）
     * @param string reason 举报的原因
     * @param int target 被举报对象的ID
     * @param int review 是否通过审核
     * @param int reviewuser 审核的用户
     * @param time reviewtime 审核的时间
     * @param time time 举报的时间
     * 
     * @return array id 增加的举报的信息
     */
    public function add() {
        $insert = array(
            'type'=>$this->type,
            'reason'=>$this->reason,
            'target' => $this->target,
            'review' => $this->review,
            'reviewuser'=>$this->reviewuser,
            'reviewtime'=>$this->reviewtime,
            'time'=>$this->time,
        );

        $model = new TipoffModel();

        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新举报信息
     * @desc 更新举报的信息
     * 
     * @param int type 举报的类型（用户/问题/评论）
     * @param string reason 举报的原因
     * @param int target 被举报对象的ID
     * @param int review 是否通过审核
     * @param int reviewuser 审核的用户
     * @param time reviewtime 审核的时间
     * @param time time 举报的时间
     * 
     * @return array id 更新的举报的信息
     */
     
    public function updateById() {
        $model = new TipoffModel();

        $data = array(
            "id" => $this->id,
            'type'=>$this->type,
            'reason'=>$this->reason,
            'target' => $this->target,
            'review' => $this->review,
            'reviewuser'=>$this->reviewuser,
            'reviewtime'=>$this->reviewtime,
            'time'=>$this->time,
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
     * 获取举报总数
     */
    public function getTotalNum(){
        $model = new TipoffModel();
        return $model->getTotalNum();
    }
}

