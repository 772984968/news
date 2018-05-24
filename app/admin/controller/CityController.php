<?php

namespace app\admin\controller;

use app\common\model\News;
use app\common\model\NewsCity;
use think\Controller;

class CityController extends TemplateController
{

   public $config = [
     'modelName' => 'app\common\model\City', // 模型字段
     'field' => [
     'id','name', 'default','sort'
       ],
     'bars' => [
     'title' => '城市管理',
     'url' => 'City/index'
     ],
       'del'=>['title'=>'删除城市','url'=>'City/del'],
       'add'=>['title'=>'添加城市','url'=>'City/add'],
       'edit'=>['title'=>'编辑城市','url'=>'City/edit'],
       'delall'=>['title'=>'批量删除','url'=>'City/delall'],
     ];


    public function getTitle()
    {
        return [[
         ['type'=>'checkbox'],
         ['field'=>'id','title'=>'ID','sort'=>true],
         ['field'=>'name','title'=>'城市名称'],
            ['field'=>'default','title'=>'是否默认','sort'=>true],
            ['field'=>'sort','title'=>'排序','sort'=>true],
         ['field'=>'right','title'=>'数据操作','align'=>'center','toolbar'=>'#barDemo','width'=>300],
         ]];

    }


    public function getOption()
    {
         return [
              ['key'=>'name','title'=>'城市名称','value'=>'','html'=>'text','option'=>['placeholder'=>'请输入城市名称:如：北京']],
             ['key'=>'default','title'=>'是否默认','value'=>'','html'=>'radio','option'=>[
                 ['id'=>1,'name'=>'是'],
                 ['id'=>0,'name'=>'否','check'=>'checked'],
             ]
             ],
             ['key'=>'sort','title'=>'排序','value'=>0,'html'=>'text','option'=>['placeholder'=>'请输入数字，排序默认由大到小']],
             ];
    }

    //添加
    public function add(){
        if ($this->request->isAjax()){
            $model=new $this->config['modelName'];
            if ($model->where('name',$this->request->post('name'))->find()){
                return json(['code'=>400,'msg'=>'城市已存在']);
            }
            if($model->allowField(true)->save(input('post.'))){
                return  json(['code'=>200,'msg'=>'添加成功']);
            }else{
                return json(['code'=>400,'msg'=>$model->getError]);
            }
        }
        $data['option']=$this->getOption();
        $data['config']=$this->config;//获取配置
        $this->assign('data',$data);
        return   $this->fetch('./template/add');

    }
    //删除
    public  function del(){
        $model=new $this->config['modelName'];
        $ids=$this->request->post('id');
        if($model::destroy($ids)){
            NewsCity::where('city_id',$ids)->delete();
            return  json(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json(['code'=>400,'msg'=>$model->getError]);
        }
    }

}
