<?php
/**
 * 字符串类
 */
class Http
{

    /**
     * 发送一个POST请求
     * @param string $url     请求URL
     * @param array  $params  请求参数
     * @param array  $options 扩展参数
     * @return mixed|string
     */
    public static function post($url, $params = [], $options = [])
    {
        $req = self::sendRequest($url, $params, 'POST', $options);
        return $req['ret'] ? $req['msg'] : '';
    }

    /**
     * 发送一个GET请求
     * @param string $url     请求URL
     * @param array  $params  请求参数
     * @param array  $options 扩展参数
     * @return mixed|string
     */
    public static function get($url, $params = [], $options = [])
    {
        $req = self::sendRequest($url, $params, 'GET', $options);
        return $req['ret'] ? $req['msg'] : '';
    }

    /**
     * CURL发送Request请求,含POST和REQUEST
     * @param string $url     请求的链接
     * @param mixed  $params  传递的参数
     * @param string $method  请求的方法
     * @param mixed  $options CURL的参数
     * @return array
     */
    public static function sendRequest($url, $params = [], $method = 'POST', $options = [])
    {
        $method = strtoupper($method);
        $protocol = substr($url, 0, 5);
        $query_string = is_array($params) ? http_build_query($params) : $params;

        $ch = curl_init();
        $defaults = [];
        if ('GET' == $method) {
            $geturl = $query_string ? $url . (stripos($url, "?") !== false ? "&" : "?") . $query_string : $url;
            $defaults[CURLOPT_URL] = $geturl;
        } else {
            $defaults[CURLOPT_URL] = $url;
            if ($method == 'POST') {
                $defaults[CURLOPT_POST] = 1;
            } else {
                $defaults[CURLOPT_CUSTOMREQUEST] = $method;
            }
            $defaults[CURLOPT_POSTFIELDS] = $query_string;
        }

        $defaults[CURLOPT_HEADER] = false;
        $defaults[CURLOPT_USERAGENT] = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36";
        $defaults[CURLOPT_FOLLOWLOCATION] = true;
        $defaults[CURLOPT_RETURNTRANSFER] = true;
        $defaults[CURLOPT_CONNECTTIMEOUT] = 3;
        $defaults[CURLOPT_TIMEOUT] = 3;

        // disable 100-continue
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

        if ('https' == $protocol) {
            $defaults[CURLOPT_SSL_VERIFYPEER] = false;
            $defaults[CURLOPT_SSL_VERIFYHOST] = false;
        }

        curl_setopt_array($ch, (array)$options + $defaults);

        $ret = curl_exec($ch);
        $err = curl_error($ch);

        if (false === $ret || !empty($err)) {
            $errno = curl_errno($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            return [
                'ret'   => false,
                'errno' => $errno,
                'msg'   => $err,
                'info'  => $info,
            ];
        }
        curl_close($ch);
        return [
            'ret' => true,
            'msg' => $ret,
        ];
    }

    /**
     * 异步发送一个请求
     * @param string $url    请求的链接
     * @param mixed  $params 请求的参数
     * @param string $method 请求的方法
     * @return boolean TRUE
     */
    public static function sendAsyncRequest($url, $params = [], $method = 'POST')
    {
        $method = strtoupper($method);
        $method = $method == 'POST' ? 'POST' : 'GET';
        //构造传递的参数
        if (is_array($params)) {
            $post_params = [];
            foreach ($params as $k => &$v) {
                if (is_array($v)) {
                    $v = implode(',', $v);
                }
                $post_params[] = $k . '=' . urlencode($v);
            }
            $post_string = implode('&', $post_params);
        } else {
            $post_string = $params;
        }
        $parts = parse_url($url);
        //构造查询的参数
        if ($method == 'GET' && $post_string) {
            $parts['query'] = isset($parts['query']) ? $parts['query'] . '&' . $post_string : $post_string;
            $post_string = '';
        }
        $parts['query'] = isset($parts['query']) && $parts['query'] ? '?' . $parts['query'] : '';
        //发送socket请求,获得连接句柄
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 3);
        if (!$fp) {
            return false;
        }
        //设置超时时间
        stream_set_timeout($fp, 3);
        $out = "{$method} {$parts['path']}{$parts['query']} HTTP/1.1\r\n";
        $out .= "Host: {$parts['host']}\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        if ($post_string !== '') {
            $out .= $post_string;
        }
        fwrite($fp, $out);
        //不用关心服务器返回结果
        //echo fread($fp, 1024);
        fclose($fp);
        return true;
    }

    /**
     * 发送文件到客户端
     * @param string $file
     * @param bool   $delaftersend
     * @param bool   $exitaftersend
     */
    public static function sendToBrowser($file, $delaftersend = true, $exitaftersend = true)
    {
        if (file_exists($file) && is_readable($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment;filename = ' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check = 0, pre-check = 0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            if ($delaftersend) {
                unlink($file);
            }
            if ($exitaftersend) {
                exit;
            }
        }
    }
    /**
     * 模拟post表单Post提交
     */
    public function httpPostBuildData($url,$param){
        #手工组包
        $data = '';
        $id = uniqid();
        $this->buildDataFun($param,$id,$data);
        $data .=  "--".$id . "--";
        #手工组包
     
        // echo $data;
        $ch = curl_init();//初始化
        curl_setopt($ch,CURLOPT_URL,$url);//设置URL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//设置允许https访问。忽略证书错误
        curl_setopt($ch,CURLOPT_POST,1);//设置get或者post
        curl_setopt($ch,CURLOPT_HTTPHEADER,[
            'Expect:  ',//PHP的坑 设置POST不发送 HTTP/1.1 100 Continue 
            "Content-Type: multipart/form-data; boundary=".$id,
            "Content-Length: " . strlen($data)
        ]);//设置协议附加头head
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//提交POST内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//在执行curl_exec返回结果
        $ret=curl_exec($ch);//执行
        curl_close($ch); 
        return $ret;
    }
     #手工组包
     public function buildDataFun($param,$id,&$data,&$path='',$iii=0){
        if(isset($param['filename'])){
            #正常时是这样传送file文件的
            #$file = curl_file_create(ROOT_PATH . '/public/154106488401000050.pdf',"application/octet-stream");
    
            $data .=  "--".$id . "\r\n"
            . 'Content-Disposition: form-data; name="'. $path.'"; filename="' . $param['filename'] . '"' . "\r\n"
            . 'Content-Type: application/octet-stream'."\r\n\r\n";
            
            $data .= $param['file'] . "\r\n";
            return ;
        }
        if(is_array($param) && $path != ''){
            // $data .= "--" . $id . "\r\n". 'Content-Disposition: form-data; name="' .$path . "\"\r\n\r\n". $content . "\r\n";  
            $data .= "--" . $id . "\r\n". 'Content-Disposition: form-data; name="' .$path . "\"\r\n\r\n". $param . "\r\n";  
        }
        foreach ($param as $name => $content) {
            if($path == ''){
                $path1 = $name;
            }else{
                $path1 = $path."[".$name."]";
            }
            if(is_array($content)) {
                $this->buildDataFun($content,$id,$data,$path1);
            } else{
                $data .= "--" . $id . "\r\n"
                . 'Content-Disposition: form-data; name="' .$path1 . "\"\r\n\r\n"
                . $content . "\r\n";  
            }
            
        }
        return ;
    }
}
