<?php

namespace app\common\model;

use \think\Model;
class MemberModel extends Model
{
    protected $name = 'member';
    //开启自动时间戳写入和修改
    protected $autoWriteTimestamp = true; 
    
}
