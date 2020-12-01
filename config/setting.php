<?php
return [
    'rabbitmq_host' => env('rabbitmq.host', '127.0.0.1'),
    'rabbitmq_port' => env('rabbitmq.port', '6379'),
    'rabbitmq_username' => env('rabbitmq.username', 'guest'),
    'rabbitmq_password' => env('rabbitmq.password', 'guest'),
    'rabbitmq_vhost' => env('rabbitmq.vhost', 'test'),
    'kafka_url_list'=>'de-kafka.pp.dktapp.cloud:9092',
];
