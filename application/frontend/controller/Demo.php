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

namespace app\frontend\controller;

use think\Controller;
use think\Db;
use think\Log;

class Demo extends Controller
{
    /**
     * 打印数据库配置信息
     */
    public function index()
    {
        var_dump(Db::connect());
    }

    /**
     * 使用 sql 语句的方式查询数据库
     */
    public function query1()
    {
        $res = Db::query('select * from frontend_user where user_id=?', [27]);
        var_dump($res);
    }

    /**
     * 绑定参数名, sql 语句的方式查询数据库
     */
    public function queryDemo1()
    {
        // 简单的查询方式
        $res = Db::query('select * from frontend_user where user_id = ?', [33]);
        print_r($res);
        //也支持命名占位符绑定
        $res2 = Db::query('select * from frontend_user where user_id = :id', ['id' => 34]);
        print_r($res2);
        // 如果设置了数据表前缀参数的话，使用表前缀的查询 多条记录
        $res3 = Db::name("user")->select();
        print_r($res3);
        // 使用表前缀的查询单条记录
        $res4 = Db::name("user")->find();
        print_r($res4);
        //使用助手函数
        $res5 = db("user")->find();
        print_r($res5);
    }

    /**
     * 【1】插入数据库操作
     */
    public function insertDemo1()
    {
        // 插入成功返回 1
        $insertRes = Db::execute('insert into frontend_user (username, password) values (?, ?)', ["tinywan001", 'thinkphp']);
        echo $insertRes;
        //也支持命名占位符绑定 插入成功返回 1
        $insertRes2 = Db::execute('insert into frontend_user (username, password) values (:username, :password)', ['username' => "Github", 'password' => '123456']);
        echo $insertRes2;
    }

    /**
     * 【2】使用 Db 类的 insert 方法向数据库提交数据
     */
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
            return "执行错误";
        }
        return '执行成功! insertRes'.$insertRes;
    }

    /**
     * 添加多条数据
     * @return string
     */
    public function insertAllDemo1()
    {
        $data = [];
        for ($i = 0 ; $i<10 ; $i++){
            $data[] = [
                'username' => "User".$i,
                'password' => "Pwd".$i,
            ];
        }
        $res = Db::name('user')->insertAll($data);
        echo $res;
    }

    /**
     * 更新数据表中的数据
     */
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
     * 更新数据表中的数据
     */
    public function updateDemo2()
    {
        $db = Db::name("user");
        // 自增操作
        $res1 = $db->where([
            "user_id" => 26
        ])->setInc("status");
        echo $res1;
        // 自增操作 指定的值，只需要传递第二个参数就可以了
        $res2 = $db->where([
            "user_id" => 26
        ])->setInc("status",10);
        echo $res2;
        // 自减操作
        $res3 = $db->where([
            "user_id" => 26
        ])->setDec("status",10);
        echo  $res3;
    }

    /**
     * 数据库删除操作
     */
    public function deleteDemo1()
    {
        $db = Db::name("user");
        // 删除成功为 1 没有数据被删除或者失败返回为 0
        $res1 = $db->where([
            "user_id" => 26
        ])->delete();
        echo  $res1;
        // 根据主键直接删除操作
        $res2 = $db->delete(46);
        echo $res2;
        // 删除所有记录
        $res3 = $db->where("1=1")->delete();
    }

    /**
     * 查询构造器
     */
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

    /**
     * 查询多个数据使用
     */
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

    /**
     * Redis 连接测试
     */
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

}