<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/13
 * Time: 23:12
 */

namespace app\index\controller;
use think\Db;

//该类用于地区选择的查询
class AreaQuery extends Base
{
    public function index(){
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
}