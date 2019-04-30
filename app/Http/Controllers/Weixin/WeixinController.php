<?php

namespace App\Http\Controllers\Weixin;

use App\Model\UserModel;
use App\Model\Goods;
use App\Model\WeixinChatModel;
use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;

use App\Model\WeixinMedia;

class WeixinController extends Controller
{
    //

    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    protected $redis_weixin_jsapi_ticket = 'str:weixin_jsapi_ticket';     //微信 jsapi_ticket

    public function test()
    {
        //echo __METHOD__;
        echo 'Token: '. $this->getWXAccessToken();
    }

    /**
     * 首次接入
     */
    public function validToken1()
    {
        // $arr = $_REQUEST;
        // $str = var_export($arr,true);
        // file_put_contents('logs/wx_event.log',$str,FILE_APPEND);
        // define("TOKEN",'zhanghengrui');
        // $signature =$arr['signature'];
        // $nonce = $arr['nonce'];
        // $timestamp=$arr['timestamp'];
        // $newArr = array(TOKEN,$timestamp,$nonce);
        // sort($newArr,SORT_STRING);
        // $strnews = implode($newArr);
        // $newstr = sha1($strnews);
        // if($newstr == $signature){
        //     $str =$arr['echostr'];
        //     echo $str;
        // }
        echo $_GET['echostr'];
        $str = file_get_contents("php://input");
        file_put_contents('logs/wx_event.log',$str,FILE_APPEND);
        //$xml = simplexml_load_string($str);
        //$openid = $xml->FromUserName;
        //file_put_contents('logs/wx_event.log',$openid,FILE_APPEND);
    }

    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent()
    {
        $data = file_get_contents("php://input");


        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);

        $event = $xml->Event;                       //事件类型
        $openid = $xml->FromUserName;              //用户openid


