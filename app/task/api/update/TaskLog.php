<?php

namespace app\task\api\update;

use app\task\model\TaskLog as TaskLogMdl;

class TaskLog
{
    public $apiDescription = "增加task log";

    public $use_strict_filter = true; // 是否严格过滤参数

    public function getParams()
    {
        $return['params'] = [
            'run_id' => ['type' => 'string', 'valid' => 'required', 'description' => '运行id', 'example' => ''],
            'task_code' => ['type' => 'string', 'valid' => 'required', 'description' => '任务名', 'example' => ''],
            'end_time' => ['type' => 'string', 'valid' => 'required', 'description' => '结束时间', 'example' => ''],
        ];
        return $return;
    }

    public function update($params)
    {
        $runId = $params['run_id'];
        $taskLogMdl = new TaskLogMdl();
        return $taskLogMdl->updateTaskEndTime($runId, $params);
    }

}