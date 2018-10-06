<?php
namespace App\Common;

class Utils {
  /**
   * 合并数组 但保留键值
   */
  public function arrayMeargeByKey($arr1, $arr2, $arr3) {
    $result = array();
    foreach($arr1 as $key => $val) {
      $result[$key] = $val;
    }
    foreach($arr2 as $key => $val) {
      $result[$key] = $val;
    }
    foreach($arr3 as $key => $val) {
      $result[$key] = $val;
    }
    return $result;
  }

  //针对搜索结果的快速排序
  public function quickSortSearchRes($arr) {
    //判断参数是否是一个数组
    if(!is_array($arr)) return false;
    //递归出口:数组长度为1，直接返回数组
    $length=count($arr);
    if($length<=1) return $arr;
    //数组元素有多个,则定义两个空数组
    $left=$right=array();
    //使用for循环进行遍历，把第一个元素当做比较的对象
    $first_key = "";
    $count = 0;
    foreach($arr as $key => $val)
    {
      if($count == 0) {
        $count = 1;
        $first_key = $key;
        continue;
      }
      //判断当前元素的大小
      if($arr[$key] > $arr[$first_key]){
        $left[$key] = $arr[$key];
      }else{
        $right[$key] = $arr[$key];
      }
    }
    //递归调用
    $left=$this->quickSortSearchRes($left);
    $right=$this->quickSortSearchRes($right);
    //将所有的结果合并
    return $this->arrayMeargeByKey($left,array($first_key => $arr[$first_key]),$right);
  }
}