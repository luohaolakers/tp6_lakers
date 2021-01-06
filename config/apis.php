<?php


return [
    /*
    |--------------------------------------------------------------------------
    | 定义所有api接口路由
    |--------------------------------------------------------------------------
    |
    | key代表, api method name.
    | rpc_call('method', array($param1, $param2));
    |
     */
    'routes' => [
        'auth.create.api.user' => ['uses' => 'auth\api\create\ApiUser@create', 'version' => ['v1']],
        'auth.get.api.token' => ['uses' => 'auth\api\get\LoginToken@get', 'version' => ['v1']],
        'auth.check.api.token' => ['uses' => 'auth\api\CheckToken@check', 'version' => ['v1']],

        'task.create.tasklog'=>['uses' => 'task\api\create\TaskLog@create', 'version' => ['v1']],
        'task.update.tasklog'=>['uses' => 'task\api\update\TaskLog@update', 'version' => ['v1']],

    ],
];
