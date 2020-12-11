<?php

namespace app\auth\api\get;

use \Firebase\JWT\JWT;
use think\facade\Config;
use app\auth\model\ApiUsers as ApiUsersMdl;
use think\db\exception\HttpResponseException;

class LoginToken
{
    public $apiDescription = "获取认证的token";

    public $use_strict_filter = true; // 是否严格过滤参数

    public function getParams()
    {
        $return['params'] = [
            'name' => ['type' => 'string', 'valid' => 'required', 'description' => '账号', 'example' => 'lakers'],
            'password' => ['type' => 'string', 'valid' => 'required', 'description' => '密码', 'example' => 'admin123'],
        ];
        return $return;
    }

    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
    public function get($params)
    {
        $apiUsersMdl = new ApiUsersMdl();
        if (!$apiUsersMdl->checkLogin($params['name'], $params['password'])) {
            throw new \LogicException("Username or password error!");
        }
        $key = Config::get('jwt.key');
        $time = time(); //当前时间
        $payload = [
            'iss' => Config::get('jwt.iss'),
            'aud' => Config::get('jwt.aud'),
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time + 20, //过期时间,这里设置1个小时
            'data' => ["user" => $params['name'], "password" => $params['password']]
        ];
        return JWT::encode($payload, $key);




    }
}