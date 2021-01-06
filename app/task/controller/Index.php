<?php

namespace app\task\controller;

use app\BaseController;

use app\base\file\File;
use app\task\lib\test;


class Index extends BaseController
{
    public function index()
    {
        try {

            $file = new File();
            $path = app()->getRootPath() . 'runtime/elklog/crontabscript';
            $fileDataList = $file->getListFile($path);
            if ($fileDataList) {
                $index = new test();
                foreach ($fileDataList as $key => $f) {
                    if (strpos($f, 'shortageRepl.php.log') !== false) {
                        $data = $file->readFileLine($f, array($index, 'syncData'));
                        if ($data) {
                            foreach ($data as $j => $val) {
                                $lineData = json_decode($val,1);
                                if(!$lineData){
                                    continue;
                                }
                                $dbData['run_id'] = $lineData['uniqid'];
                                $dbData['task_code'] = $lineData['logger_name'];
                                if($lineData['data']=='start'){
                                    $dbData['start_time'] = $lineData['elk_log_time'];
                                    $m = 'task.create.tasklog';
                                }else{
                                    $dbData['end_time'] = $lineData['elk_log_time'];
                                    $m = 'task.update.tasklog';
                                }
                                rpc_call($m, $dbData);
                            }
                        }
                    }
                }
            }
        } catch (\LogicException $e) {
            $msg = $e->getMessage();
        }
        return '完成';
    }


}