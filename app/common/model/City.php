<?php

namespace app\common\model;

use think\Model;
use app\common\model\NewsCity;

class City extends Model
{
    public function getDefaultAttr($value)
    {
        $state = [0=>'否',1=>'是'];
        return $state[$value];
    }
    public function city()
    {
        return $this->belongsToMany('News',NewsCity::class);
    }

}
