<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan
 * |  Date: 2017/3/2
 * |  Time: 12:59
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\index\controller;


use Curl\Curl;
use think\Controller;
use think\Db;
use think\Log;

class DbDemo extends Controller
{

    public function index()
    {
        return 'db';
    }

    public function phalcon()
    {
        $res = Db::query('select * from user where id=?', [757158802]);
        $data = ['foo' => 'bar', 'bar' => 'foo'];
        $insertRes = Db::table('think_user')->insert($data);
        print_r($res);
    }

    public function queryDemo1()
    {
        // 是否为 GET 请求
        $res = Db::query('select * from tour_admin_user where id=?', [164]);
        print_r($res);
        //也支持命名占位符绑定
        $res2 = Db::query('select * from tour_admin_user where id=:id', ['id' => 164]);
        print_r($res2);
        die;
    }

    public function insertDemo1()
    {
        // 是否为 GET 请求
        $insertRes = Db::execute('insert into tour_admin_user (id, name) values (?, ?)', [8, 'thinkphp']);;
        var_dump($insertRes);
        //也支持命名占位符绑定
        $insertRes2 = Db::execute('insert into think_user (id, name) values (:id, :name)', ['id' => 8, 'name' => 'thinkphp']);
        var_dump($insertRes2);
        die;
    }

    //使用 Db 类的 insert 方法向数据库提交数据
    public function insertDemo2()
    {
        $data = [
            'startTime' => date("Y-m-d H:i:s", time()),
            'action' => 'publish',
            'sign' => 1,
            'createTime' => date('Y-m-d H:i:s')
        ];
        try {
            $insertRes = Db::table('push_flow_record')->insert($data);
        } catch (\Exception $e) {
            Log::error('错误信息' . $e->getMessage());
            exit('执行错误!');
        }
        exit('执行成功!');
    }

    //更新数据表中的数据
    public function updateDemo1()
    {
        $data = [
            'stopTime' => date("Y-m-d H:i:s", time()),
            'action' => 'publish',
            'sign' => 1,
            'app' => 'tinywan.com'
        ];
        try {
            $insertRes = Db::table('push_flow_record2')->where('id', 1596)->update($data);
        } catch (\Exception $e) {
            Log::error('push_flow_record表发修改数据异常，错误信息:' . $e->getMessage());
            exit('执行错误!');
        }
        echo $insertRes;
        exit('执行成功!');
    }

    /**
     * 查询构造器
     */

    //查询一个数据使用
    public function tableDemo1()
    {
        // table方法必须指定完整的数据表名,find 方法查询结果不存在，返回 null
        $tabRes = Db::table('user')->where('id', 75715888)->find();
        if ($tabRes === null) exit('查询结果不存在');
        print_r($tabRes);
        //助手函数
        $tabRes2 = db('user')->where('id', 757158800)->find();
        print_r($tabRes2);
    }

    //查询多个数据使用

    public function tableSelect()
    {
        $res = Db::table('domain_name')->where('code', 'tinywan.amai8.com')->find(); //select 方法查询结果不存在，返回空数组
        if ($res['sourceStationId'] == '1') {

        } elseif ($res['sourceStationId'] == '2') {

        } elseif ($res['sourceStationId'] == '3') {

        } else {

        }
        if (empty($res)) exit('查询结果不存在');
        var_dump($res);
    }

    public function redisTest()
    {
        $redis = new \Redis();
        $redis->connect('121.41.88.209', '63789');
        $redis->auth('tinywanredisamaistream');
        $redis->select(12);
        Log::error('错误信息');
    }

    public function curl()
    {
        $curl = new Curl();
        $res = $curl->get('http://sewise.amai8.com/');

        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            echo 'Response:' . "\n";
            var_dump($curl->response);
        }
        print_r($res);
        echo '11111111';
    }

    private function prcToUtc($prcTime)
    {
        //重定向到News模块的Category操作
        $this->redirect('News/category', ['cate_id' => 2]);
        //重定向到指定的URL地址 并且使用302
        $this->redirect('http://thinkphp.cn/blog/2', 302);
        return gmdate("Y-m-d\TH:i:s\Z", strtotime($prcTime));
    }

    public function test2()
    {
        $url = 'http://mworker.amaitech.com/api/Notify/notifyUrl';
        $ch = curl_init() or die (curl_error());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 360);
        $response = curl_exec($ch);
        curl_close($ch);
        var_dump(json_decode($response, true));
        die;
    }
}