        // 处理用户发送消息
        if(isset($xml->MsgType)){
            if($xml->Content=='图文消息'){            //用户发送文本消息
                // $msg = $xml->Content;
                // //记录聊天消息

                // $data = [
                //     'msg'       => $xml->Content,
                //     'msgid'     => $xml->MsgId,
                //     'openid'    => $openid,
                //     'msg_type'  => 1        // 1用户发送消息 2客服发送消息
                // ];

                // $id = WeixinChatModel::insertGetId($data);
                // var_dump($id);
                // $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. $msg. date('Y-m-d H:i:s') .']]></Content></xml>';


                // echo $xml_response;
                $PicUrl='http://mmbiz.qpic.cn/mmbiz_jpg/6VN1HNqJyI2FIV0bQk1TMMSwCSxmy8ick9uAOFYJbKLExS9YMlphD7c3wKibQWr1oqIalQQjf2YLWmHEjVlic1XXw/0';
                $url='https://www.baidu.com';
                $xml_response ='<xml>
                                  <ToUserName><![CDATA['.$openid.']]></ToUserName>
                                  <FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName>
                                  <CreateTime>'.time().'</CreateTime>
                                  <MsgType><![CDATA[news]]></MsgType>
                                  <ArticleCount>1</ArticleCount>
                                  <Articles>
                                    <item>
                                      <Title><![CDATA[tuwen]]></Title>
                                      <Description><![CDATA[wenzi]]></Description>
                                      <PicUrl><![CDATA['.$PicUrl.']]></PicUrl>
                                      <Url><![CDATA['.$url.']]></Url>
                                    </item>
                                  </Articles>
                                </xml>';
                                echo $xml_response;
            }elseif($xml->MsgType=='text'){
                $content=$xml->Content;
                $u = Goods::where(['goods_name'=>$content])->first()->toarray();
                $goods_name=$u['goods_name'];
                $goods_content=$u['goods_content'];
                $goods_img=$u['goods_img'];
                $url='https://www.baidu.com';
                $xml_response ='<xml>
                                  <ToUserName><![CDATA['.$openid.']]></ToUserName>
                                  <FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName>
                                  <CreateTime>'.time().'</CreateTime>
                                  <MsgType><![CDATA[news]]></MsgType>
                                  <ArticleCount>1</ArticleCount>
                                  <Articles>
                                    <item>
                                      <Title><![CDATA['.$goods_name.']]></Title>
                                      <Description><![CDATA['.$goods_content.']]></Description>
                                      <PicUrl><![CDATA['.$goods_img.']]></PicUrl>
                                      <Url><![CDATA['.$url.']]></Url>
                                    </item>
                                  </Articles>
                                </xml>';
                                echo $xml_response;

            }elseif($xml->MsgType=='image'){       //用户发送图片信息
                //视业务需求是否需要下载保存图片
                if(1){  //下载图片素材
                    $file_name = $this->dlWxImg($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;

                    //写入数据库
                    $data = [
                        'openid'    => $openid,
                        'add_time'  => time(),
                        'msg_type'  => 'image',
                        'media_id'  => $xml->MediaId,
                        'format'    => $xml->Format,
                        'msg_id'    => $xml->MsgId,
                        'local_file_name'   => $file_name
                    ];

                   $m_id = WeixinMedia::insertGetId($data);
                   var_dump($m_id);
                }
            }elseif($xml->MsgType=='voice'){        //处理语音信息
                $this->dlVoice($xml->MediaId);
            }elseif($xml->MsgType=='event'){        //判断事件类型

                if($event=='subscribe'){                        //扫码关注事件
                    $sub_time = $xml->CreateTime;               //扫码关注时间
                    //获取用户信息
                    $user_info = $this->getUserInfo($openid);
                    //保存用户信息
                    $u = WeixinUser::where(['openid'=>$openid])->first();
                    if($u){       //用户不存在
                        //echo '用户已存在';
                    }else{
                        $user_data = [
                            'openid'            => $openid,
                            'add_time'          => time(),
                            'nickname'          => $user_info['nickname'],
                            'sex'               => $user_info['sex'],
                            'headimgurl'        => $user_info['headimgurl'],
                            'subscribe_time'    => $sub_time,
                        ];

                        $id = WeixinUser::insertGetId($user_data);      //保存用户信息
                        //var_dump($id);
                    }
                      // 文本消息
                    
        $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎关注！'.']]></Content></xml>';
        echo $xml_response;
                }elseif($event=='CLICK'){               //click 菜单
                    if($xml->EventKey=='kefu01'){       // 根据 EventKey判断菜单
                        $this->kefu01($openid,$xml->ToUserName);
                    }
                }

            }

        }
    }

    /**
     * 客服处理
     * @param $openid   用户openid
     * @param $from     开发者公众号id 非 APPID
     */
    public function kefu01($openid,$from)
    {
        // 文本消息
        $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$from.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. 'Hello World, 现在时间'. date('Y-m-d H:i:s') .']]></Content></xml>';
        echo $xml_response;
    }

    /**
     * 下载图片素材
     * @param $media_id
     */
    public function dlWxImg($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getWXAccessToken().'&media_id='.$media_id;
        //echo $url;echo '</br>';

        //保存图片
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;

        //获取文件名
        $file_info = $response->getHeader('Content-disposition');

        $file_name = substr(rtrim($file_info[0],'"'),-20);

        $wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $r = Storage::disk('local')->put($wx_image_path,$response->getBody());
        if($r){     //保存成功
            //echo 'OK';
        }else{      //保存失败
            //echo 'NO';
        }

        return $file_name;


    }

    /**
     * 下载语音文件
     * @param $media_id
     */
    public function dlVoice($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getWXAccessToken().'&media_id='.$media_id;

        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);

        $wx_image_path = 'wx/voice/'.$file_name;
        //保存图片
        $r = Storage::disk('local')->put($wx_image_path,$response->getBody());
        if($r){     //保存成功

        }else{      //保存失败

        }
    }




    /**
     * 接收事件推送
     */
    public function validToken()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str,FILE_APPEND);
        //echo $_GET['echostr'];
        $data = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }

    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        //$openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->getWXAccessToken();      //请求每一个接口必须有 access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
        //echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }

    /**
     * 创建服务号菜单
     */
    public function createMenu(){
        //echo __METHOD__;
        // 1 获取access_token 拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getWXAccessToken();
        //echo $url;echo '</br>';

        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);

        $data = [
            "button"    => [
                [
                    "type"  => "view",      // view类型 跳转指定 URL
                    "name"  => "百度一下",
                    "url"   => "https://www.baidu.com"
                ],
                [
                    "type"  => "click",      // click类型
                    "name"  => "客服01",
                    "key"   => "kefu01"
                ]
            ],
        ];


        $body = json_encode($data,JSON_UNESCAPED_UNICODE);      //处理中文编码
        $r = $client->request('POST', $url, [
            'body' => $body
        ]);

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(),true);
        //echo '<pre>';print_r($response_arr);echo '</pre>';

        if($response_arr['errcode'] == 0){
            echo "菜单创建成功";
        }else{
            echo "菜单创建失败，请重试";echo '</br>';
            echo $response_arr['errmsg'];

        }



    }



    /**
     * 刷新access_token
     */
    public function refreshToken()
    {
        Redis::del($this->redis_weixin_access_token);
        echo $this->getWXAccessToken();
    }

    public function exam()
    {
        return view('weixin.exam');
    }


    public function materialTest()
    {
        //echo __METHOD__;echo '</br>';
        echo '<pre>';print_r($_POST);echo '</pre>';echo '</br>';
        echo '<pre>';print_r($_FILES);echo '</pre>';
    }

    /**
     * 上传素材
     */
    public function upMaterial()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->getWXAccessToken().'&type=image';
        $client = new GuzzleHttp\Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name'     => 'username',
                    'contents' => 'zhangsan'
                ],
                [
                    'name'     => 'media',
                    'contents' => fopen('abc.jpg', 'r')
                ],
            ]
        ]);

        $body = $response->getBody();
        echo $body;echo '<hr>';
        $d = json_decode($body,true);
        echo '<pre>';print_r($d);echo '</pre>';


    }



    public function upMaterialTest($file_path)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->getWXAccessToken().'&type=image';
        $client = new GuzzleHttp\Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name'     => 'media',
                    'contents' => fopen($file_path, 'r')
                ],
            ]
        ]);

        $body = $response->getBody();
        echo $body;echo '<hr>';
        $d = json_decode($body,true);
        echo '<pre>';print_r($d);echo '</pre>';


    }


    /**
     * 获取永久素材列表
     */
    public function materialList()
    {
        $client = new GuzzleHttp\Client();
        $type = $_GET['type'];
        $offset = $_GET['offset'];

        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->getWXAccessToken();

        $body = [
            "type"      => $type,
            "offset"    => $offset,
            "count"     => 20
        ];
        $response = $client->request('POST', $url, [
            'body' => json_encode($body)
        ]);

        $body = $response->getBody();
        echo $body;echo '<hr>';
        $arr = json_decode($response->getBody(),true);
        echo '<pre>';print_r($arr);echo '</pre>';


    }

    public function http405()
    {
        echo __METHOD__;
    }





    public function formShow()
    {

        return view('test.form');

    }

    public function formTest(Request $request)
    {
        //echo '<pre>';print_r($_POST);echo '</pre>';echo '<hr>';
        //echo '<pre>';print_r($_FILES);echo '</pre>';echo '<hr>';

        //保存文件
        $img_file = $request->file('media');
        //echo '<pre>';print_r($img_file);echo '</pre>';echo '<hr>';

        $img_origin_name = $img_file->getClientOriginalName();
        echo 'originName: '.$img_origin_name;echo '</br>';
        $file_ext = $img_file->getClientOriginalExtension();          //获取文件扩展名
        echo 'ext: '.$file_ext;echo '</br>';

        //重命名
        $new_file_name = str_random(15). '.'.$file_ext;
        echo 'new_file_name: '.$new_file_name;echo '</br>';

        //文件保存路径


        //保存文件
        $save_file_path = $img_file->storeAs('form_test',$new_file_name);       //返回保存成功之后的文件路径

        echo 'save_file_path: '.$save_file_path;echo '<hr>';

        //上传至微信永久素材
        //$this->upMaterialTest($save_file_path);


    }


    /**
     * 微信客服聊天
     */
    public function chatView()
    {
        $data = [
            'openid'    => 'oLreB1jAnJFzV_8AGWUZlfuaoQto'
        ];
        return view('weixin.chat',$data);
    }

    public function getChatMsg()
    {
        $openid = $_GET['openid'];  //用户openid
        $pos = $_GET['pos'];        //上次聊天位置
        $msg = WeixinChatModel::where(['openid'=>$openid])->where('id','>',$pos)->first();
        //$msg = WeixinChatModel::where(['openid'=>$openid])->where('id','>',$pos)->get();
        if($msg){
            $response = [
                'errno' => 0,
                'data'  => $msg->toArray()
            ];

        }else{
            $response = [
                'errno' => 50001,
                'msg'   => '服务器异常，请联系管理员'
            ];
        }

        die( json_encode($response));

    }


    /**
     * 微信登录测试
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('weixin.login');
    }

    /**
     * 接收code
     */
    public function getCode()
    {
//        // 1 获取code
//        $code = $_GET['code'];
//
//        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
//        $token_json = file_get_contents($token_url);
//        $token_arr = json_decode($token_json,true);
//
//        $access_token = $token_arr['access_token'];
//        $openid = $token_arr['openid'];
//
//        // 3 携带token  获取用户信息
//        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
//        $user_json = file_get_contents($user_info_url);
//
//        $user_arr = json_decode($user_json,true);
//
//        $unionid = $user_arr['unionid'];        // 微信用户 unionid
//        echo '<pre>';print_r($user_arr);echo '</pre>';
        $user_arr = [];
        $unionid = 'oTm241U1rFq9ZgnmPx3hXhXd-wssa';
        //4 获取unionid 查询用户信息
        $u = WeixinUser::where(['unionid'=>$unionid])->first();
        var_dump($u);
        if($u){
            //TODO 登录逻辑

        }else{
            // 添加用户表
            $u_data = [
                'name'  => 'wx_'.str_random(8),
            ];

            $uid = UserModel::insertGetId($u_data);

            //添加微信用户表
            $wx_u_data = [
                'uid'       => $uid,
                'openid'    => str_random(16),
                'add_time'  => time(),
                //'sex'       => $user_arr['sex'],
                //'headimgurl'    => $user_arr['headimgurl'],
                'unionid'   => $unionid
            ];

            $wx_id = WeixinUser::insertGetId($wx_u_data);

            // 登录
        }

    }


    /**
     * 微信jssdk 调试
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jssdkTest()
    {

        //计算签名

        $jsconfig = [
            'appid' => env('WEIXIN_APPID'),        //APPID
            'timestamp' => time(),
            'noncestr'    => str_random(10),
            //'sign'      => $this->wxJsConfigSign()
        ];

        $sign = $this->wxJsConfigSign($jsconfig);
        $jsconfig['sign'] = $sign;
        $data = [
            'jsconfig'  => $jsconfig
        ];
        return view('weixin.jssdk',$data);
    }


    /**
     * 计算JSSDK sign
     */
    public function wxJsConfigSign($param)
    {
        $current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     //当前调用 jsapi的 url
        $ticket = $this->getJsapiTicket();
        $str =  'jsapi_ticket='.$ticket.'&noncestr='.$param['noncestr']. '&timestamp='. $param['timestamp']. '&url='.$current_url;
        $signature=sha1($str);
        return $signature;
    }


    /**
     * 获取jsapi_ticket
     * @return mixed
     */
    public function getJsapiTicket()
    {

        //是否有缓存
        $ticket = Redis::get($this->redis_weixin_jsapi_ticket);
        if(!$ticket){           // 无缓存 请求接口
            $access_token = $this->getWXAccessToken();
            $ticket_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $ticket_info = file_get_contents($ticket_url);
            $ticket_arr = json_decode($ticket_info,true);

            if(isset($ticket_arr['ticket'])){
                $ticket = $ticket_arr['ticket'];
                Redis::set($this->redis_weixin_jsapi_ticket,$ticket);
                Redis::setTimeout($this->redis_weixin_jsapi_ticket,3600);       //设置过期时间 3600s
            }
        }
        return $ticket;

    }







