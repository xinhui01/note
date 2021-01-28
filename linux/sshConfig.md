# ssh config 文件配置
### host 别名
### hostname 主机名,可以是域名
### Port 端口
### User 用户名

## known_hosts
```
ssh会把你每个你访问过计算机的公钥(public key)都记录在~/.ssh/known_hosts
OpenSSH会核对公钥。如果公钥不同，OpenSSH会发出警告，避免你受到DNS Hijack之类的攻击。
```
## 常用配置
```
Host 47.115.93.250
HostName 47.115.93.250
User root
Port 22
IdentityFile "C:\Users\Administrator\.ssh\id_rsa11"
```

## [更多配置解释](https://www.cnblogs.com/jingwu/articles/5598385.html)