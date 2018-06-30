<?php
namespace app\admin\controller;
use app\admin\model\ArticleModel;
class Article extends Base
{
	//文章列表
    public function index()
    {    
    	if(request()->isAjax()){
    		$article = new ArticleModel();
    		$cate_id = input('param.cate_id');
            $page = input('param.page');
            $limit = input('param.limit');
            $list =$article->alias('a')
                  ->join(config('database.prefix').'category c','a.cate_id = c.id','left')
                  ->field('a.*,c.name')
                  ->where('a.cate_id',$cate_id)
                  ->order('a.id desc')
                  ->paginate($limit)->toArray();
            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
        }
        $id = input('param.cate_id');
        $category = db('category')->field('id,name,pid,sort')->order('sort asc')->select();
        $catenav = array_reverse(catenav($category,$id));
        $this->assign('catenav',$catenav);
    	return $this->fetch();
    }

    //添加文章
    public function add()
    {
    	$article = new ArticleModel();
        if(request()->isPost()){
            $param = input('param.');
            $validate = new \think\Validate([
                ['title', 'require', '标题不能为空'],
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
        		$param['user_id'] = session('wx_user_id');
        		$param['username'] = session('wx_username');
                $result = $article->allowField(true)->save($param);
                if(false !== $result){
                    return json(['code'=>1,'msg'=>'添加成功','url'=>url('article/index','cate_id='.$param['cate_id'])]);
                }
            }
        }
        $id = input('param.cate_id');
        $category = db('category')->field('id,name,pid,sort')->order('sort asc')->select();
        $catenav = array_reverse(catenav($category,$id));
        $this->assign('catenav',$catenav);
    	return $this->fetch();
    }

    //编辑文章
    public function edit()
    {
    	$article = new ArticleModel();
    	if(request()->isPost()){
            $param = input('param.');
            $validate = new \think\Validate([
                ['title', 'require', '标题不能为空'],
            ]);
            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
            	if(array_key_exists('picture', $param)){
               		$cont = $article->field('id,picture')->where('id',$param['id'])->find(); 
            	}
                $result = $article->allowField(true)->save($param, ['id' => $param['id']]);
                if(false !== $result){
                	@unlink(ROOT_PATH . 'public' . DS .$cont['picture']);
                    return json(['code'=>1,'msg'=>'编辑成功','url'=>url('article/index','cate_id='.$param['cate_id'])]);
                }
            }
        }
    	$id = input('param.id');
        $list = $article->where('id', $id)->find();
        $cate_id = input('param.cate_id');
        $category = db('category')->field('id,name,pid,sort')->order('sort asc')->select();
        $catenav = array_reverse(catenav($category,$cate_id));
        $this->assign('catenav',$catenav);
        $this->assign('list',$list);
    	return $this->fetch();
    }

    //设置状态status
    public function status()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $result = db('article')->where('id',$param['id'])->update(['status' =>$param['status']]);
            if(false !== $result){
            	return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }
    	}

    }

    //设置推荐状态
    public function isgood()
    {
    	if(request()->isPost()){
    		$article = new ArticleModel();
    		$param = input('post.data/a');
    		$cate_id = input('param.cate_id');
            $result = $article->isUpdate()->saveAll($param);
            if(false !== $result){
            	return json(['code'=>1,'msg'=>'设置成功','url'=>url('article/index','cate_id='.$cate_id)]);
            }
    	}

    }

    //设置置顶状态
    public function istop()
    {
    	if(request()->isPost()){
    		$article = new ArticleModel();
    		$param = input('post.data/a');
    		$cate_id = input('param.cate_id');
            $result = $article->isUpdate()->saveAll($param);
            if(false !== $result){
            	return json(['code'=>1,'msg'=>'设置成功','url'=>url('article/index','cate_id='.$cate_id)]);
            }
    	}

    }

    //删除信息
    public function del()
    {
    	if(request()->isPost()){
    		$id = trim(input('param.id'),',');
    		$cate_id = input('param.cate_id');
            $result = db('article')->where('id','in', $id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('article/index','cate_id='.$cate_id)]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('article/index','cate_id='.$cate_id)]);
            }
    	}

    }
}