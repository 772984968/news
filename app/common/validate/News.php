<?php
namespace app\common\validate;
use think\Validate;
//新闻验证类
class News extends Validate{

    protected $rule =   [
        'title'  => 'require',
        'content'   => 'require',
        'title_url'=>'require',
        'created_at' => 'require',
        'category_id' => 'require',
        'info' => 'max:100',
        'city_id'=>'require',
    ];

    protected $message  =   [
        'title.require' => '标题必须',
        'content.require'     => '内容不能为空',
        'title_url.require'   => '封面图片不能为空',
        'created_at.require'   => '创建时间不能为空',
        'category_id.require'   => '新闻类型不能为空',
        'info.max'   => '描述内容不能超过100个字',
        'city_id.require'   => '城市名不能为空',
    ];
        protected $scene = [
        'add'  =>  ['title','content','title_url','created_at','category_id','info'],
        'api'  =>  ['city_id'],
     //   'edit'  =>  ['username','password','role_id','is_admin'],
    ];

}
