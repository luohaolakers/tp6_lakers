<?php

namespace utils;

use utils\ftp\Log;
use utils\ftp\Ftp;
use utils\ftp\Sftp;
use think\facade\Config;

class FtpUtils
{
    public $conn;
    public $ftpType = ['ftp_bi' => 'ftp'];

    private $config_name;


    public function __construct($config_name)
    {
        $this->config_name = $config_name;
        $host = Config::get("$config_name.host");
        $userName = Config::get("$config_name.username");
        $passWord = Config::get("$config_name.password");
        $type = Config::get("$config_name.type");
        if ($type == 'sftp') {
            $this->ftpConn = new Sftp();
        } else {
            $this->ftpConn = new Ftp();
        }
        if (!$this->ftpConn->connect($userName, $passWord, $host)) {
            Log::save_error_log("FTP连接失败!", '', '', '', $this->config_name);
            return false;
        }
        return true;
    }


    public function sendFile($server_path, $loca_path)
    {
        if (!is_file($loca_path)) {
            return false;
        }
        $this->ftpConn->mkdir($server_path);
        if (!$this->ftpConn->sendFile($server_path, $loca_path)) {
            return false;
        }
        return true;

    }
}
