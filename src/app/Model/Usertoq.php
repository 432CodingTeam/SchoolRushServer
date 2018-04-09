<<<<<<< HEAD
<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Usertoq extends NotORM {

    protected function getTableName($id) {
        return 'usertoq';
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
    public function getPQIdByuid($uid)
    {
        $model=$this->getORM();
        $data= $model->where("uid",$uid)->and('status',1);
        //把data赋值成问题id
        return $data;
    }
    
    public function getUnPQIdByuid($uid)
    {
        $model=$this->getORM();
        return $model->where('uid',$uid)->and('status',0);
    }
    public function getPassingRate($id){
        $model = $this->getORM();  
        $passedq = $model->where('uid',$id)->and('status',1);
        
        $new_model = $this->getORM();
        $unpassq = $new_model->where('uid',$id)->and('status',0);

        $rate = count($passedq) / (count($passedq)+count($unpassq));
        
        return $rate;
    }

    public function getTobeSolved($uid){
        $model = $this->getORM();

        return $model->where('uid',$uid)->and('status',0);
    }

    public function getPassed($uid){
        $model = $this->getORM();

        return $model->where('uid',$uid)->and('status',1);
    }

    public function getTopTen($qid){
        $model = $this->getORM();
        $data = $model->where('qid',$qid)->and('status',1);
        return $data;
    }
=======
<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Usertoq extends NotORM {

    protected function getTableName($id) {
        return 'usertoq';
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
    public function getPQIdByuid($uid)
    {
        $model=$this->getORM();
        $data= $model->where("uid",$uid)->and('status',1);
        //把data赋值成问题id
        return $data;
    }
    
    public function getUnPQIdByuid($uid)
    {
        $model=$this->getORM();
        return $model->where('uid',$uid)->and('status',0);
    }
    public function getPassingRate($id){
        $model = $this->getORM();  
        $passedq = $model->where('uid',$id)->and('status',1);
        
        $new_model = $this->getORM();
        $unpassq = $new_model->where('uid',$id)->and('status',0);

        $rate = count($passedq) / (count($passedq)+count($unpassq));
        
        return $rate;
    }

    public function getTobeSolved($uid){
        $model = $this->getORM();

        return $model->where('uid',$uid)->and('status',0);
    }

    public function getPassed($uid){
        $model = $this->getORM();

        return $model->where('uid',$uid)->and('status',1);
    }

    public function getTopTen($qid){
        $model = $this->getORM();
        $data = $model->where('qid',$qid)->and('status',1);
        return $data;
    }
    public function getCnt(){
        $model = $this->getORM();
        
        return $model->where("id");
    }
>>>>>>> 32053ac5a65fc4639dde2fe2813c2a1f37192344
}