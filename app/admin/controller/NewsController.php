<?php

namespace app\admin\controller;

use app\common\model\Category;
use app\common\model\News;
use app\common\model\NewsCity;
use think\Controller;
use think\Db;

class NewsController extends TemplateController
{


   public $config = [
     'modelName' => 'app\common\model\News', // 模型字段
     'field' => [
     'id',
     'title',
     'content',
     'title_url',
     'top',
     'derivation',
     'info',
     'sort',
         'category_id',
         'city_id',
     'created_at',
     ], // 查询的字段
     'bars' => [
     'title' => '新闻管理',
     'url' => 'News/index'
     ],
       'del'=>['title'=>'删除新闻','url'=>'News/del'],
       'add'=>['title'=>'添加新闻','url'=>'News/add'],
       'edit'=>['title'=>'编辑新闻','url'=>'News/edit'],
       'delall'=>['title'=>'批量删除','url'=>'News/delall'],
     ];

    // 显示首页
    public function index()
    {
        if ($this->request->isAjax()){
            return $this->getField();
        }
        $this->assign('data',$this->getData());
        return $this->fetch();
    }
    // 获取字段
    public function getField(){
        $model=new $this->config['modelName'];
        $page=input('page')??'1';
        $limit=input('limit')??'10';
        $where=[];
        if (input('id')!=null){
            $paramas=input('id');
            $where['id']=['like','%'.$paramas.'%'];
        }
        $paginate=$model::with('category,city')->field($this->config['field'])->where($where)->paginate($limit,false,['page'=>$page]);
        $data=$paginate->toArray();
         return json(['code'=>0,'msg'=>'','count'=>$data['total'],'data'=>$data['data']]);


    }
    public function getTitle()
    {
        return [[
         ['type'=>'checkbox'],
         ['field'=>'id','title'=>'ID','sort'=>true],
         ['field'=>'title','title'=>'新闻标题'],
            /*
         ['field'=>'content','title'=>'新闻内容'],
         ['field'=>'title_url','title'=>'封面图片'],*/
         ['field'=>'top','title'=>'是否置顶'],
            /*
            ['field'=>'info','title'=>'文章描述'],
            ['field'=>'derivation','title'=>'文章来源'],*/
         ['field'=>'sort','title'=>'排序','sort'=>true],
            ['field'=>'category_id','title'=>'新闻类型','sort'=>true, 'templet'=>'#categoryTpl'],
         ['field'=>'created_at','title'=>'发布时间','sort'=>true],
            ['field'=>'city_id','title'=>'所属城市','sort'=>true,'templet'=>'#cityTpl'],
         ['field'=>'right','title'=>'数据操作','align'=>'center','toolbar'=>'#barDemo','width'=>300],
         ]];

    }


    public function getOption($category=[])
    {
        $city=Db('city')->field('id,name')->select();
         return [
              ['key'=>'title','title'=>'标题','value'=>'','html'=>'text','option'=>['placeholder'=>'请输入标题']],
              ['key'=>'title_url','title'=>'封面图片','value'=>'','html'=>'upload','option'=>''],
             ['key'=>'content','title'=>'新闻内容','value'=>'','html'=>'textarea','option'=>''],
             ['key'=>'info','title'=>'新闻概要','value'=>'','html'=>'text','option'=>['placeholder'=>'请输入新闻概要']],
             ['key'=>'derivation','title'=>'新闻出处','value'=>'','html'=>'text','option'=>['placeholder'=>'新闻出处']],
              ['key'=>'top','title'=>'是否置顶','value'=>'','html'=>'radio','option'=>[
                  ['id'=>1,'name'=>'是'],
                  ['id'=>0,'name'=>'否','check'=>'checked'],
              ]
              ],
              ['key'=>'created_at','title'=>'发布日期','value'=>'','html'=>'date','option'=>''],
             ['key'=>'sort','title'=>'排序','value'=>'0','html'=>'text','option'=>''],
              ['key'=>'city_id','title'=>'归属城市','value'=>'','html'=>'city','option'=>$city],
              ['key'=>'category_id','title'=>'新闻类型','value'=>'','html'=>'category','option'=>$category],

          ];
    }
    //添加
    public function add(){
        if ($this->request->isAjax()){
            $model=new $this->config['modelName'];
            $data=input('post.');
            $result = $this->validate($data,'News.add');
            if(true !== $result){
                return json(['code'=>400,'msg'=>$result]);
            }
            if($model->allowField(true)->save($data)){
             //  $model->city()->sync($data['cities']);
                return  json(['code'=>200,'msg'=>'添加成功']);
            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }
        }
        $city_id=Db('city')->find();
        $category=Db('category')->where('city_id',$city_id['id'])->field('id,category')->select();
        $data['option']=$this->getOption($category);
        $data['config']=$this->config;//获取配置
        $this->assign('data',$data);
        return   $this->fetch();

    }

    //编辑
    public function edit(){

        $model=new $this->config['modelName'];
        if ($this->request->isAjax()){
            $data=input('post.');
            $result = $this->validate($data,'News.add');
            if(true !== $result){
                return json(['code'=>400,'msg'=>$result]);
            }
            if($model->allowField(true)->isUpdate(true)->save($data)){
              return  json(['code'=>200,'msg'=>'修改成功']);
            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }
        }
        $attribute=$model->get(function ($query) {
            $query->where(['id'=>input('id')]);
        });
        $category=Db('category')->where('city_id',$attribute->city_id)->field('id,category')->select();
        $option=$this->getOption($category);
        foreach ($option as $key=>$vo){
            if (isset($attribute[$vo['key']])){
                $option[$key]['value']=$attribute->getData($vo['key']);
            }
        }
        //所属城市
        $option[]=['key'=>'id','title'=>'用户ID','value'=>$attribute['id'],'html'=>'hidden'];
        $data['option']=$option;
        $data['config']=$this->config;
        $this->assign('data',$data);
        return $this->fetch();

    }
    //删除
    public  function del(){
        $model=new $this->config['modelName'];
        $ids=$this->request->post('id');
        if($model::destroy($ids)){
            return  json(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json(['code'=>400,'msg'=>$model->getError]);
        }
    }
    //获取类型
    public function getCategory(){
        if (input('pid')){
            $data=Category::where('city_id',input('pid'))->select();
            return json(['status'=>200,'message'=>'success','data'=>$data]);
        }

}

}
