<?php

namespace app\common\model;

use think\Model;

class News extends Model
{
    public function getTopAttr($value)
    {
        $state = [0=>'否',1=>'是'];
        return $state[$value];
    }
    public function city()
    {
        return $this->belongsToMany('City','news_city','city_id','news_id');
    }


}
