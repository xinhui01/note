# 修改wsl2 磁盘存贮位置 
```
打开 注册表
计算机\HKEY_CURRENT_USER\SOFTWARE\Microsoft\Windows\CurrentVersion\Lxss\{4f1010df-ebd0-45b2-96b4-2f67011f61e8}
修改 basepath

源路径 C:\Users\Administrator\AppData\Local\Packages\CanonicalGroupLimited.Ubuntu20.04onWindows_79rhkp1fndgsc
```
# 删除微软商店下载包
```
C:\Windows\SoftwareDistribution\Download
C:\Windows\SoftwareDistribution\DataStore
```

# wslg 配置
```
ubuntu 默认开启ibus输入法
安装拼音输入法过后,乱码,需要安装中文字体
goland 开启后无法使用拼音输入法,需要在启动sh文件头部加入
export XMODIFIERS="@im=ibus"
export GTK_IM_MODULE="ibus"
export QT_IM_MODULE="ibus" 
```