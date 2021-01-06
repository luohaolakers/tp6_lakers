<?php

namespace app\task\api\create;

use app\task\model\TaskLog as TaskLogMdl;

class TaskLog
{
    public $apiDescription = "增加task log";

    public $use_strict_filter = true; // 是否严格过滤参数

    public function getParams()
    {
        $return['params'] = [
            'task_code' => ['type' => 'string', 'valid' => 'required', 'description' => '任务名', 'example' => ''],
            'start_time' => ['type' => 'string', 'valid' => 'required', 'description' => '开始时间', 'example' => ''],
            'run_id' => ['type' => 'string', 'valid' => 'required', 'description' => '运行id', 'example' => ''],
        ];
        return $return;
    }

    public function create($params)
    {
        $taskLogMdl = new TaskLogMdl();
        return $taskLogMdl->add($params);
    }

}