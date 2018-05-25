<?php

namespace app\common\model;

use think\Model;
use app\common\model\NewsCity;

class City extends Model
{
    protected $field = true;
    public function getDefaultAttr($value)
    {
        $state = [0=>'否',1=>'是'];
        return $state[$value];
    }
    public function news()
    {
        return $this->hasMany('News');
    }

}
