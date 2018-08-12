<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Tipoff as TipoffModel;
/**
 * 举报接口类
 *
 */

class Tipoff extends Api {

	public function getRules() {
        return array(
            'getById' => array(
                'id' => array("name" => "id")
            ),
            'deleteById' => array(
                'id'=> array("name" => "id")
            ),
            'updateById' => array(
                'id' => array("name" => 'id','require'=>true),
                'uid' => array('name' => "uid",'require'=>true),
                'qid'=>array('name'=>"qid",'require'=>false,'default'=>null),
                'status'=>array('name'=>'status','require'=>true),
            ),
            'add'=>array(
                'type' 	=> array('name' => 'type'),
                'reason' 	=> array('name' => 'reason'),
                'target' 	=> array('name' => 'target'),
                'review'=>array('name'=>"review",'require'=>false,'default'=>null),
                'reviewuser'=>array('name'=>"reviewuser",'require'=>false,'default'=>null),
                'reviewtime'=>array('name'=>"reviewtime",'require'=>false,'default'=>null),
            ),
            'updateById' => array(
                'id' => array("name" => 'id','require'=>true),
                'type' => array('name' => "type",'require'=>true),
                'reason'=>array('name'=>"reason",'default'=>null),
                'target'=>array('name'=>'target','require'=>true),
                'review'=>array('name'=>"review",'default'=>null),
                'reviewuser'=>array('name'=>"reviewuser",'default'=>null),
                'reviewtime'=>array('name'=>"reviewtime",'default'=>null),
            ),
        );
	}

    /**
     * 获取所有举报信息
     * @desc 获取所有举报信息
     * @author lxx
     * @return array data 举报信息
     */
    public function getAll() {
        $model = new TipoffModel();
        $data = $model->getAll();

        return $data;
    }

    /**
     * 根据id获取
     * @author lxx
     * @desc 根据提供的id获取该id指定的举报信息
     * @param int data 要获取的信息的id
     * @return array data 该id指定得信息
     */

    public function getById() {
        $model = new TipoffModel();
        $data = $model->getById($this->id);

        return $data;
    }

    /**
     * 根据id删除
     * @author lxx
     * @desc 根据提供的id删除对应的信息
     * @param int id 要删除信息的id
     * @return int data 删除的id
     */

    public function deleteById()
    {
        $model = new TipoffModel();
        $data = $model->deleteById($this->id);

        return $data;
    }

 /**
     * 增加一条举报
     * @desc 增加一条举报信息
     * @author lxx
     * 
     * @param int type 增加的举报类型（1为举报用户，2为举报问题，3为举报评论）
     * @param int target 举报的对象id
     * @param string reason 举报的原因
     * @param int review 是否已审核（1为已审核，2为未审核）
     *  @param int reviewuser 审核的用户
     * @param int reviewtime 审核的时间
     */
    public function add() {
        $insert = array(
            'type'=>$this->type,
            'reason'=>$this->reason,
            'target'=>$this->target,
            'review'=>$this->review,
            'reviewuser'=>$this->reviewuser,
            'reviewtime'=>$this->reviewtime,
            'time'=>date('Y-m-d H:i:s'),
        );

        $model = new TipoffModel();
        $id = $model->add($insert);

        return $id;
    }

    /**
     * 更新举报信息
     * @author lxx
     * 
     * @param int type 增加的举报类型（1为举报用户，2为举报问题，3为举报评论）
     * @param int target 举报的对象id
     * @param string reason 举报的原因
     * @param int review 是否已审核（1为已审核，2为未审核）
     *  @param int reviewuser 审核的用户
     * @param int reviewtime 审核的时间
     */
    public function updateById() {
        $data = array(
            'type'=>$this->type,
            'reason'=>$this->reason,
            'target'=>$this->target,
            'review'=>$this->review,
            'reviewuser'=>$this->reviewuser,
            'reviewtime'=>$this->reviewtime,
            'time'=>date('Y-m-d H:i:s'),
        );
        $model = new TipoffModel();
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
}
