<?php
namespace app\admin\controller;
class Category extends Base
{
	public function index(){
		if(request()->isAjax()){
			$lists = db('category')->alias('c')
                    ->join(config('database.prefix').'model m','c.mid = m.id','left')
                    ->field('c.*,m.title')
                    ->order('c.sort asc')
                    ->select();
            $list = classify($lists);
            return json(['code'=>0,'msg'=>'获取成功!','data'=>array_values($list)]);
        }
		return $this->fetch();
	}

	//添加栏目
	public function add(){
		if(request()->isPost()){
            $param = input('param.');
            $islast = db('category')->where('id',$param['pid'])->value('islast'); 
            if($islast == 1){
                return json(['code'=>1,'msg'=>'选择的父栏目不能是终极栏目','url'=>'javascript:;']);
            }
            unset($param['file']);
            $result = db('category')->insert($param);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'添加成功','url'=>url('category/index')]);
            }
        }
        $category = db('category')->field('id,name,pid,sort')->order('sort asc')->select();
        $list = classify($category);
        $model = db('model')->field('id,title')->select();
        $this->assign(['category'=>$list,'model'=>$model]);
		return $this->fetch();
	}

    //编辑栏目
    public function edit()
    {
    	
    	if(request()->isPost()){
            $param = input('param.');
            unset($param['file']);
            if($param['id'] == $param['pid']){
                return json(['code'=>2,'msg'=>'父栏目不能是自身栏目，请重新选择。','url'=>'javascript:;']);
            }

            $data = db('category')->field('id,name,pid,sort')->order('sort asc')->select();

            $sonId = cateId($data,$param['id']);
            if(in_array($param['pid'],$sonId)){
                return json(['code'=>2,'msg'=>'父栏目不能是自身的子栏目，请重新选择。','url'=>'javascript:;']);
            }

            if(array_key_exists('images', $param)){
                $cont = db('category')->field('id,images')->where('id',$param['id'])->find(); 
            }
            $result = db('category')->where('id',$param['id'])->update($param);
            if(false !== $result){
                @unlink(ROOT_PATH . 'public' . DS .$cont['images']);
                return json(['code'=>1,'msg'=>'编辑成功','url'=>url('category/index')]);
            }

    	}else{
    		$id = input('param.id');
	    	$category = db('category')->field('id,name,pid,sort')->order('sort asc')->select();
            $category = classify($category);
	        $list = db('category')->where('id',$id)->find();
            $model = db('model')->field('id,title')->select();
            $this->assign(['category'=>$category,'model'=>$model,'list'=>$list]);
	    	return $this->fetch();
    	}	
    }

    //设置导航显示、隐藏
    public function is_show()
    {
        if(request()->isPost()){
            $param = input('param.');
            $result = db('category')->where('id',$param['id'])->update(['is_show' =>$param['is_show']]);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }
        }
    }

    //修改排序
    public function Sort(){
        if(request()->isPost()){
            $id = input('post.id');
            $data['sort'] = input('post.sort');
            $result = db('category')->where('id',$id)->setField($data);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'修改排序成功','url'=>'javascript:;']);
            }
        }
    } 

    //终极栏目转换
    public function Islast(){
        if(request()->isPost()){
            $id = input('post.id');
            $cate = db('category')->alias('c')
                  ->join(config('database.prefix').'model m','c.mid = m.id','left')
                  ->field('c.id,c.mid,c.islast,m.dataname')
                  ->where('c.id',$id)
                  ->find();
            if($cate['islast'] == 1){  //判断当前栏目情况
                $num = db($cate['dataname'])->where('cate_id',$id)->count();
                if($num > 0){
                    return json(['code'=>1,'msg'=>'该栏目下还有多余信息数据，请先删除！','url'=>'javascript:;']);
                }
                $result = db('category')->where('id',$id)->setField('islast', 0);
                if(false !== $result){
                    return json(['code'=>1,'msg'=>'转换栏目属性成功','url'=>url('category/index')]);
                }
            }else{
                $num = db('category')->where('pid',$id)->count();
                if($num > 0){
                    return json(['code'=>2,'msg'=>'该栏目下还有子级，请先删除该栏目下的子级！','url'=>'javascript:;']);
                }
                $result = db('category')->where('id',$id)->setField('islast', 1);
                if(false !== $result){
                    return json(['code'=>1,'msg'=>'转换栏目属性成功','url'=>url('category/index')]);
                }
            }
        }
    } 

    //删除栏目
    public function del()
    {
        if(request()->isPost()){
            $id = trim(input('param.id'),',');
            $num = db('category')->where('pid',$id)->count();
            if($num > 0){
                return json(['code'=>2,'msg'=>'该栏目下还有子级，请先删除该栏目下的子级！','url'=>'javascript:;']);
            }
            $result = db('category')->where('id', $id)->delete();
            if($result){
                return json(['code'=>1,'msg'=>'删除成功','url'=>url('category/index')]);
            }else{
                return json(['code'=>2,'msg'=>'删除失败','url'=>url('category/index')]);
            }
        }

    }
}