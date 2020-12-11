<?php

namespace app\shopadmin\controller;

use app\BaseController;
use app\base\http\Client as HttpClient;
use app\base\kafka\Producer as KafkaProducer;

use \Firebase\JWT\JWT;

use \app\base\rabbmitmq\Comm as RabbmitmqComm;
use \app\base\email\Comm as EmailComm;

use \app\base\log\LogUtils as LogUtils;
use \Monolog\Handler\StdoutHandler;

use think\facade\Log;


class Index extends BaseController
{
    //GIT =https://github.com/luohaolakers/tp6_lakers
    //GIT1=https://sonarcloud.io/project/issues?id=luohaolakers_tp6_lakers&open=AXYeoEKyTBCkXJuuF2OO&resolved=false&types=BUG
    public function index()
    {
        try {
            $data['jwt'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJpYXQiOjE2MDc2OTc0NzksIm5iZiI6MTYwNzY5NzQ3OSwiZXhwIjoxNjA3Njk3NDk5LCJkYXRhIjp7InVzZXIiOiJ0ZXN0IiwicGFzc3dvcmQiOiJ0ZXN0In19.xqJTP00JlmihOTU7KgzZYNwuMq4iuZcA51YVs8bIvCc';
            $msg = rpc_call('auth.check.api.token', $data);
        } catch (\LogicException $e) {
            $msg = $e->getMessage();
        }
        return $msg;
        return rpc_call('auth.create.api.user', []);
//        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) 2020新春快乐</h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }


    public function index1()
    {
        try {
            $data['name'] = 'test';
            $data['password'] = 'test';
            $msg = rpc_call('auth.get.api.token', $data);
        } catch (\LogicException $e) {
            $msg = $e->getMessage();
        }
        return $msg;
    }

    public function resData()
    {
        $data['a'] = 1;
        $data['b'] = 1;
        $data = json_encode($data);
        return response()->data($data);
    }

    public function Logtest()
    {
        Log::write('测试日志信息，这是警告级别，并且实时写入', 'info');
    }

    public function rabbmit()
    {
        $connecter = new RabbmitmqComm();
        $data = $connecter->load('lakerstest1', 'lakerstest1', 'lakerstest1')->send('lakersssss');
        var_dump($data);
        return false;
    }

    public function email()
    {
        $emailComm = new EmailComm();
        $data = $emailComm::sendToAdmin('test', 'test');
        var_dump($data);
    }

    public function httpaasd()
    {
        $baseHttpClient = new HttpClient();
        $url = 'https://oms-preprod.decathlon.com.cn:9443/index.php/openapi/rpc/service/';
        $data = $baseHttpClient->set_ssl(false)->get($url);
        var_dump($data);
    }

    public function kafka()
    {
        $config = [
            'dr_msg_cb' => function ($kafka, $message) {
//                var_dump((array)$message);
            }
        ];
        $producer = new KafkaProducer($config);
        $producer = $producer->setBrokerServer()
            ->setProducerTopic('test_test');
        $res = $producer->producer('12321', '');
        var_dump($res);
        var_dump(11);
    }


    public function ckafka()
    {
//        $arr  = ['name'=>null];
//        isset($arr['name']);
//
//        $config = \Kafka\ConsumerConfig::getInstance();
//        $config->setMetadataRefreshIntervalMs(10000);
//        $config->setMetadataBrokerList('de-kafka.pp.dktapp.cloud:9092');
//        $config->setGroupId('testGroupLakers1');
//        $config->setBrokerVersion('2.0.0');
//        $config->setTopics(['oms-basicdata-shop']);
//        $config->setOffsetReset('earliest');
//        $consumer = new \Kafka\Consumer();
//        var_dump($consumer);
//        $consumer->start(function ($topic, $part, $message) {
//            var_dump($message);
//            Log::write($message, 'info');
//        });
        json(array('a' => 2))->send();
        exit;
    }

    public function jwt()
    {
        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "user" => 'lakers',
            "password" => 'lakers1'

        );

        LogUtils::elkLog($payload, 'test');
        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, $key);
        var_dump($jwt);
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        var_dump($decoded);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

}
