<?php

namespace app\common\model;

use think\Model;

class Category extends Model
{
    public function news(){
        return $this->hasMany('News');
    }


}
