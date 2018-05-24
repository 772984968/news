<?php

namespace app\admin\controller;

use think\Controller;

class CategoryController extends TemplateController
{

   public $config = [
     'modelName' => 'app\common\model\Category', // 模型字段
     'field' => [
     'id','category','sort'
       ],
     'bars' => [
     'title' => '类别管理',
     'url' => 'Category/index'
     ],
       'del'=>['title'=>'删除类别','url'=>'Category/del'],
       'add'=>['title'=>'添加类别','url'=>'Category/add'],
       'edit'=>['title'=>'编辑类别','url'=>'Category/edit'],
       'delall'=>['title'=>'批量删除','url'=>'Category/delall'],
     ];


    public function getTitle()
    {
        return [[
         ['type'=>'checkbox'],
         ['field'=>'id','title'=>'ID','sort'=>true],
         ['field'=>'category','title'=>'类别名称'],
            ['field'=>'sort','title'=>'排序','sort'=>true],
         ['field'=>'right','title'=>'数据操作','align'=>'center','toolbar'=>'#barDemo','width'=>300],
         ]];

    }


    public function getOption()
    {
         return [
              ['key'=>'category','title'=>'类别名称','value'=>'','html'=>'text','option'=>['placeholder'=>'请输入类别名称']],
              ['key'=>'sort','title'=>'排序','value'=>0,'html'=>'text','option'=>['placeholder'=>'请输入数字，排序默认由大到小']],
             ];
    }

    //添加
    public function add(){
        if ($this->request->isAjax()){
            $model=new $this->config['modelName'];
            if ($model->where('category',$this->request->post('category'))->find()){
                return json(['code'=>400,'msg'=>'类别已存在']);
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
}
