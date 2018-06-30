<?php

namespace app\common\model;

use \think\Model;
class GroupModel extends Model
{
    protected $name = 'member_group';
    //开启自动时间戳写入和修改
    protected $autoWriteTimestamp = true; 
    
}
