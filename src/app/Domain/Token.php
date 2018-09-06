<?php

namespace App\Domain;
use App\Model\Token as TokenModel;
use App\Model\User as UserModel;

date_default_timezone_set('Asia/Shanghai'); //设置默认时区

class Token {

    //添加一条token
    public function add($uid) {
        $tokenModel = new TokenModel();
        $userModel = new UserModel();

        //先删除
        $this->deleteByUid($uid);

        $user = $userModel->getById($uid);
        $user = $user;
        $token = md5($user["name"] . date("Y-m-d H:m:s"));

        $data = array(
            "token" => $token,
            "uid" => $uid,
            "expiretime" => date("Y-m-d H:i:s", strtotime("+2 hour")), //token有效期为20分钟
        );

        $insert_res = $tokenModel->add($data);
        return $insert_res;
    }

    //删除一条token
    public function deleteByUid($uid) {
        $tokenModel = new TokenModel();

        return $tokenModel->deleteByUid($uid);  //如果有的话会删除 没有也不会出错
    }

    //延长token的过期时间
    public function addExpireTime($uid) {
        $tokenModel = new TokenModel();

        $token = $tokenModel->getByUid($uid);

        if(count($token) == 0)
            return false; //没有此条token
        $token = $token->fetchOne();

        $data = array(
            "expiretime" => date("Y-m-d H:i:s", strtotime("+2 hour")),
        );

        return $tokenModel->updateById($token["id"], $data);
    }

    //判断token是否过期
    public function isExpire($uid) {
        $tokenModel = new TokenModel();

        $token = $tokenModel->getByUid($uid);

        if(count($token) == 0)
            return array("res" => false, "msg" => "没有此条token");
        
        $token = $token->fetchOne();

        $tokenExpire = $token["expiretime"];

        $now = date("Y-m-d H:i:s");

        if((strtotime($now) - strtotime($tokenExpire)) > 0) {
            $this->deleteByUid($uid); //删除过期的token
            return array("res" => true, "msg" => "该token已过期，已经被删除");
        }
        return array("res" => false, "msg" => "该token未过期");
    }

}