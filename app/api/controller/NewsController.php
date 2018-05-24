<?php

namespace app\api\controller;

use app\common\model\City;
use app\common\model\News;
use app\common\model\NewsCity;
use app\Http\Requests\Request;
use app\common\model\Category;
use think\Controller;
use app\lib\factory\Factory;



class NewsController extends BaseController
{
    public function  index(){
       $data=[];
         //类型
        $data['category']=Category::order('sort','desc')->select();
        //banner
        $data['banner']=News::where('top',1)->select();
        if ($this->request->has('city')){
            $city=$this->request->get('city');
            $city=City::where('name',$city)->find();
            $news_ids=NewsCity::where('city_id',$city['id'])->column('news_id');
            $sql=News::where('id','in',$news_ids);
            if ($this->request->has('category_id')){
                $sql->where('category_id',input('category_id'));
            }
            $data['news']=$sql->order('created_at','desc')->paginate(10);
         return   static ::jsonSuccess($data);
        }
        return static::jsonError('参数错误','404');
    }

    //城市定位
    public function location(){
             if ($this->request->has('city')){
            $city=City::where('name',input('city'))->find();
            if ($city){
                return static::jsonSuccess($city);
            }
        }
        $city=City::where('default','gt',0)->find();
        return static::jsonSuccess($city);


    }
    //新闻详情
    public function detail(){
        if ($this->request->has('news_id')){
            $news=News::with('category')->find(input('news_id'));
            if (!$news){
                return static::jsonError('该文章不存在了。',404);
            }
            return static ::jsonSuccess($news);
        }
        return static::jsonError('参数错误');

    }

}
