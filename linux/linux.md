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

