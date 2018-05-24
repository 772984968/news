<?php

namespace app\common\model;

use think\Model;

class Category extends Model
{
    protected $field = true;
    public function news(){
        return $this->hasMany('News');
    }


}
