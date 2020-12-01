<?php
return [
    // 默认日志记录通道
    'default' => 'file',
    // 日志记录级别
    'level' => [],
    // 日志类型记录的通道 ['error'=>'email',...]
    'type_channel' => [],

    // 日志通道列表
    'channels' => [
        'file' => [
            // 日志记录方式
            'type' => 'File',
            // 日志保存目录
            'path' => app()->getRuntimePath() . "/test",
            // 单文件日志写入
            'single' => true,
            // 独立日志级别
            'apart_level' => [],
            'json' => true,
            // 最大日志文件数量
            'max_files' => 2,
            'file_size' => 1024 * 1024 * 10,
        ],
        // 其它日志通道配置
    ],
];