<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/13
 * Time: 23:12
 */

namespace app\index\controller;
use think\Db;

//该类用于各种选择的查询
class Query extends Base
{
    //查询存在的所有的地区
    public function area(){
        $list = Db::table('area')->distinct(true)->field('province')->select();
        for ($i=0;$i<count($list);$i++){
           $city =  Db::table('area')
               ->distinct(true)
               ->field('city')
               ->where('province',$list[$i]['province'])
               ->select();

           for ($j=0;$j<count($city);$j++){
               $district = Db::table('area')
                   ->field('district')
                   ->where('province',$list[$i]['province'])
                   ->where('city',$city[$j]['city'])
                   ->select();
               for($k=0;$k<count($district);$k++){
                   $district[$k]['label'] = $district[$k]['value'] = $district[$k]['district'];
                   unset($district[$k]['district']);
               }
               $city[$j]['label'] = $city[$j]['value'] =  $city[$j]['city'];
               unset($city[$j]['city']);
               $city[$j]['children'] = $district;
           }

           $list[$i]['label'] = $list[$i]['value'] =  $list[$i]['province'];
           unset($list[$i]['province']);
           $list[$i]['children'] = $city;
        }
       return json($list);
    }

    //将查询到的数据数组转换为前端需要的格式(value、label都为值)
    private function arrayHandel(array $arr){
        $list = [];
        foreach ($arr as $value){
            $tmp = ['value'=>$value,'label'=>$value];
            array_push($list,$tmp);
        }
        return $list;
    }

    //将查询到的数据数组转换为前端需要的格式(value为id、label为值)
    private function arrayHandel2(array $arr){
        $list = [];
        foreach ($arr as $key=>$value){
            $tmp = ['value'=>$value,'label'=>$key];
            array_push($list,$tmp);
        }
        return $list;
    }

    //查询某个地区所有的仓库
    public function storehouse($area){
        $storehouse = db('storehouse')->where('area',$area)->column('name');
        $list = $this->arrayHandel($storehouse);
        return json($list);
    }

    //查询某个地区所有的班组
    public function team($area){
        $team = db('team')->where('area',$area)->column('name');
        $list = $this->arrayHandel($team);
        return json($list);
    }

    //查询所有的材料大类
    public function category(){
        $category = db('category')->where(1)->column('category_name');
        $list = $this->arrayHandel($category);
        return json($list);
    }

    //根据材料大类返回材料名称
    public function stuff($category_name){
        $stuff = db('stuff')->where('category_name',$category_name)->column('stuff_name');
        $list = $this->arrayHandel($stuff);
        return json($list);
    }

    //根据材料大类返回材料名称和id
    public function stuffWithId($category_name){
        $stuff = db('stuff')->where('category_name',$category_name)->column('id','stuff_name');
        $list = $this->arrayHandel2($stuff);
        return json($list);
    }
}