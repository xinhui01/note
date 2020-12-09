# redis使用记录
## redis 有漏洞容易被植入挖矿木马,不要暴露在外网
## redis 启动命令 
### 最好指定 配置文件
```
redis-server /usr/local/redis/etc/redis.conf &  #加 &后台启动,关闭xshell不影响
# 关闭redis可以直接kill进程
# redis-cli -h 127.0.0.1 -p 6379 shutdown 关闭
``` 
## aof 持久化不会默认开启需要,修改配置
```
appendonly   no #yes 开启aof

appendfilename "" #持久化文件名

appendfsync everysec #写入磁盘策略 everysec表示每秒写入

no-appendfsync-on-rewrite 是否在后台写时同步单写 默认no

aof-load-truncated  默认值是yes，在写入AOF文件时，突然断电写了一半，设置成yes会log继续，如果设置成no，就直接恢复失败了。

# config get * #查看配置
```

## dir redis.conf, 配置写入记录文件位置
* 一般redis无法写入缓存,是该目录没有权限
* 默认该配置是相对路径的,需要注意启动的位置

## 一般命令
```
keys * #查看所有key
```
