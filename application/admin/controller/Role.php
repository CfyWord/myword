<?php
namespace app\admin\controller;

use app\admin\model\RoleModel;

class Role extends Base
{
	//用户列表
    public function index()
    {
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $list = db('role')
                  ->field('role_id,name,status')
                  ->order('role_id asc')
                  ->paginate($limit)->toArray();

            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加用户
    public function add()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $role = new RoleModel();
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['name', 'require', '角色名称不能为空！'],
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
                $result = $role->allowField(true)->data($param)->save();
                if(false !== $result){
                    return json(['code'=>1,'msg'=>'添加成功','url'=>url('role/index')]);
                }
            }
    	}
        $power = db('power')->field('id,name,pid,sort')->order('sort asc')->select();

        $this->assign("power",json_encode($power,JSON_UNESCAPED_UNICODE));
    	return $this->fetch();
    }

    //修改用户
    public function edit()
    {
    	
    	if(request()->isPost()){
    		$param = input('param.');
            $role = new RoleModel();
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['name', 'require', '角色名称不能为空！'],
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
                $result = $role->allowField(true)->save($param, ['role_id' => $param['role_id']]);
                if(false !== $result){
                    return json(['code'=>1,'msg'=>'编辑成功','url'=>url('role/index')]);
                }
            }
    	}else{
    		$id = input('param.id');
	    	$list = db('role')->where('role_id',$id)->find();


            $power = db('power')->field('id,name,pid,sort')->order('sort asc')->select();
            $array = explode(',', $list['power']);

            foreach ($power as $key => $value) {
               
               if(in_array($value['id'], $array)){
                  $power[$key]['checked'] = true;
                  $power[$key]['open'] = true;
               }
            }
            $this->assign("power",json_encode($power,JSON_UNESCAPED_UNICODE));

	    	$this->assign('list',$list);
	    	return $this->fetch();
    	}	
    }

    //设置状态status
    public function status()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $result = db('role')->where('role_id',$param['role_id'])->update(['status' =>$param['status']]);
            if(false !== $result){
            	return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }
    	}

    }

    //删除用户
    public function del()
    {
    	if(request()->isPost()){
    		$role_id = trim(input('param.role_id'),',');
            $result = db('role')->where('role_id', $role_id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('role/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('role/index')]);
            }
    	}

    }
}
