<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//获取模型下的所有控制器
function get_all_models($module='admin')
{

    $planPath = APP_PATH.$module.'/controller';
    $modules_list = array();
    $dirRes   = opendir($planPath);
    //循环遍历php文件获取名称
    while($dir = readdir($dirRes))
    {
        if(!in_array($dir,array('.','..','.svn')))
        {
            $modules_list[] = basename($dir,'.php');
        }
    }

    return $modules_list;
    /*-----------------------获取模型下所有控制器 e---------------------------*/
}
//获取控制器下所有方法
function get_all_action($control,$module='admin')
{
    if(!$control){

        return "";
     }
     $selectControl = [];
     $className = "app\\".$module."\\controller\\".$control;
     $methods = (new \ReflectionClass($className))->getMethods(\ReflectionMethod::IS_PUBLIC);
     foreach ($methods as $method) {
         if ($method->class == $className) {
             if ($method->name != '__construct' && $method->name != '_initialize') {
                 $selectControl[] = $method->name;
             }
         }
    }

    return $selectControl;
}
/**
  * 随机生成字符串
  * @param {number}length 字符串长度
*/
function salt( $length = 8 ) 
{ 
	// 密码字符集，可任意添加你需要的字符 
	$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 
	'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's', 
	't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D', 
	'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 
	'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z', 
	'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!', 
	'@','#', '$', '%', '^', '&', '*', '(', ')', '-', '_', 
	'[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',', 
	'.', ';', ':', '/', '?', '|'); 
  
	// 在 $chars 中随机取 $length 个数组元素键名 
	$keys = count($chars) - 1; 
	shuffle($chars); 
	$str = ''; 
	for($i = 0; $i < $length; $i++) 
	{ 
		// 将 $length 个数组元素连接成字符串 
		$str.= $chars[mt_rand(0, $keys)];  
	}  
	return $str; 
}

/**
  * 无限分类 
  * @param {array}   arr 数组
  * @param {number}  id  id
*/
function classify($arr, $id = '' ,$lefthtml = '|— ')
{   
    if(!$arr){

        return "";
    }
    $pid = $arr[0]['pid'];
    $arr_assert = [];//辅助数组
    $arr_new = [];
    foreach ($arr as $value) {
        $arr_new[$value['id']] = $value;
        $arr_new[$value['id']]['num'] = 1; //自己及其子代的数量
        if(! isset($arr_assert[$value['pid']])){
            $arr_assert[$value['pid']]['start'] = 0;
            $arr_assert[$value['pid']]['end'] = 0; 
        }
        $arr_assert[$value['pid']][] = $value['id'];
        ++$arr_assert[$value['pid']]['end'];
    }
    $result = [];//返回的结果集
    $count = count($arr_new); 
    $offset = 0;
    $level = 0; //显示层级
    while ($offset<$count) {
        if(isset($arr_assert[$pid]) && $arr_assert[$pid]['start'] < $arr_assert[$pid]['end']){
            $index = $arr_assert[$pid]['start'];
            ++$arr_assert[$pid]['start'];    
            $pid = $arr_assert[$pid][$index];
            $arr_new[$pid]['offset'] = $offset;
            $arr_new[$pid]['level'] = $level;
            $arr_new[$pid]['lefthtml'] = str_repeat($lefthtml,$level);
            $result[$pid] = &$arr_new[$pid];
            ++$level;
            ++$offset;   
        }else{
            --$level;
            $num = $arr_new[$pid]['num'];
            $pid = $arr_new[$pid]['pid'];
            if($pid!==0){
                $arr_new[$pid]['num'] += $num;
            }
        }          
    }
    if($id){
        return array_slice($result, $arr_new[$id]['offset'],$arr_new[$id]['num']); //返回某个id的自身及其所有子代的记录
    }
    return $result;
}

