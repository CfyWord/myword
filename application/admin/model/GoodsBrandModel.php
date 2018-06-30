<?php

namespace app\admin\model;

use \think\Model;
class GoodsBrandModel extends Model
{
    protected $name = 'goods_brand';
    //开启自动时间戳写入和修改
    protected $autoWriteTimestamp = true; 
    
    public function editFiledState($id,$field,$value)
    {
    	$result = $this->where('id',$id)->update([$field =>$value]);

    	return $result;
    }
}
