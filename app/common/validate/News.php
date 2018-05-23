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
    ];

    protected $message  =   [
        'title.require' => '标题必须',
        'content.require'     => '内容不能为空',
        'title_url.require'   => '封面图片不能为空',
        'create_atd.require'   => '创建时间不能为空',
    ];
        protected $scene = [
        'add'  =>  ['title','content','title_url','created_at'],
     //   'edit'  =>  ['username','password','role_id','is_admin'],
    ];

}
