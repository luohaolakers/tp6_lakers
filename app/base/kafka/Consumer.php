<?php

namespace app\base\kafka;
use think\facade\Config;

class Consumer
{

    public function __construct($config = [])
    {
        $this->rk = new Rdkafka($config);
        $this->rkConf = $this->rk->getConf();
//        $this->config = $this->rk->getConfig();
//        $this->brokerConfig = $this->rk->getBrokerConfig();
        return $this;
    }

    /**
     * 设置消费组
     * @param $groupName
     */
    public function setConsumerGroup($groupName)
    {
        $this->rkConf->set('group.id', $groupName);
        return $this;
    }

    /**
     * 设置服务broker
     * $broker: 127.0.0.1|127.0.0.1:9092|127.0.0.1:9092,127.0.0.1:9093
     * @param $groupName
     */
    public function setBrokerServer()
    {
        $this->rkConf->set('metadata.broker.list', Config::get('setting.kafka_url_list'));
        $this->rkConf->set('socket.timeout.ms', 20);
        $this->rkConf->set('enable.auto.commit', 'false');
//        //在interval.ms的时间内自动提交确认、建议不要启动, 1是启动，0是未启动
//        $this->rkConf->set('auto.commit.enable', 0);
//        if ($this->brokerConfig['auto.commit.enable']) {
//            $this->rkConf->set('auto.commit.interval.ms', $this->brokerConfig['auto.commit.interval.ms']);
//        }
        return $this;
    }

    public function setConsumerTopic()
    {
        $this->topicConf = new \RdKafka\TopicConf();

        //smallest：简单理解为从头开始消费，largest：简单理解为从最新的开始消费
        $this->topicConf->set('auto.offset.reset', 'smallest');
        // 设置offset的存储路径
        $this->topicConf->set('offset.store.path', 'kafka_offset.log');
        //$topicConf->set('offset.store.path', __DIR__);
        //设置默认话题配置
        $this->rkConf->setDefaultTopicConf($this->topicConf);
        return $this;
    }

    public function subscribe($topicNames)
    {
        $this->consumer = new \RdKafka\KafkaConsumer($this->rkConf);
        $this->consumer->subscribe($topicNames);
        return $this;
    }

    public function consumer(\Closure $handle)
    {
        while (true) {
//            设置超时时间
            $message = $this->consumer->consume(10000);
            if (!empty($message)) {
                switch ($message->err) {
                    case RD_KAFKA_RESP_ERR_NO_ERROR:
                        $handle($message,$this->consumer);
                        break;
                    case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                        echo "No more messages; will wait for more\n";
                        break;
                    case RD_KAFKA_RESP_ERR__TIMED_OUT:
                        echo "Timed out\n";
                        var_dump("##################");
                        break;
                    default:
                        var_dump("nothing");
                        throw new \Exception($message->errstr(), $message->err);
                        break;
                }
            } else {
                var_dump('this is empty obj!!!');
            }

        }
    }


}