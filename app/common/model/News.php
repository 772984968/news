<?php

namespace app\common\model;

use think\Model;

class News extends Model
{
    protected $field = true;
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
        return $this->belongsTo('City');
    }
    public function category(){
        return $this->belongsTo('Category','category_id');
    }


}
