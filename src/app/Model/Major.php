<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 专业表 Model层
 */
class Major extends NotORM {

    protected function getTableName($id) {
        return 'major';
    }
    public function getAll(){
        $model=$this->getORM();
        $data=$model->select("*");
         return $data;
    }
    public function getById($id)
    {
        $model=$this->getORM();
        $data=$model->where("id",$id);
        $data = $data->fetchOne();
        return $data;
    }
  public function getIdByName($name)
    {
        $model=$this->getORM();
        $data=$model->where("name",$name);
        //var_dump($data);
        return $data;
    }  
    public function deleteById($id) {
        $model = $this->getORM();

        $data = $model->where("id",$id)->delete();
        return $data;
    }
    public function add($insert_data)
    {
        $model=$this->getORM();
        $id=$model->insert($insert_data);
        return $id;
    }
    public function updateById($id,$data) {
        $model = $this->getORM();

        return $model->where("id", $id)->update($data);
    }
    public function getNameByID($id)
    {
        $model=$this->getORM();
        $data=$model->where("id",$id)->fetchone();
        
        return $data["name"];
    }
    public function getTotalNum(){
        $model = $this->getORM();

        return $model->count("id");
    }

    /**
     * 根据name字段模糊查询 (不包括大分类专业：如哲学)  最多返回30条(防止高频字段消耗计算资源)
     * @author iimT
     * @param query 查询字段
     */
    public function getByLike($query) {
        $model = $this -> getORM();
        $query = '%' . $query . '%';
        return $model -> select('id, name') -> where('name LIKE ?', $query) -> and("parent > ?", 0) -> limit(30);
    }

    /**
     * 根据专业的父专业ID值查找
     * @author iimT
     * @param parentID 父专业ID
     */
    public function getByParent($parentID) {
        $model = $this -> getORM();
        
        return $model -> where('parent', $parentID);
    }
}