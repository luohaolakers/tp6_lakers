<?php

namespace app\task\model;

use think\facade\Db;

class TaskLog
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'sdb_task_log';

    public function add($params)
    {
        $data['run_id'] = $params['run_id'];
        $data['task_code'] = $params['task_code'];
        $data['start_time'] = $params['start_time'];
        $data['created_at'] = date_time();
        return Db::table($this->table)->insertGetId($data);
    }

    public function updateTaskEndTime($runId, $params)
    {
        $data['end_time'] = $params['end_time'];
        $data['updated_at'] = date_time();
        return Db::table($this->table)->where(['run_id' => $runId])->update($data);
    }
}