public function createMenuexam(Request $request){
        $name1 = $request->input('name1');
        $name2 = $request->input('name2');
        $zhr = $request->input('zhr');
        $urla = $request->input('url');
        $key = $request->input('key');
        if($urla){
            $zhr='view';
        }
        //echo __METHOD__;
        // 1 获取access_token 拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getWXAccessToken();
        //echo $url;echo '</br>';

        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);

        $data = [
            "button" =>[
              [
                   "name" => "$name1",
                   "sub_button" => [
                   [    
                       "type" => "$zhr",
                       "name" => "$name2",
                       "url" => "$urla",
                       "key" => "$key"
                    ]
                ]
               ]

           ]
        ];


        $body = json_encode($data,JSON_UNESCAPED_UNICODE);      //处理中文编码
        $r = $client->request('POST', $url, [
            'body' => $body
        ]);

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(),true);
        //echo '<pre>';print_r($response_arr);echo '</pre>';

        if($response_arr['errcode'] == 0){
            echo "菜单创建成功";
        }else{
            echo "菜单创建失败，请重试";echo '</br>';
            echo $response_arr['errmsg'];

        }



    }

    public function log(Request $request){
    	$name=$request->input('name');
    	$password=$request->input('password');
    	$res = UserModel::where(['name'=>$name,'password'=>$password])->first();
    	if($res){
    		$num=rand(1000,9999);
    		$token=md5($num);
    		cache(['access'=>$token],3600);
    		echo json_encode(['token'=>$token]);
    	}else{
			$ip = $request->getClientIp();
			if($ip){
				$ipnum=DB::table('ip')->where('ip',$ip)->first()->num;
				$newnum=$ipnum+1;
				$data=[
					'num'=>$newnum
				];
				DB::table('ip')->where('ip',$ip)->update($data);
				echo json_encode(['ip'=>$ip,'falsenum'=>$newnum]);
			}else{
				$dataa=[
					'ip'=>$ip,
					'num'=>1
				];
				$sql = DB::table('ip')->insert($dataa);
				echo json_encode(['ip'=>$ip],'falsenum'=>1);
			}
    	}
    }

}
