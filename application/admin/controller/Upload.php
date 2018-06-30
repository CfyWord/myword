<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class Upload extends Base
{

	public function image(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        $config = db('system')->field('imgtype,imgsize')->where('id', '1')->find();
        // 移动到框架应用根目录/public/uploads/images/ 目录下
        $info = $file->validate(['size'=>$config['imgsize']*1024,'ext'=>$config['imgtype']])->move(ROOT_PATH . 'public' . DS . 'uploads/images');
        if($info){
            return json(['code'=>1,'msg'=>'图片上传成功!','url'=>'/uploads/images/'.$info->getSaveName()]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>2,'msg'=>$file->getError()]);
        }
    }

    //上传图片
    public function picture(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        $config = db('system')->field('imgtype,imgsize,openmark,markpath,markpos,markpct,marktext,markfontsize,markfontcolor,markquality,markfont,markfontoffset,markfontangle')->where('id', '1')->find();
        // 移动到框架应用根目录/public/uploads/images/ 目录下
        $info = $file->validate(['size'=>$config['imgsize']*1024,'ext'=>$config['imgtype']])->move(ROOT_PATH . 'public' . DS . 'uploads/images'); 
        if($info){
        	//给图片添加水印
	        if($config['openmark'] == 1){
	        	$imgurl = ROOT_PATH . 'public' . DS . 'uploads/images/'.$info->getSaveName(); //图片路径
  				$this->mark($config,$imgurl);
	        }
            return json(['code'=>1,'msg'=>'图片上传成功!','url'=>'/uploads/images/'.$info->getSaveName()]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>2,'msg'=>$file->getError()]);
        }
    }

    //上传文件
    public function file(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        $config = db('system')->field('filetype,filesize')->where('id', '1')->find();
        // 移动到框架应用根目录/public/uploads/images/ 目录下
        $info = $file->validate(['size'=>$config['filesize']*1024,'ext'=>$config['filetype']])->move(ROOT_PATH . 'public' . DS . 'uploads/file'); 
        if($info){
            return json(['code'=>1,'msg'=>'文件上传成功!','url'=>'/uploads/file/'.$info->getSaveName()]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>2,'msg'=>$file->getError()]);
        }
    }

    //编辑器图片上传
    public function editUpload(){
        // 获取表单上传文件 
        $file = request()->file('file');
        $config = db('system')->field('imgtype,imgsize,openmark,markpath,markpos,markpct,marktext,markfontsize,markfontcolor,markquality,markfont,markfontoffset,markfontangle')->where('id', '1')->find();
        // 移动到框架应用根目录/public/uploads/images/ 目录下
        $info = $file->validate(['size'=>$config['imgsize']*1024,'ext'=>$config['imgtype']])->move(ROOT_PATH . 'public' . DS . 'uploads/images'); 
        if($info){
            //给图片添加水印
            if($config['openmark'] == 1){
                $imgurl = ROOT_PATH . 'public' . DS . 'uploads/images/'.$info->getSaveName(); //图片路径
                $this->mark($config,$imgurl);
            }
            $data['src'] = '/uploads/images/'.$info->getSaveName();
            $data['title'] = '/uploads/images/'.$info->getSaveName();
            return json(['code'=>0,'msg'=>'图片上传成功!','data'=>$data]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>1,'msg'=>$file->getError()]);
        }
    }
    //图片添加水印
    public function mark($config,$imgurl){
    	$markurl = ROOT_PATH . 'public' . DS . $config['markpath'];  //水印图片路径

    	$fonturl = ROOT_PATH . 'public' . DS . $config['markfont'];  //文字字体路径

    	$image = \think\Image::open($imgurl);
		$image->water($markurl,$config['markpos'],$config['markpct']); //图片水印

		$image->text($config['marktext'],$fonturl,$config['markfontsize'],$config['markfontcolor'],$config['markpos'],$config['markfontoffset'],$config['markfontangle']); //文字水印

		$image->save($imgurl,null,$config['markquality']);
    }
}