<?php
namespace app\admin\controller;

class Index extends Base
{
    public function index()
    {
      

    	$list = db('category')->alias('c')
               ->join(config('database.prefix').'model m','c.mid = m.id','left')
               ->field('c.id,c.name,c.pid,c.islast,m.href')
               ->where('c.pid',0)
               ->order('c.sort')
               ->select();
    	$str = '<dl class="layui-nav-child">';
    	foreach ($list as $key => $value) {
    		if($value['islast'] == 1){
    			$str .= "<dd><a href='javascript:;' data-type='tabAdd' data-url='".url($value['href'],'cate_id='.$value['id'])."' class='site-demo-active'><span>".$value['name']."</span></a>";
    		}else{
    			$str .= "<dd><a href='javascript:;'><span>".$value['name']."</span></a>";
    		}
			$str .= $this->menucent($value['id']);
			$str .= '</dd>';
	
    	}
    	$str .= '</dl>';
    	$this->assign('menuc',$str);
        return $this->fetch();
    }

    public function menucent($pid)
    {
    	$list = db('category')->alias('c')
    		   ->join(config('database.prefix').'model m','c.mid = m.id','left')
               ->field('c.id,c.name,c.pid,c.islast,m.href')
               ->where('c.pid',$pid)
               ->order('c.sort')
               ->select();
    	if($list){
    		$str = '<dl class="layui-nav-child">';
	    	foreach ($list as $key => $value){ 
	    		if($value['islast'] == 1){
	    			$str .= "<dd><a href='javascript:;' data-type='tabAdd' data-url='".url($value['href'],'cate_id='.$value['id'])."' class='site-demo-active'><span>".$value['name']."</span></a>";
	    		}else{
	    			$str .= "<dd><a href='javascript:;'><span>".$value['name']."</span></a>";
	    		}
	    		$str .= $this->menucent($value['id']);
	    		$str .= '</dd>';
	    	}
	    	$str .= '</dl>';
	    	return $str;
    	}

    	
    }

    public function main()
    {
        //系统信息
      $systeminfo = config("systeminfo");
      $this->assign(["systeminfo"=>$systeminfo]);
        return $this->fetch();
    }
}
