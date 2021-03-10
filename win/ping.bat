chcp 65001
@echo off & title 不间断 Ping 检测
:: %1 start mshta vbscript:createobject("wscript.shell").run("""%~0"" ::",0)(window.close)&&exit
 
::设置IP地址或网址
set IP= admin.yuupni.com
 
::设置超时日志记录文件
set Log=Ping.Log
 
::设置错误信息描述，多个描述之间用英文逗号隔开，带有空格的用英文双引号括起来
set Error=请求超时,无法访问目标主机,请求找不到主机
 
echo 正在对 %IP% 进行不间断 Ping 检测中。。。
:Loop
ping %IP% -n "1">"%USERPROFILE%\Ping.$"
set Nt=%time:~,5%
set DT=%date:~,10% %Nt: =0%
for %%a in (%Error%) do (
    find "%%~a" "%tmp%\Ping.$" >nul && (echo %DT% %%~a)>>"%Log%"
)
goto Loop