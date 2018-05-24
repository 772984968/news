<?php

namespace app\admin\controller;

use app\common\model\News;
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
     'sort',
         'category_id',
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


    public function getTitle()
    {
        return [[
         ['type'=>'checkbox'],
         ['field'=>'id','title'=>'ID','sort'=>true],
         ['field'=>'title','title'=>'新闻标题'],
         ['field'=>'content','title'=>'新闻内容'],
         ['field'=>'title_url','title'=>'封面图片'],
         ['field'=>'top','title'=>'是否置顶'],
         ['field'=>'sort','title'=>'排序','sort'=>true],
            ['field'=>'category_id','title'=>'新闻类型','sort'=>true],
         ['field'=>'created_at','title'=>'发布时间','sort'=>true],
         ['field'=>'right','title'=>'数据操作','align'=>'center','toolbar'=>'#barDemo','width'=>300],
         ]];

    }


    public function getOption()
    {
        $city=Db('city')->field('id,name')->select();
        $category=Db('category')->field('id,category')->select();
         return [
              ['key'=>'title','title'=>'标题','value'=>'','html'=>'text','option'=>['placeholder'=>'请输入标题']],
              ['key'=>'title_url','title'=>'封面图片','value'=>'','html'=>'upload','option'=>''],
             ['key'=>'content','title'=>'新闻内容','value'=>'','html'=>'textarea','option'=>''],
              ['key'=>'top','title'=>'是否置顶','value'=>'','html'=>'radio','option'=>[
                  ['id'=>1,'name'=>'是'],
                  ['id'=>0,'name'=>'否','check'=>'checked'],
              ]
              ],
              ['key'=>'created_at','title'=>'发布日期','value'=>'','html'=>'date','option'=>''],
             ['key'=>'sort','title'=>'排序','value'=>'0','html'=>'text','option'=>''],
              ['key'=>'cities','title'=>'归属城市','value'=>'','html'=>'checkbox','option'=>$city],
              ['key'=>'category_id','title'=>'新闻类型','value'=>'','html'=>'select','option'=>$category],

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

                $model->city()->attach($data['cities']);
                return  json(['code'=>200,'msg'=>'添加成功']);
            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }
        }
        $data['option']=$this->getOption();
        $data['config']=$this->config;//获取配置
        $this->assign('data',$data);
        return   $this->fetch();

    }

    //编辑
    public function edit(){

        $model=new $this->config['modelName'];
        if ($this->request->isAjax()){
            $data=input('post.');
            $model=new $this->config['modelName'];

            $result = $this->validate($data,'News.add');
            if(true !== $result){
                return json(['code'=>400,'msg'=>$result]);
            }
            if($model->allowField(true)->isUpdate(true)->save($data)){

                $model->city()->sync($data['cities']);
                return  json(['code'=>200,'msg'=>'修改成功']);
            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }
        }
        $attribute=$model->get(function ($query) {
            $query->where(['id'=>input('id')]);
        },'city');
        $option=$this->getOption();
        foreach ($option as $key=>$vo){
            if (isset($attribute[$vo['key']])){
                $option[$key]['value']=$attribute->getData($vo['key']);
            }
        }

        //所属城市
        $city=array_column($attribute->city,'id');
        $option[]=['key'=>'id','title'=>'用户ID','value'=>$attribute['id'],'html'=>'hidden'];
        $data['option']=$option;
        $data['config']=$this->config;
        $data['city']=$city;
        var_dump($data);die;
        $this->assign('data',$data);
        return $this->fetch();

    }

}
