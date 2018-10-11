<?php
namespace App\Api;

use PhalApi\Api;

/**
 * 添加日志接口类
 * @author 老高 
 */

class AddLog extends Api {
    public function getRules(){
        return array(
            'writeLog' => array(
            'userId'    => array('name' => 'userId'),
            'actionId'  => array('name' => 'actionId'),
            'action'    => array('name' => 'action'),
            ),
            'writeLogToFile' => array(
                'userId'    => array('name' => 'userId'),
                'actionId'  => array('name' => 'actionId'),
                'action'    => array('name' => 'action'),
                ),
        );
    }

    /**
     * 在指定文件夹下每个月创建一个日期目录，再在日期目录下每日创建文件来存放日志
     * @param  $userId   用户ID
     * @param  $actionID 行为ID
     * @param  $action   动作行为
     * @return array
     */
    public function writeLog(){
        /* 接收参数 */
        $dataStr    = "用户ID:".$this->userId.", 行为ID:".$this->actionId.", 用户行为:".$this->action;
        if($dataStr == "用户ID:, 行为ID:, 用户行为:"){
            return array(
                'res' =>false,
                'msg' =>'您还没有传入任何数据',
            );
        }
        // $dataStr = '老高'; // 用于测试
        /* 目录信息 */
        $dirName    = date('Y-m');
        /* 判断存放日志文件的根目录是否存在，不存在就创建一个新的目录 */
        if(!is_dir('actionLog')){
            mkdir('actionLog');
        }
        $dirPath    = 'actionLog/';
        $monthDir   = scandir($dirPath); // 获取AddLog目录下的所有目录
        /* 判断是否需要建立本月的日志目录 */
        $isIn       = in_array($dirName,$monthDir);
        /* 当月目录不存在 */
        if($isIn == false){
            /* 创建目录 */
           mkdir($dirPath.$dirName);
           $fileName  = date('d');
           $filePath  = $dirPath.$dirName.'/'.$fileName.'.log';
           /* 将内容写入日志文件 */
           $result    = $this->WriteFile($filePath,$dataStr);
           if($result == false){
               return array(
                   'res' => false,
                   'msg' => '文件写入失败',
               );
           }else{
            return array(
                'res' => true,
                'msg' => '日志写入成功!',
            );
           }
           return array(
            'res'   => false,
            'msg'   => $result,
            );
        }
        /* 当月目录已存在 */
        else{
            /* 获取本月日志目录下的所有文件 */
            $dayDir  = scandir($dirPath.$dirName.'/');
            /* 判断今日日志是否存在并处理 */
            $dayFile = date('d').'.log';
            if(in_array($dayFile,$dayDir)){
                return array(
                    'res'  => false,
                    'msg'  => '今天的日志已经写入文件',
                );
            }else{
                /* 将内容写入日志文件 */
                $filePath  = $dirPath.$dirName.'/'.$dayFile;
                $result    = $this->WriteFile($filePath,$dataStr);
                if($result == false){
                    return array(
                        'res' => false,
                        'msg' => '文件写入失败',
                    );
                }else{
                    return array(
                        'res' => true,
                        'msg' => '日志写入成功!',
                    );
                }
            }
        }
    }

    /**
     * 在指定文件夹下每日创建日期目录，再在日期目录下创建文件来存放日志
     * @param  $userId   用户ID
     * @param  $actionID 行为ID
     * @param  $action   动作行为
     * @return array
     */
    public function writeLogToFile(){
        /* 接收参数 */
        $dataStr    = "用户ID:".$this->userId.", 行为ID:".$this->actionId.", 用户行为:".$this->action;
        if($dataStr == "用户ID:, 行为ID:, 用户行为:"){
            return array(
                'res' => false,
                'msg' => '您还没有传入任何数据',
            );
        }
        /* 判断当日的log文件有没有加入 */
        $fileName   = time();
        $dirPath    = 'actionLog/Log/';
        $filedir    = scandir($dirPath); // 获取AddLog目录下的所有目录
        /* 如果AddLog目录下没有任何目录，则直接创建新文件夹并写入LOG文件 */
        if(count($filedir)-2 == 0){ // 减去2是'.'和'..'
            /* 创建目录 */
            $res    = mkdir($dirPath.$fileName);
            if($res == false){
                return array(
                    'res'  => false,
                    'msg'  => '文件创建失败',
                );
            }else{
                /* 文件写入 */
                $filePath  = $dirPath.$fileName.'/'.time().'.log';
                $res       = $this->WriteFile($filePath,$dataStr);
                if($res == false){
                    return array(
                        'res' => false,
                        'msg' => "日志写入失败",
                    );
                }else{
                    return array(
                        'res' => true,
                        'msg' => '文件添加成功',
                    );
                }
            }
        }else{
            /* 去掉数组中的".",".." */
            unset($filedir[0]);
            unset($filedir[1]);
            $Max      = max($filedir);
            if(time() - $Max < 86400){
                return array(
                    'res' => false,
                    'msg' => '今日日志已写入文件'
                );
            }else{
                /* 创建目录 */
                $res    = mkdir($dirPath.$fileName);
                if($res == false){
                    return array(
                        'res'  => false,
                        'msg'  => '文件创建失败',
                    );
                }else{
                    /* 文件写入 */
                    $filePath  = $dirPath.$fileName.'/'.time().'.log';
                    $res       = $this->WriteFile($filePath,$dataStr);
                    if($res == false){
                        return array(
                            'res' => false,
                            'msg' => "日志写入失败",
                        );
                    }else{
                        return array(
                            'res' => true,
                            'msg' => '文件添加成功',
                        );
                    }
                }
            }
        }
    }

    /**
     * 将内容写入文件
     * @param  $Parh 要写入的文件的路径
     * @param  $str  要写入的内容
     * @return true/false
     */
    protected function WriteFile($Path,$str){
        $file      = fopen($Path,'w');
        $writeFile = fwrite($file,$str);
        fclose($file);
        if($writeFile == false){
            return false;
        }else{
            return true;
        }
    }
 }