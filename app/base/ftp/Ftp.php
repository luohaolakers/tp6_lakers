<?php

namespace app\base\ftp;

class Ftp
{
    public $conn;

    public function connect($username, $password, $host, $port = 21)
    {
        $this->conn = ftp_connect($host, $port);
        if (!ftp_login($this->conn, $username, $password)) {
            $this->conn = null;
            return false;
        } else {
            ftp_pasv($this->conn, true);
        }
        return true;
    }


    public function sendFile($server_path, $loca_path)
    {
        if (empty($this->conn)) {
            return false;
        }
        if (!ftp_put($this->conn, $server_path, $loca_path, FTP_BINARY)) {
            return false;
        }
        return true;
    }

    //获取文件列表
    function getFileList($directory)
    {
        return ftp_nlist($this->conn, $directory);
    }

    /**
     * 下载文件
     *
     * @param void
     * @return void
     * @author
     **/
    public function download($remote, $local)
    {
        if (!$this->is_conn()) {
            return false;
        }
        return @ftp_get($this->conn, $local, $remote, FTP_BINARY);
    }

    /**
     * 重命名文件
     * @param void
     * @return void
     * @author
     **/
    public function rename($oldName, $newName)
    {
        if (!$this->is_conn()) {
            return false;
        }
        return ftp_rename($this->conn, $oldName, $newName);
    }

    /**
     * 删除文件
     * @param string  文件标识(ftp)
     * @return  boolean
     */
    public function remove($file)
    {
        if (!$this->is_conn()) {
            return false;
        }
        return ftp_delete($this->conn, $file);
    }

    /**
     * 检查是否连接
     * @param void
     * @return void
     * @author
     **/
    private function is_conn()
    {
        return is_resource($this->conn);
    }

    /**
     * 析构
     * @param void
     * @return void
     * @author
     **/
    public function __destruct()
    {
        if ($this->conn) {
            ftp_close($this->conn);
        }
    }


    /**
     * 返回指定文件的大小
     * @param void
     * @return void
     * @author
     **/
    public function getSize($file)
    {
        if (!$this->is_conn()) {
            return false;
        }
        return ftp_size($this->conn, $file);
    }


    public function mkdir($file)
    {
        $dir = explode('/', $file);
        if (strpos($dir[count($dir) - 1], '.') !== false) {
            unset($dir[count($dir) - 1]);
        }
        $path = '';
        for ($i = 0; $i < count($dir); $i++) {
            $path .= "/" . $dir[$i];
            if (!@ftp_chdir($this->conn, $path)) {
                @ftp_chdir($this->conn, "/");
                if (!@ftp_mkdir($this->conn, $path)) {
                    break;
                }
            }
        }
    }

}