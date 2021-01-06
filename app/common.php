<?php
// 应用公共文件

use think\facade\Config;
use app\base\log\LogUtils;


if (!function_exists('elk_log')) {
    function elk_log($msg, $loggerName, $moduleName = 'mian', $path = 'main')
    {
        $logUtils = new LogUtils();
        $logUtils::elkLog($msg, $loggerName, $moduleName, $path);
    }
}

if (!function_exists('date_time')) {
    function date_time($time = 0)
    {
        if (intval($time) == 0) {
            $time = time();
        }
        return date('Y-m-d H:i:s', $time);
    }
}

if (!function_exists('rpc_call')) {
    /***
     *
     * 请求API的统一入口
     * @param string method 方法名,api的key
     * @param array parameters 请求api的参数，每个api请参考api的业务需求
     * @param array identity 用户信息
     *
     * @return array 返回api的信息
     *
     */
    function rpc_call($apiMethod, $parameters = array())
    {
        $apis = Config::get('apis.routes');
        if (array_key_exists($apiMethod, $apis)) {
            list($class, $method) = explode('@', $apis[$apiMethod]['uses']);
        } else {
            throw new InvalidArgumentException("Api [$apiMethod] not defined");
        }
        $class = 'app\\' . $class;
        $instance = new $class;
        if (!method_exists($instance, $method)) {
            throw new InvalidArgumentException("Api [$apiMethod] method [$method] not defined");
        }
//        $apiParams = $instance->getParams();
//        //验证数据
//        //通过传入数据和api原定义的类型进行比对
//        $realApiParams = utils::_validatorApiParams($apiParams['params'], $parameters);
//
//        //验证json结构的参数
//        //$realApiParams 为API传入参数验证后API定义的参数
//        //以前的API有些在API中未定义确使用了，因此该API参数为兼容暂时不使用
//        $realApiParams = utils::_validatorApiJsonParams($apiParams['params'], $parameters, $realApiParams);
//        //是否需要强制使用API定义的参数
//        if( $instance->use_strict_filter )
//        {
//            $apiParameters = apiUtil::pretreatment($realApiParams, $apiParams);
//        }
//        else
//        {
//            $apiParameters = apiUtil::pretreatment($parameters, $apiParams);
//        }
        return call_user_func(array($instance, $method), $parameters);

//        return rpc::call($method, $parameters, $appKey,$identity);
//        echo 'test';
    }
}