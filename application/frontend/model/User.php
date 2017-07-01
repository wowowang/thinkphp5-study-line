<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/27
 * Time: 21:00
 */

namespace app\frontend\model;

use think\Model;
use traits\model\SoftDelete;

class User extends Model
{
    //软删除
    use SoftDelete;

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    //如果数据库没有创建update_time和更新时间update_time,则按照以下的做一个映射既可以
    protected $createTime  = "create_time";
    protected $updateTime  = "update_time";
    //软删除的映射
    protected $deleteTime = "delete_time";

    //关闭自动写入
    #protected $updateTime = false;

    //自动完成 字段
    protected $auto = [
        "logintime"
    ];

    //插入的时候自动完成
    protected $insert = [
        "apikey_time"
    ];

    //更新自动完成
    protected $update = [
        "logintime"
    ];

    /**
     * 根据数据库status字段的值，在模型中定义输出的值
     * @param $val
     * @param $data 可选参数
     * @return string
     */
    public function getStatusAttr($val, $data)
    {
        switch ($val) {
            case "0":
                return "开启状态";
                break;
            case "1":
                return "关闭状态";
                break;
            default:
                return "未知";
        }
    }

    /**
     * 自动md5的值
     * @param $val
     * @param $data 可选参数
     * @return string
     */
    public function setPasswordAttr($val, $data)
    {
//        return md5($val);
//        return $val.$data["apikey_value"];
        // 通过密码和某一个字段的连接进行md5
        return md5($val . $data["apikey_value"]);
    }

    public function setLoginTimeAttr()
    {
        return time();
    }

    /**
     * 注意： 数据库字段为apikey_time 则在设置属性的时候直接去掉下划线既可以：ApiKeyTime
     * @return int
     */
    public function setApiKeyTimeAttr()
    {
        return time();
    }
}