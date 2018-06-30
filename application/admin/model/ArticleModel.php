<?php

namespace app\admin\model;

use \think\Model;
class ArticleModel extends Model
{
    protected $name = 'article';
    //开启自动时间戳写入和修改
    protected $autoWriteTimestamp = true; 
    protected $createTime = false;
    //输出时间格式
    public function getLastLoginTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
    protected $type = [
        'create_time'  =>  'timestamp',  //类型转换
    ];
}
