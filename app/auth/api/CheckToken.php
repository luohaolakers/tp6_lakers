<?php

namespace app\auth\api;

use \Firebase\JWT\JWT;
use think\facade\Config;

class CheckToken
{

    public $apiDescription = "认证jwt";

    public $use_strict_filter = true; // 是否严格过滤参数

    public function getParams()
    {
        $return['params'] = [
            'jwt' => ['type' => 'string', 'valid' => 'required', 'description' => 'jwt', 'example' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9vbXMtc2VydmVyLmNvbVwvYXBpXC9hdXRoIiwiaWF0IjoxNjA3Njc4ODg3LCJleHAiOjE2MDc2ODI0ODcsIm5iZiI6MTYwNzY3ODg4NywianRpIjoieG1NNmVhT3Zsb1c2dkl5eCIsInN1YiI6MTI0LCJwcnYiOiJjMDc3ODViN2FhYmMwMTliNGVjZTBkNTgyYzg3ZDNlMDAzOTEyMzdhIn0.dKrhl6cUK94wClQ13-eoNqZeX17WxUofFL_wrciUwYM'],
        ];
        return $return;
    }

    //https://blog.csdn.net/cjs5202001/article/details/80228937
    public function check($params)
    {
        try {
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            //HS256方式，这里要和签发的时候对应
            $decodedObj = JWT::decode($params['jwt'], Config::get('jwt.key'), ['HS256']);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            throw new \LogicException($e->getMessage());
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw new \LogicException($e->getMessage());
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            throw new \LogicException($e->getMessage());
        } catch (\Exception $e) {  //其他错误
            throw new \LogicException($e->getMessage());
        }
        return $decodedObj;
    }
}