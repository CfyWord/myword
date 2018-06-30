<?php

namespace app\admin\model;

use \think\Model;
class UserModel extends Model
{
    protected $name = 'user';
    //开启自动时间戳写入和修改
    protected $autoWriteTimestamp = true; 
    //输出时间格式
    public function getLastLoginTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
}
