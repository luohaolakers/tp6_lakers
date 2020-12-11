<?php

namespace app\auth\model;

use think\exception\PDOException;
use think\Model;
use think\facade\Db;

class ApiUsers
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'sdb_api_users';

    public function addApiUser()
    {
        $data = [];
        $data['name'] = 'test';
        $data['password'] = 'test';
        $data['token'] = 'test';
        $data['created_at'] = '2019-12-05 19:22:00';
        $data['updated_at'] = '2019-12-05 19:22:00';
        $data['api_list'] = 'asd';
        Db::table($this->table)->insertGetId($data);
        return 1;
    }

    public function checkLogin($name, $password)
    {
        return Db::table($this->table)->where(['name' => $name, 'password' => $password])->count();
    }

}