<?php
namespace app\admin\controller;

use app\admin\model\GoodsBrandModel;
use app\common\model\SpecModel;

class Spec extends Base
{
	//商品规格列表
    public function index()
    {
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $SpecModel = new SpecModel();
            $list = $SpecModel ->paginate($limit)->toArray();

            foreach ($list['data'] as $key => $value) {
                    $item = db("spec_item")->where("spec_id", $value['id'])->column("item");
                    $list['data'][$key]['item'][] = $item?implode(",", $item):"";
            }
            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加商品规格
    public function add()
    {
    	if(request()->isPost()){

    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['spec_name', 'require', '规格名称不得为空'],
                ['spec_name', 'unique:spec', '规格名称已存在'],
                ['type_id', 'require', '请选择模型']
            ]);
            $spec_item = input("param.spec_item");
            if(!$spec_item){
                return json(['code'=>2,'msg'=>"请填写规格项",'url'=>"javascript:;"]);
            }
            $spec_item = explode(PHP_EOL, trim($spec_item,"\n"));
            var_dump($spec_item);die;
            if (!$validate->check($param['spec'])) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{
                $SpecModel = new SpecModel();
                $result = $SpecModel->allowField(true)->insertGetId($param['spec']);
                if($result){
                    //增加规格项
                    foreach ($spec_item as $key => $value) {
                      $data[$key]['spec_id'] = $result;
                      $data[$key]['item'] = $value;
                    }
                    db("spec_item")->insertAll($data);
                    return json(['code'=>1,'msg'=>'添加成功','url'=>url('Spec/index')]);
                }else{
                    return json(['code'=>2,'msg'=>'添加失败','url'=>"javascript:;"]);
                }
            }
    	}
        $goods_type = db("goods_type")->select();
        $this->assign("goods_type",$goods_type);
    	return $this->fetch();
    }

   
    //修改商品规格
    public function edit()
    {
    	$SpecModel = new SpecModel();
    	if(request()->isPost()){
    		$param = input('param.');
            //验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = new \think\Validate([
                ['id', 'require', '请选择操作'],
                ['type_name', 'require', '规格名称不得为空'],
                ['type_name', 'unique:goods_type,type_name^id', '规格名称已存在']
            ]);


            if (!$validate->check($param)) {
                return json(['code'=>2,'msg'=>$validate->getError(),'url'=>"javascript:;"]);
            }else{

               $result = db("goods_type")->where('id',$param['id'])->update(['type_name'=>$param['type_name']]);
                if($result!==false){ 
                    return json(['code'=>1,'msg'=>'编辑成功','url'=>url('Spec/index')]);
                }else{
                    return json(['code'=>2,'msg'=>'编辑失败','url'=>"javascript:;"]);
                }
            }

    	}else{
            $id = input('param.id');
            $list = $SpecModel->where("id",$id)->find();
            $item = db("spec_item")->where("spec_id",$id)->select();
    		$goods_type = db("goods_type")->select();
            $this->assign("list",$list);
            $this->assign("item",$item);
            $this->assign("goods_type",$goods_type);
	    	return $this->fetch();
    	}	
    }

    //删除商品规格
    public function del()
    {
    	if(request()->isPost()){
    		$id = trim(input('param.id'),',');
            if(!$id){
                return json(['code'=>2,'msg'=>'请选择操作','url'=>'javascript:;']);
            }
            $result = db("goods_type")->where('id','in', $id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('Spec/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('Spec/index')]);
            }
    	}

    }
}
