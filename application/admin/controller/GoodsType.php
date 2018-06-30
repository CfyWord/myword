<?php
namespace app\admin\controller;

use app\admin\model\GoodsBrandModel;

class GoodsType extends Base
{
	//商品模型列表
    public function index()
    {
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $list = db("goods_type") ->paginate($limit)->toArray();
            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加商品模型
    public function add()
    {
    	if(request()->isPost()){

    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['type_name', 'require', '模型名称不得为空'],
                ['type_name', 'unique:goods_type', '模型名称已存在']
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
               $result = db("goods_type")->insert(['type_name'=>$param['type_name']]);
                if($result){
                    return json(['code'=>1,'msg'=>'添加成功','url'=>url('GoodsType/index')]);
                }else{
                    return json(['code'=>2,'msg'=>'添加失败','url'=>"javascript:;"]);
                }
            }
    	}
    	return $this->fetch();
    }

   
    //修改商品模型
    public function edit()
    {
    	
    	if(request()->isPost()){
    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['id', 'require', '请选择操作'],
                ['type_name', 'require', '模型名称不得为空'],
                ['type_name', 'unique:goods_type,type_name^id', '模型名称已存在']
            ]);


            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{

               $result = db("goods_type")->where('id',$param['id'])->update(['type_name'=>$param['type_name']]);
                if($result!==false){ 
                    return json(['code'=>1,'msg'=>'编辑成功','url'=>url('GoodsType/index')]);
                }else{
                    return json(['code'=>2,'msg'=>'编辑失败','url'=>"javascript:;"]);
                }
            }

    	}else{
    		$id = input('param.id');
	    	$list = db("goods_type")->where('id',$id)->find();
	    	$this->assign('list',$list);
	    	return $this->fetch();
    	}	
    }

    //删除商品模型
    public function del()
    {
    	if(request()->isPost()){
    		$id = trim(input('param.id'),',');
            if(!$id){
                return json(['code'=>2,'msg'=>'请选择操作','url'=>'javascript:;']);
            }
            $result = db("goods_type")->where('id','in', $id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('GoodsType/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('GoodsType/index')]);
            }
    	}

    }
}
