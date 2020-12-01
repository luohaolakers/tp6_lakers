<?php

namespace app\base\log;

class LogUtils
{

    public static function elkLog($msg, $loggerName, $moduleName = 'mian', $path = 'main')
    {
        $time = time();
        $message['elk_log_time'] = date('Y-m-d H:i:s', $time);
        $message['logger_name'] = $loggerName;
        if (is_array($msg) || is_object($msg)) {
            $message['msg_str'] = json_encode($msg);
        } else {
            $message['msg_str'] = $msg;
        }
        $message['module_name'] = $moduleName;
        $message['@timestamp'] = str_replace('+00:00', '.000Z', gmdate(DATE_ATOM, $time));
        $fileName = $message['logger_name'] . date("Ymd") . '.log';
        $dir = app()->getRootPath() . "runtime/elklog/" . $path . "/" . $fileName;
        $fp = fopen($dir, 'a');
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, json_encode($message) . PHP_EOL);
        }
        fclose($fp);
        @chmod($dir, 0766);
        @chown($dir, "www");
        @chgrp($dir, "www");
    }

}
