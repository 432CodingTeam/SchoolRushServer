<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Question extends NotORM {

    protected function getTableName($id) {
        return 'question';
    }

    public function getAll() {
        //获取label表的所有行
        $model = $this->getORM();
        $data = $model->select("*");
        return $data;
    }

    public function getById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id);
        $data = $data->fetchOne();
        return $data;
    }
    public function getByIds($id){
        $model = $this->getORM();

        $data = $model->where("id",$id);
        return $data;
    }
    public function getByIdArray($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id);
        return $data;
    }
    public function deleteById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id)->delete();
        return $data;
    }
    public function add($insert_data) {
        $model = $this -> getORM();
        return $model -> insert($insert_data);
    }

    public function updateById($id,$data) {
        $model = $this->getORM();

        return $model->where("id", $id)->update($data);
    }
    public function getQByuid($uid)
    {
        $model=$this->getORM();
        $data=$model->where("uid",$uid);
        return $data;
    }
    public function getQBykey()
    {
        $model=$this->getORM();
        $data=$model->where("q",$uid);
        return $data;
    }
    public function getByLimit($start, $num) {
        $model = $this->getORM();
        $data = $model->limit($start, $num);
        
        return $data;
    }

    public function getTotalNumByFilter($filter) {
        $model = $this->getORM();
        $data = $model->where($filter);
        return count($data);
    }
    
    public function getFilterByLimit($filter, $start, $num) {
        $model = $this->getORM();
        
        return $model->where($filter)->limit($start, $num);
    }

    public function getTypeById($id) {
        $model = $this->getORM();

        return $model->select('id, type')->where("id", $id)->fetchOne();
    }

    public function getByExceptId($arr, $start, $num) {
        $data = array();
        foreach($arr as $id) {
            array_push($data, (int)$id["qid"]);
        }
        $model = $this->getORM();

        return $model->order('id DESC')->where("NOT id", $data)->limit($start, $num);
    }

    public function getByIdArr($idArr) {
        $model = $this->getORM();

        return $model->where("id", $idArr);
    }

    public function searchByKey($key) {
        $model = $this->getORM();
        return $model->select("id")->where('title LIKE ?', "%".$key."%")->or('q LIKE ?', "%".$key."%");
    }

    /**
     * 根据关键字搜索，只选择title和id字段
     * @param key 关键字
     * @param num 数量限制
     */
    public function searchSimple($key, $num) {
        $model = $this->getORM();
        return $model -> select("id, title") -> order("createtime DESC") -> where("title LIKE ?", "%" . $key . "%") -> limit(0, $num);
    }

    public function getLivenessInfoById($id) {
        $data = $this->getById($id);
        $data["q"] = $this->getRegPlaceQ($data['q']);
        $data['q'] = mb_substr($data['q'],0,25,"UTF8");
        return $data;
    }

    public function getRegPlaceQ($q) {
        $q = $this->regularReplaceP($q);
        $q = $this->regularReplaceA($q);
        $q = $this->regularReplaceExp($q);
        return $this->regularReplaceCode($q);
    }

    public function getTitleById($id) {
        $model = $this->getORM();

        $data = $model -> select("title") -> where("id", $id) -> fetchOne();
        return $data;
    }
    
    public function regularReplaceP($str){

        $res = preg_replace('/!\[.*\]\((.+)\)/','[图片]',$str);
        if(!$res) return $str;

        return $res;
    }
    public function regularReplaceA($str){
        $res =  preg_replace('/\[.*\]\((.+)\)/','[链接]',$str);
        if(!$res) return $str;

        return $res;
    }

    public function regularReplaceExp($str) {
        $res = preg_replace('/\$\$[\s\S]*?\$\$/','[表达式]', $str);
        if(!$res) return $str;
        return $res;
    }

    public function regularReplaceCode($str) {
        $res = preg_replace('/```[\s\S]*?```/','[代码]', $str);
        if(!$res) return $str;
        
        return $res;
    }

    /**
     * 临时使用
     */
    // public function getAllDemo() {
    //     $model = $this -> getORM();

    //     return $model -> select('id, `keys`') -> where('id > 185');
    // }
}