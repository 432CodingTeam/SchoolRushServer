<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class User extends NotORM {

    protected function getTableName($id) {
        return 'user';
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
<<<<<<< HEAD
    public function getIdByName($name)
=======
    public function getByName($name)
>>>>>>> 5855a21cc3d4ea05cf9f2ba18c3015185832cf3a
    {
        $model=$this->getORM();
        $data=$model->where("name",$name);
        return $data;
    }

    public function updateById($id,$data) {
        $model = $this->getORM();

        return $model->where("id", $id)->update($data);
    }

    public function isRepeat($key, $value) {
        $model = $this->getORM();
        
        $res = $model->where($key, $value);
        
        return count($res) == 0 ? false : true;
    }

    public function getByMail($mail) {
        $model=$this->getORM();
        $data = $model->where("email",$mail);
        return $data;
    }
    
    //将base64转为图片 失败返回false 成功返回文件路径和文件名
    public function base64toImg($base64, $imgName) {
        $imgName = "user_" . $imgName . '.png';
        if (strstr($base64,",")) {
            $base64 = explode(',',$base64);
            $base64 = $base64[1];
        }

        $img = base64_decode($base64);
        $path = './upload/';
        $a = file_put_contents($path. $imgName, $img);
        if($a)
            return array("filePath"=>$path.$imgName, "fileName"=>$imgName);
        return false;
    }
}