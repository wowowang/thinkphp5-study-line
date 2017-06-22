<?php
namespace app\wechat\controller;

use EasyWeChat\Foundation\Application;
use think\Controller;
use think\Request;

class Index extends Controller
{
    public function server()
    {
        $options = [
            'debug'  => true,
            'app_id' => 'wx94c43716d8a91f3f',
            'secret' => 'd4624c36b6795d1d99dcf0547af5443d',
            'token'  => 'easywechat',
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => CONF_PATH.'/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];
        $app = new Application($options);
        $app->server->setMessageHandler(function ($message){
            return "您好！欢迎关注我!";
        });
        return $app->server->serve()->send();
    }



    /**
     * 这里采用高级授权模式，为了获取用户信息
     * 这个页面是用户进来就能够刚问的页面，也就是首次进来的页面
     * 首先访问的地址 ；http://wechatu.xd107.com/home/WeiXin/index
     */
    public function index()
    {
        $scope = "snsapi_base";
        $scope = "snsapi_userinfo";
        $appid = 'wx94c43716d8a91f3f';
        /*基本授权 方法跳转地址*/
        $redirect_uri = urlencode('http://wanwecaht.amai8.com/wechat/Index/getUserInfo');

        /*基本授权 snsapi_base*/
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1234#wechat_redirect";

        /*高级授权 snsapi_userinfo*/
        //$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=snsapi_userinfo&state=1234#wechat_redirect";
        //跳转
        header('location:' . $url);
    }

    /*拉取用户信息*/
    public function getUserInfo()
    {
        header("Content-type: text/html; charset=utf-8");
        $appid = 'wx94c43716d8a91f3f';
        $appsecret = 'd4624c36b6795d1d99dcf0547af5443d';
        /*回调的时候自带的这个参数*/
        $request = Request::instance();
        $code = $request->get('code');
        /*基本授权 snsapi_base*/
        //$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1234#wechat_redirect";
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";

        $result = json_decode(get_uri($url),true);
        /*取出数组中的access_token这个值*/
        $access_token = $result['access_token'];
        $openid = $result['openid'];
        $URL2 = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        $responseInfo = json_decode(get_uri($URL2),true);
        var_dump($responseInfo);
        die;
        $this->headimgurl = $responseInfo['headimgurl'];
        $this->userInfo = $responseInfo;
        $this->display();
    }


}
