<?php
namespace app\admin\controller;

use app\common\model\GroupModel;

class Group extends Base
{
	//会员组列表
    public function index()
    {
        
     
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $GroupModel = new GroupModel();
            $list = $GroupModel
                  ->order('id asc')
                  ->paginate($limit)->toArray();

            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加会员组
    public function add()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $Group = new GroupModel();
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['group_name', 'require', '会员组名称'],
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
                $result = $Group->allowField(true)->data($param)->save();
                if(false !== $result){
                    return json(['code'=>1,'msg'=>'添加成功','url'=>url('Group/index')]);
                }
            }
    	}
    	return $this->fetch();
    }

    //修改会员组
    public function edit()
    {
    	$Group = new GroupModel();
    	if(request()->isPost()){
    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['group_name', 'require', '角色名称不能为空！'],
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
                $result = $Group->allowField(true)->save($param, ['id' => $param['id']]);
                if(false !== $result){
                    return json(['code'=>1,'msg'=>'编辑成功','url'=>url('Group/index')]);
                }
            }
    	}else{
    		$id = input('param.id');
	    	$list = $Group->where('id',$id)->find();
	    	$this->assign('list',$list);
	    	return $this->fetch();
    	}	
    }

    //删除会员组
    public function del()
    {
    	if(request()->isPost()){
            $Group = new GroupModel();
    		$id = trim(input('param.id'),',');
            $result = $Group->where('id', $id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('Group/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('Group/index')]);
            }
    	}

    }
}
