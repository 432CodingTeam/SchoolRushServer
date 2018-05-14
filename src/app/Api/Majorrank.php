<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Majorrank as MajorrankModel;
/**
 * 专业接口类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Majorrank extends Api {

	public function getRules() {
        return array(
            'getMajorRank'=>array(
                'majorID'=>array('name'=>'majorID'),
                'campusID'=>array('name'=>'campusID'),
            ),
        );
	}
	
    /**
     * 获取学校排名
     * @desc 获取学校在某个专业的排名
     * @author lxx
     * @param int campusID 学校id
     * @param int majorID 专业id
     * @return int data 学校排名（前十）
     */
    public function getMajorRank()
    {
        $model=new MajorrankModel();
        $model1=$model->getMajorTopten($this->majorID);
        $d=0;
        foreach($model1 as $m)
        {
            $d++;
            if($this->campusID==$m)
            return $d;
        }
    }
}
