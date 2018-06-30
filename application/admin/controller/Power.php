<?php
namespace app\admin\controller;

use app\admin\model\PowerModel;

class Power extends Base
{
	//用户列表
    public function index()
    {
    	if(request()->isAjax()){
            $lists = db('power')
                  ->field('id,name,icon,href,pid,sort,status,menustatus')
                  ->order('sort asc')
                  ->select();
            $list = @classify($lists);
            return json(['code'=>0,'msg'=>'获取成功!','data'=>array_values($list)]);
    	}
    	 
        return $this->fetch();
    }

    //添加用户
    public function add()
    {
    	if(request()->isPost()){

    		$param = input('param.');
            $power = new PowerModel();
    		$validate = $this->validate($param,'PowerValidate');
    		if(true !== $validate){
			   	return json(['code'=>2,'msg'=>$validate,'url'=>"javascript:;"]);
			}else{
                $param['href'] = $param['controller'].'/'.$param['action'];
				$result = $power->allowField(true)->data($param)->save();
				if(false !== $result){
					return json(['code'=>1,'msg'=>'添加成功','url'=>url('power/index')]);
				}
			}
    	}

        /*-----------------------获取模型下所有控制器 s---------------------------*/
        $modules_list = get_all_models('admin');
        $this->assign('modules_list',$modules_list);
        /*-----------------------获取模型下所有控制器 e---------------------------*/

    	$power = db('power')->field('id,name,pid,sort')->order('sort asc')->select();
   
        $list = @classify($power);

        
    	$this->assign('power',$list);
    	return $this->fetch();
    }

    //获控制器下面的所有方法
    public function ajax_get_action($control,$module='admin')
    {
        $selectControl = get_all_action($control,$module);

        return json(["selectControl"=>$selectControl]);
    }
    //编辑权限
    public function edit()
    {
    	
    	if(request()->isPost()){
    		$param = input('param.');
            $power = new PowerModel();
            if($param['id'] == $param['pid']){
                return json(['code'=>2,'msg'=>'父栏目不能是自身栏目，请重新选择。','url'=>'javascript:;']);
            }

            $data = db('power')->field('id,name,pid,sort')->order('sort asc')->select();

            $sonId = cateId($data,$param['id']);
            if(in_array($param['pid'],$sonId)){
                return json(['code'=>2,'msg'=>'父栏目不能是自身的子栏目，请重新选择。','url'=>'javascript:;']);
            }
            
    		$validate = $this->validate($param,'PowerValidate');
    		if(true !== $validate){
			   	return json(['code'=>2,'msg'=>$validate,'url'=>"javascript:;"]);
			}else{
                $param['href'] = $param['controller'].'/'.$param['action'];
				$result = $power->allowField(true)->save($param, ['id' => $param['id']]);
				if($result){
	            	return json(['code'=>1,'msg'=>'编辑成功','url'=>url('power/index')]);
	            }
			}
    	}else{
    		$id = input('param.id');
	    	$power = db('power')->field('id,name,pid,sort')->order('sort asc')->select();
            $power = @classify($power);

	        $list = db('power')->field('id,name,icon,href,pid,sort,status,menustatus')->where('id',$id)->find();
            /*-----------------------获取模型下所有控制器 s---------------------------*/
            $modules_list = get_all_models('admin');
            $href = explode("/", $list['href']);
            $controller = $href[0];
            $action = $href[1];
            $action_list = get_all_action($controller,'admin');
            $this->assign([
                'modules_list'=>$modules_list,
                "action_list"=>$action_list,
                'controller'=>$controller,
                "action"=>$action,
                ]);
            /*-----------------------获取模型下所有控制器 e---------------------------*/
	    	$this->assign('power',$power);
	    	$this->assign('list',$list);
	    	return $this->fetch();
    	}	
    }

    //设置状态status
    public function status()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $result = db('power')->where('id',$param['id'])->update(['status' =>$param['status']]);
            if(false !== $result){
            	return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }
    	}
    }

    //设置菜单状态status
    public function menustatus()
    {
        if(request()->isPost()){
            $param = input('param.');
            $result = db('power')->where('id',$param['id'])->update(['menustatus' =>$param['menustatus']]);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }
        }
    }
    //删除用户
    public function del()
    {
    	if(request()->isPost()){
    		$id = trim(input('param.id'),',');
            $num = db('power')->where('pid',$id)->count();
            if($num > 0){
                return json(['code'=>2,'msg'=>'该栏目下还有子级，请先删除该栏目下的子级！','url'=>'javascript:;']);
            }
            $result = db('power')->where('id', $id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('power/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('power/index')]);
            }
    	}

    }
}
