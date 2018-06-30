<?php
namespace app\admin\controller;

use app\admin\model\GoodsCategoryModel;
class GoodsCategory extends Base
{
	//商品分类列表
    public function index()
    {
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $GoodsCategoryModel = new GoodsCategoryModel();
            $list = $GoodsCategoryModel ->order("sort asc")->paginate($limit)->toArray();
            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加商品分类
    public function add()
    {   
        $GoodsCategoryModel = new GoodsCategoryModel();
    	if(request()->isPost()){

    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['category_name', 'require', '分类名称不得为空'],
                ['category_name', 'unique:goods_category', '分类名称已存在'],
                ['pid', 'require', '请选择上级分类']
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{

              
               $result = $GoodsCategoryModel->allowField(true)->save($param);
                if($result){
                    return json(['code'=>1,'msg'=>'添加成功','url'=>url('GoodsCategory/index')]);
                }else{
                    return json(['code'=>2,'msg'=>'添加失败','url'=>"javascript:;"]);
                }
            }
    	}

        $goods_category = $GoodsCategoryModel->field('id,category_name,pid,sort')->order('sort asc')->select();
        $goods_category = classify($goods_category);
        $this->assign("goods_category",$goods_category);
    	return $this->fetch();
    }

    //修改热门
    public function is_hot()
    {
        if(request()->isPost()){
            $GoodsCategoryModel = new GoodsCategoryModel();
            $is_hot = input('param.is_hot','0','int');
            $id = input('param.id','','int');

            if(!$id){
                return json(['code'=>2,'msg'=>'请选择操作','url'=>"javascript:;"]);
            }
            $result = $GoodsCategoryModel->editFiledState($id,"is_hot",$is_hot);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }

        }
    }

    //修改热门
    public function is_show()
    {
        if(request()->isPost()){
            $GoodsCategoryModel = new GoodsCategoryModel();
            $is_show = input('param.is_show','0','int');
            $id = input('param.id','','int');

            if(!$id){
                return json(['code'=>2,'msg'=>'请选择操作','url'=>"javascript:;"]);
            }
            $result = $GoodsCategoryModel->editFiledState($id,"is_show",$is_show);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }

        }
    }

    public function edit_sort()
    {   $GoodsCategoryModel = new GoodsCategoryModel();
        $sort = input('param.sort','0','int');
        $id = input('param.id','','int');
        if(!$id){
            return json(['code'=>2,'msg'=>'请选择操作','url'=>"javascript:;"]);
        }
        $result = $GoodsCategoryModel->editFiledState($id,"sort",$sort);
        if(false !== $result){
            return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
        }


    }
    //修改商品分类
    public function edit()
    {
    	$GoodsCategoryModel = new GoodsCategoryModel();
    	if(request()->isPost()){
    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['id', 'require', '请选择操作'],
                ['category_name', 'require', '分类名称不得为空'],
                ['category_name', 'unique:goods_category,category_name^id', '分类名称已存在'],
                ['pid', 'require', '请选择上级分类']
            ]);

            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{

               $category_img=$GoodsCategoryModel->field('category_img')->where('id',$param['id'])->find();

               $result = $GoodsCategoryModel->allowField(true)->save($param,['id'=>$param['id']]);
                if($result){
                    
                    if(input("param.category_img")){
                        //删除图片
                       
                        @unlink(ROOT_PATH.'public'.$category_img['category_img']);
                    }

                    return json(['code'=>1,'msg'=>'编辑成功','url'=>url('GoodsCategory/index')]);

                }else{
                    return json(['code'=>2,'msg'=>'编辑失败','url'=>"javascript:;"]);
                }
            }

    	}else{

    		$id = input('param.id');

	    	$list = $GoodsCategoryModel->where('id',$id)->find();
	    	$this->assign('list',$list);

            $goods_category = $GoodsCategoryModel->field('id,category_name,pid,sort')->order('sort asc')->select();
            $goods_category = classify($goods_category);
            $this->assign("goods_category",$goods_category);

	    	return $this->fetch();
    	}	
    }

    //删除商品分类
    public function del()
    {
    	if(request()->isPost()){
    		$id = trim(input('param.id'),',');
            if(!$id){
                return json(['code'=>2,'msg'=>'请选择操作','url'=>'javascript:;']);
            }
            $GoodsCategoryModel = new GoodsCategoryModel();
            $haveSonclass = $GoodsCategoryModel->where("pid",'in',$id)->count();
            var_dump($haveSonclass);die;
            $category_img=$GoodsCategoryModel->where('id','in', $id)->column('brand_img');
            $result = $GoodsCategoryModel->where('id','in', $id)->delete();
            if($result){
                
                foreach ($category_img as $key => $value) {
                    @unlink(ROOT_PATH.'public'.$value);
                }
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('GoodsCategory/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('GoodsCategory/index')]);
            }
    	}

    }
}
