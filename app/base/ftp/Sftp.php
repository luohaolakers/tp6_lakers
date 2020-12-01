<?php

namespace app\base\ftp;

class Sftp
{
    public $conn;
    public $sftp;

    public function connect($username, $password, $host, $port = 22)
    {
        $this->conn = @ssh2_connect($host, $port);
        if (!$this->conn) {
            $this->conn = null;
            return false;
        }
        if (!@ssh2_auth_password($this->conn, $username, $password)) {
            $this->conn = null;
            return false;
        }
        $this->sftp = @ssh2_sftp($this->conn);
        if (!$this->sftp) {
            $this->conn = null;
            return false;
        }
        return true;
    }


    public function mkdir($file)
    {
        $dir = explode('/', $file);
        if (strpos($dir[count($dir) - 1], '.') !== false) {
            unset($dir[count($dir) - 1]);
        }
        $path = '';
        for ($i = 0; $i < count($dir); $i++) {
            if ($dir[$i] == '') {
                continue;
            }
            $path .= "/" . $dir[$i];
            if (!file_exists("ssh2.sftp://{$this->sftp}" . $path)) {
                ssh2_sftp_mkdir($this->sftp, $path, 0777, true);
            }
        }
    }

    public function sendFile($server_path, $loca_path)
    {
        return copy($loca_path, "ssh2.sftp://" . intval($this->sftp) . $server_path);
    }


    public function download($remote, $local)
    {
        //远程文件 拷贝到本地
        $resource = "ssh2.sftp://" . $this->sftp . $remote;
        $res = copy($resource, $local);
        return $res;
    }

    //获取文件列表
    function getFileList($directory)
    {
        $resData = array();
        $handle = opendir("ssh2.sftp://$this->sftp" . $directory);
        while (false != ($file = readdir($handle))) {
            // .和 ..不管
            if ($file == "." || $file == "..") {
                continue;
            }
            // 发现需要下载的文件，检验后缀
            $path = $directory . '/' . $file;
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($extension, array('xml', 'zip'))) {
                $resData[] = $path;
            }
        }
        return $resData;
    }


    public function rename($oldName, $newName)
    {
        return ssh2_sftp_rename($this->sftp, $oldName, $newName);
    }

    // 删除文件
    public function remove($remote)
    {
        $rc = false;
        if (is_dir("ssh2.sftp://{$this->sftp}/{$remote}")) {
            // ssh 删除文件夹
            $rc = ssh2_sftp_rmdir($this->sftp, $remote);
        } else {
            // 删除文件
            $rc = ssh2_sftp_unlink($this->sftp, $remote);
        }
        return $rc;
    }


}