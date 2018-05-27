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
        $data=input('get.');
        $result = $this->validate($data,'News.api');
        if(true !== $result){
            return json(['code'=>400,'msg'=>$result]);
        }
       $rs=[];
         //类型
        $rs['category']=Category::where('city_id',$data['city_id'])->order('sort','desc')->select();

        //banner
        $rs['banner']=News::where(['top'=>1,'city_id'=>$data['city_id']])->field(['title_url','id','title','city_id'])->select();
         $sql=News::where('city_id',$data['city_id'])->field(['id','title','title_url','info','sort','city_id','created_at','category_id']);
            if ($this->request->has('category_id')){
                $sql->where('category_id',input('category_id'));
            }
            $rs['news']=$sql->order(['sort'=>'desc','created_at'=>'desc'])->paginate(5);
         return   static ::jsonSuccess($rs);

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
    //切换城市
    public function ListCity(){
        $cities=City::all();
      return  static::jsonSuccess($cities);

    }

}
