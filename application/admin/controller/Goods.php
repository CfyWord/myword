<?php
namespace app\admin\controller;

use app\admin\model\UserModel;

class Goods extends Base
{
	//商品列表
    public function index()
    {
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $user = new UserModel();
            $list = $user->alias('u')
                  ->join(config('database.prefix').'role r','u.role_id = r.role_id','left')
                  ->field('u.user_id,u.username,u.last_login_time,u.last_login_ip,u.status,r.name')
                  ->order('u.user_id asc')
                  ->paginate($limit)->toArray();

            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加商品
    public function add()
    {
    	if(request()->isPost()){

    		$param = input('param.');
            $user = new UserModel();
    		$validate = $this->validate($param,'UserValidate');
    		if(true !== $validate){
			   	return json(['code'=>2,'msg'=>$validate,'url'=>"javascript:;"]);
			}else{
				$salt = salt(8);
				$param['password'] = md5(md5(input('param.password')).$salt);
				$param['pwdkey'] = $salt;
				$result = $user->allowField(true)->data($param)->save();
				if(false !== $result){
					return json(['code'=>1,'msg'=>'添加成功','url'=>url('user/index')]);
				}
			}
    	}
    	$role = db('role')->field('role_id,name')->where('status','1')->select();
    	$this->assign('role',$role);
    	return $this->fetch();
    }

    //修改商品
    public function edit()
    {
    	
    	if(request()->isPost()){
    		$param = input('param.');
            $user = new UserModel();
    		$validate = $this->validate($param,'UserValidate.edit');
    		if(true !== $validate){
			   	return json(['code'=>2,'msg'=>$validate,'url'=>"javascript:;"]);
			}else{
				if($param['password'] == ''){
		            unset($param['password']);
		            unset($param['repassword']);
		        }else{
		            $salt = salt(8);
		            $param['pwdkey'] = $salt;	
		            $param['password'] = md5(md5(input('param.password')).$salt);
		        }
				$result = $user->allowField(true)->save($param, ['user_id' => $param['user_id']]);
				if(false !== $result){
	            	return json(['code'=>1,'msg'=>'编辑成功','url'=>url('user/index')]);
	            }
			}
    	}else{
    		$id = input('param.id');
	    	$role = db('role')->field('role_id,name')->where('status','1')->select();
	    	$list = db('user')->field('user_id,username,truename,role_id,status')->where('user_id',$id)->find();
	    	$this->assign('role',$role);
	    	$this->assign('list',$list);
	    	return $this->fetch();
    	}	
    }

    //设置状态status
    public function status()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $result = db('user')->where('user_id',$param['user_id'])->update(['status' =>$param['status']]);
            if(false !== $result){
            	return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }
    	}

    }

    //删除商品
    public function del()
    {
    	if(request()->isPost()){
    		$user_id = trim(input('param.user_id'),',');
            $result = db('user')->where('user_id','in', $user_id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('user/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('user/index')]);
            }
    	}

    }
}
