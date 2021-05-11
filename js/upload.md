# js 分片上传问题
## 文件校验
### md5方法 https://codechina.csdn.net/mirrors/ihsmarkitosi/CryptoJS-v3.1.2/-/raw/master/rollups/md5.js?inline=false
### js默认读取的文件二进制,是utf-16编码的,其他语言是utf-8,js需要转换下

## md5 方法,还原utf-8
```js
    // 生成MD5的函数部分,并还原成utf-8 二进制
    function md5s(str) {
        var MD5 = CryptoJS.MD5(CryptoJS.enc.Latin1.parse(str)).toString();
        return MD5
    }
```

```js
    new Promise(function(){
        const filereader = new FileReader();
        filereader.readAsBinaryString(file);
        filereader.onload = function(){
            var md5str = md5s(this.result);
            $('#md5').val(md5str);
        }
    })
```
