## 自动压缩日志文件

```shell
30 7 * * * /usr/bin/find /opt/nginx/html/play/runtime/log/ -name *.log -mtime +7 |xargs bzip2




### delete old bak_file ###
find /data/local_bak/ -type f -mtime +7 -exec rm -fr {} \;

### new bak ###
baktime=`date +"%Y%m%d"`
cd /opt
tar czf /data/local_bak/nginx.bak-${baktime}.tgz nginx

```
## Linux下查看tcp连接数及状态命令：
```shell
netstat -n | awk '/^tcp/ {++S[$NF]} END {for(a in S) print a, S[a]}'

```
## 大量TIME_WAIT状态的连接
- 很多时候，出现大量的TIME_WAIT状态的连接，往往是因为网站程序代码中没有使用mysql.colse()，才导致大量的mysql  TIME_WAIT
- 根据TCP协议定义的3次握手断开连接规定,发起socket主动关闭的一方 socket将进入TIME_WAIT状态,TIME_WAIT状态将持续2个MSL(Max Segment Lifetime),在Windows下默认为4分钟,即240秒,TIME_WAIT状态下的socket不能被回收使用. 具体现象是对于一个处理大量短连接的服务器,如果是由服务器主动关闭客户端的连接,将导致服务器端存在大量的处于TIME_WAIT状态的socket, 甚至比处于Established状态下的socket多的多,严重影响服务器的处理能力,甚至耗尽可用的socket,停止服务. TIME_WAIT是TCP协议用以保证被重新分配的socket不会受到之前残留的延迟重发报文影响的机制,是必要的逻辑保证
