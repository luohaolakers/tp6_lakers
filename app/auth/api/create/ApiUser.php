<?php

namespace app\auth\api\create;

use \Firebase\JWT\JWT;
use app\auth\model\ApiUsers as ApiUsersMdl;

class ApiUser
{
    public $apiDescription = "创建api 用户";

    public $use_strict_filter = true; // 是否严格过滤参数

    public function getParams()
    {
        $return['params'] = [
            'name' => ['type' => 'string', 'valid' => 'required', 'description' => '账号', 'example' => 'lakers'],
            'password' => ['type' => 'string', 'valid' => 'required', 'description' => '密码', 'example' => 'admin123'],
        ];
        return $return;
    }

    public function create($params)
    {
        $apiUsersMdl = new ApiUsersMdl();
        return $apiUsersMdl->addApiUser();
//        $params['password'] = bcrypt($params['password']);
        return 1;
    }


}