/**
  * 获取某个分类下的所有分类id
  * @param {array}   array 数组
  * @param {number}  id  id
*/
function cateId($array,$id){
    $result = [];
    foreach ($array as $key => $value) {
        if ($value['pid'] == $id) {
            $result[] = $value['id'];
            $result   = array_merge($result, cateId($array, $value['id']));
        }
    }
    return $result;
}

/**
  * 获取某个分类的所有父级
  * @param {array}   array 数组
  * @param {number}  id  id
*/
function catenav($arr,$id){  
    $tree = [];  
    while($id != 0){  
        foreach($arr as $item){  
            if($item['id'] == $id){  
                $tree[] = $item;  
                $id = $item['pid'];  
                break;    
            }  
        }  
    }  
    return $tree;  
}  
  
/**
  * 邮件发送
  * @param {str}  address    邮件地址
  * @param {str}  title      邮件标题
  * @param {str}  content    邮件内容
*/
function SendMail($address,$title = '',$content = '')
{
    require_once('../extend/PHPMailer/PHPMailer/PHPMailer.php');
    require_once('../extend/PHPMailer/PHPMailer/Exception.php');

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    $data = db('mail')->where('id','1')->find();

    $mail->isSMTP();// 使用SMTP服务
    $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
    $mail->Host = $data['smtphost'];// 发送方的SMTP服务器地址
    $mail->SMTPAuth = true;// 是否使用身份验证
    $mail->Username = $data['emailusername'];// 发送方的邮箱用户名，就是自己的邮箱名
    $mail->Password = $data['emailpassword'];// 发送方的邮箱密码，不是登录密码,是第三方授权登录码,要自己去开启,在邮箱的设置->账户->POP3/IMAP/SMTP/Exchange/CardDAV/CalDAV服务 里面
    $mail->SMTPSecure = "ssl";// 使用ssl协议方式,
    $mail->Port = $data['smtpport'];// QQ邮箱的ssl协议方式端口号是465/587
    $mail->setFrom($data['fromemail'],$data['emailname']);// 设置发件人信息，如邮件格式说明中的发件人,
    $mail->addAddress($address);// 设置收件人信息，如邮件格式说明中的收件人

    $emailtitle = $title ? $title : $data['emailtitle'];
    $emailcontent = $content ? $content : $data['emailcontent'];

    $mail->Subject = $emailtitle;// 邮件标题
    $mail->Body = $emailcontent;// 邮件正文
    //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用
    return($mail->Send());

    // if(!$mail->send()){// 发送邮件
    //     echo "Message could not be sent.";
    //     echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
    // }else{
    //     echo '发送成功';
    // }
}

/**
  * 短信发送
  * @param {str}   phone    手机号码
  * @param {arr} TempValue   模板参数值     
*/
function SendSms($phone,$TempValue = '') 
{
    require_once('../extend/aliyun/SignatureHelper.php');
    $params = array ();

    // *** 需用户填写部分 ***
    
    $data = db('aliyun_sms')->where('id','1')->find();

    // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    $accessKeyId = $data['accessKeyId'];
    $accessKeySecret = $data['accessKeySecret'];
    // fixme 必填: 短信接收号码
    $params["PhoneNumbers"] = $phone;

    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    $params["SignName"] = $data['SignName'];

    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
    $params["TemplateCode"] = $data['TemplateCode'];

    // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
    $params['TemplateParam'] = Array (
        $data['TemplateParam'] => $TempValue
    );

    // fixme 可选: 设置发送短信流水号
    $params['OutId'] = "12345";

    // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
    $params['SmsUpExtendCode'] = "1234567";


    // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
        $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
    }

    // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
    $helper = new Aliyun\DySDKLite\SignatureHelper();

    // 此处可能会抛出异常，注意catch
    $content = $helper->request(
        $accessKeyId,
        $accessKeySecret,
        "dysmsapi.aliyuncs.com",
        array_merge($params, array(
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
        ))
        // fixme 选填: 启用https
        // ,true
    );

    return $content;
}
