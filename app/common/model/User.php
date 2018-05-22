<?php

namespace app\common\model;

use think\Model;
use app\common\lib\traits\tokenTrait;
use app\lib\factory\Factory;
//用户模型
class User extends Model
{
    use tokenTrait;


    //用户登录
    public  function  login(){
        if(HUANG_JING != 1)
        {
            $this->setToken();
            return $this->getLoginInfo();
        }
        {
            session('userKey', $this->id);
            return [];
        }
    }
    public function getLoginInfo(){
        return [
            'userId'=>$this->loginKey,
            'token'=>$this->getToken(),
        ];

    }
}
