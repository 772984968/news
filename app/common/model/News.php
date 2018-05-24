<?php

namespace app\common\model;

use think\Model;

class News extends Model
{
    public function getTopAttr($value)
    {
        $state = [
            0=>'否',
            1=>'是'];
        return $state[$value];
    }/*
    public function getCategoryIdAttr($value)
{
    if ($value){
       foreach ( Category::all() as $cate){
           if ($value==$cate['id']){
               return $cate['category'];
           }
       }
    }
    return $value;


}*/
    public function city()
    {
        return $this->belongsToMany('City','news_city','city_id','news_id');
    }
    public function category(){
        return $this->belongsTo('Category','category_id');
    }


}
