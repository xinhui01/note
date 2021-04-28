## php rbkafka 生产者
### 高并发环境下 local queue 很容易full ,导致cli应用producer卡死
### 可以配置最大队列数进行检测,后熔断
```
$conf->set('queue.buffering.max.messages',(string)$config['queue.buffering.max.messages']);
```