<?php

namespace app\base\file;

class File
{

    /**
     * 读取文件操作
     * @param string $file
     */
    public function readFileLine($file, $callback, $length = 1024)
    {
        $resData = [];
        $fp = @fopen($file, 'r');
        if ($fp) {
            while (!feof($fp)) {
                $lineData = fgets($fp, $length);
                $resData[] = $lineData;
//                var_dump($lineData);
//                call_user_func_array($callback, $lineData);
            }
        }
        return $resData;
    }

    /**
     * 遍历文件
     * @param string $path
     * @return array
     */
    public function getListFile($path)
    {
        if (is_dir($path)) {
            //1、首先先读取文件夹
            $temp = scandir($path);
            //遍历文件夹
            if ($temp) {
                foreach ($temp as $v) {
                    $str = $path . '/' . $v;
                    if (is_dir($str)) {
                        //如果是文件夹则执行
                        if ($v == '.' || $v == '..') {
                            //判断是否为系统隐藏的文件.和..  如果是则跳过否则就继续往下走，防止无限循环再这里。
                            continue;
                        }
                        //因为是文件夹所以再次调用自己这个函数，把这个文件夹下的文件遍历出来
                        $this->getlistFile($str);
                    } else {
                        $this->resData[] = $str;
                    }
                }
            }
        }
        return isset($this->resData) ? $this->resData : array();
    }
}