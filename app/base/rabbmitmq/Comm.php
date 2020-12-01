<?php

namespace app\base\rabbmitmq;

use think\facade\Config;


class Comm
{
    public $configs = array();

    //交换机名称
    public $exchange_name = '';
    //队列名称
    public $queue_name = '';

    public $prefetchcount = '';

    //路由名称
    public $route_key = '';
    //持久化，默认True
    public $durable = true;
    //自动删除
    public $autodelete = false;

    public $exchangeType = AMQP_EX_TYPE_TOPIC;

    private $_conn = Null;
    private $_exchange = Null;
    private $_channel = Null;
    private $_queue = Null;


    public function load($exchange_name = '', $queue_name = '', $route_key = '', $configs = array())
    {
        $this->configs = array(
            'HOST' => Config::get('setting.rabbitmq_host'),
            'PORT' => Config::get('setting.rabbitmq_port'),
            'USER' => Config::get('setting.rabbitmq_username'),
            'PASSWD' => Config::get('setting.rabbitmq_password'),
            'VHOST' => Config::get('setting.rabbitmq_vhost'),
            'ROUTER' => 'erp.task.%s.*'
        );
        $this->setConfigs($this->configs);
        //判断是否需要重置
        if (!empty($this->exchange_name) && $this->exchange_name != $exchange_name) {
            $this->reset($exchange_name, $queue_name, $route_key);
        } else {
            $this->exchange_name = $exchange_name;
            $this->queue_name = $queue_name;
            $this->route_key = $route_key;
        }
        return $this;
    }

    private function setConfigs($configs)
    {
        if (!is_array($configs)) {
            throw new \Exception('configs is not array');
        }
        if (!($configs['HOST'] && $configs['PORT'] && $configs['USER'] && $configs['PASSWD'] && $configs['VHOST'])) {
            throw new \Exception('configs is empty');
        }
        $config['host'] = $configs['HOST'];
        $config['port'] = $configs['PORT'];
        $config['login'] = $configs['USER'];
        $config['password'] = $configs['PASSWD'];
        $config['vhost'] = $configs['VHOST'];
        unset($configs);
        $this->configs = $config;
    }

    /*
     * 设置是否持久化，默认为True
     */
    public function setDurable($durable)
    {
        $this->durable = $durable;
    }

    /*
     * 设置是否自动删除
     */
    public function setAutoDelete($autodelete)
    {
        $this->autodelete = $autodelete;
    }

    //没有处理完不接受新的任务
    public function setChannelPrefetchCount($val)
    {
        $this->prefetchcount = $val;
    }

    public function setExchangeType($type)
    {
        $this->exchangeType = $type;
    }

    /*
     * 打开amqp连接
     */
    private function open()
    {
        if (!$this->_conn) {
            try {
                $this->_conn = new \AMQPConnection($this->configs);
                $this->_conn->connect();
                $this->initConnection();
            } catch (\AMQPConnectionException $ex) {
                throw new \Exception('cannot connection rabbitmq', 500);
            }
        }
    }

    /*
     * rabbitmq连接不变
     * 重置交换机，队列，路由等配置
     */
    public function reset($exchange_name, $queue_name, $route_key)
    {
        $this->exchange_name = $exchange_name;
        $this->queue_name = $queue_name;
        $this->route_key = $route_key;
        try {
            $this->initConnection();
        } catch (\AMQPConnectionException $ex) {
            throw new \Exception('reset cannot connection rabbitmq', 500);
        }
    }

    /*
     * 初始化rabbit连接的相关配置
     */
    private function initConnection()
    {
        if (empty($this->exchange_name) || empty($this->queue_name) || empty($this->route_key)) {
            throw new \Exception('rabbitmq exchange_name or queue_name or route_key is empty', 500);
        }
        $this->_channel = new \AMQPChannel($this->_conn);
        if ($this->prefetchcount != '') {
            $this->_channel->setPrefetchCount($this->prefetchcount);
        }
        $this->_exchange = new \AMQPExchange($this->_channel);
        $this->_exchange->setName($this->exchange_name);
        $this->_exchange->setType($this->exchangeType);
        if ($this->durable)
            $this->_exchange->setFlags(AMQP_DURABLE);
        if ($this->autodelete)
            $this->_exchange->setFlags(AMQP_AUTODELETE);
        if (!$this->_exchange->declareExchange()) {
            throw new \Exception('exchange declareExchange false', 500);
        }
        $this->_queue = new \AMQPQueue($this->_channel);
        $this->_queue->setName($this->queue_name);
        if ($this->durable)
            $this->_queue->setFlags(AMQP_DURABLE);
        if ($this->autodelete)
            $this->_queue->setFlags(AMQP_AUTODELETE);
        $this->llen();
        $this->_queue->bind($this->exchange_name, $this->route_key);
    }

    public function close()
    {
        if ($this->_conn) {
            $this->_conn->disconnect();
        }
    }

    public function __sleep()
    {
        $this->close();
        return array_keys(get_object_vars($this));
    }

    public function __destruct()
    {
        $this->close();
    }

    public function llen()
    {
        if ($this->_queue) {
            return $this->_queue->declareQueue();
        }
        return false;
    }

    /*
     * 生产者发送消息
     */
    public function send($msg)
    {
        $this->open();
        if (is_array($msg)) {
            $msg = json_encode($msg);
        } else {
            $msg = trim(strval($msg));
        }
        return $this->_exchange->publish($msg, $this->route_key);
    }

    public function run($fun_name, $autoack = True)
    {
        $this->open();
        if (!$fun_name || !$this->_queue) return False;
        while (True) {
            if ($autoack) $this->_queue->consume($fun_name, AMQP_AUTOACK);
            else $this->_queue->consume($fun_name);
        }
    }

}