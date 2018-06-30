<?php
namespace app\admin\controller;

use app\admin\model\GoodsBrandModel;

class GoodsBrand extends Base
{
	//商品品牌列表
    public function index()
    {
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $GoodsBrandModel = new GoodsBrandModel();
            $list = $GoodsBrandModel ->order("sort asc")->paginate($limit)->toArray();
            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加商品品牌
    public function add()
    {
    	if(request()->isPost()){

    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['brand_name', 'require', '品牌名称不得为空'],
                ['brand_name', 'unique:goods_brand', '品牌名称已存在']
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{

               $GoodsBrandModel = new GoodsBrandModel();
               $result = $GoodsBrandModel->allowField(true)->save($param);
                if($result){
                    return json(['code'=>1,'msg'=>'添加成功','url'=>url('GoodsBrand/index')]);
                }else{
                    return json(['code'=>2,'msg'=>'添加失败','url'=>"javascript:;"]);
                }
            }
    	}
    	return $this->fetch();
    }

    //修改热门
    public function is_hot()
    {
        if(request()->isPost()){
            $GoodsBrandModel = new GoodsBrandModel();
            $is_hot = input('param.is_hot','0','int');
            $id = input('param.id','','int');
            if(!$id){
                return json(['code'=>2,'msg'=>'请选择操作','url'=>"javascript:;"]);
            }
            $result = $GoodsBrandModel->editFiledState($id,"is_hot",$is_hot);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }

        }
    }

    public function edit_sort()
    {   $GoodsBrandModel = new GoodsBrandModel();
        $sort = input('param.sort','0','int');
        $id = input('param.id','','int');
        if(!$id){
            return json(['code'=>2,'msg'=>'请选择操作','url'=>"javascript:;"]);
        }
        $result = $GoodsBrandModel->editFiledState($id,"sort",$sort);
        if(false !== $result){
            return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
        }


    }
    //修改商品品牌
    public function edit()
    {
    	$GoodsBrandModel = new GoodsBrandModel();
    	if(request()->isPost()){
    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['id', 'require', '请选择操作'],
                ['brand_name', 'require', '品牌名称不得为空'],
                ['brand_name', 'unique:goods_brand,brand_name^id', '品牌名称已存在']

            ]);


            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{

               $brand_img=$GoodsBrandModel->field('brand_img')->where('id',$param['id'])->find();

               $result = $GoodsBrandModel->allowField(true)->save($param,['id'=>$param['id']]);
                if($result){
                    
                    if(input("param.brand_img")){
                        //删除图片
                       
                        @unlink(ROOT_PATH.'public'.$brand_img['brand_img']);
                    }

                    return json(['code'=>1,'msg'=>'编辑成功','url'=>url('GoodsBrand/index')]);

                }else{
                    return json(['code'=>2,'msg'=>'编辑失败','url'=>"javascript:;"]);
                }
            }

    	}else{
    		$id = input('param.id');
	    	$list = $GoodsBrandModel->where('id',$id)->find();
	    	$this->assign('list',$list);
	    	return $this->fetch();
    	}	
    }

    //删除商品品牌
    public function del()
    {
    	if(request()->isPost()){
    		$id = trim(input('param.id'),',');
            if(!$id){
                return json(['code'=>2,'msg'=>'请选择操作','url'=>'javascript:;']);
            }
            $GoodsBrandModel = new GoodsBrandModel();
            $brand_img=$GoodsBrandModel->where('id','in', $id)->column('brand_img');
            $result = $GoodsBrandModel->where('id','in', $id)->delete();
            if($result){
                
                foreach ($brand_img as $key => $value) {
                    @unlink(ROOT_PATH.'public'.$value);
                }
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('GoodsBrand/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('GoodsBrand/index')]);
            }
    	}

    }
}
