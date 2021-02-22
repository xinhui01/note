# wsl2 固定ip问题
## [详细解释](https://www.cnblogs.com/kuangdaoyizhimei/p/14175143.html)
```
:: wsl ip设置
wsl -d Ubuntu -u root ip addr add 192.168.50.16/24 broadcast 192.168.50.255 dev eth0 label eth0:1
:: win ip设置
netsh interface ip add address "vEthernet (WSL)" 192.168.50.88 255.255.255.0
```