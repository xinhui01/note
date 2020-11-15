<?php
/*
 * @Author: xinhui
 * @Date: 2020-06-18 11:19:27
 * @Description: 解析url参数
 */ 
class Query
{
    /**
     * 解析get参数
     */
    public function convertUrlQuery($query)
    {
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
    /**
     * 将参数变为字符串
     * @param $array_query
     * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0&region=0&s=1&page=1' (length=73)
     */
    public function getUrlQuery($array_query)
    {
        $tmp = array();
        foreach($array_query as $k=>$param)
        {
            $tmp[] = $k.'='.$param;
        }
        $params = implode('&',$tmp);
        return $params;
    }
    public function getUri($query){
        $request_uri = $_SERVER["REQUEST_URI"];
        $url = strstr($request_uri,'?') ? $request_uri :  $request_uri.'?';

        if(is_array($query))
            $url .= http_build_query($query);
        else if($query != "")
            $url .= "&".trim($query, "?&");

        $arr = parse_url($url);

        if(isset($arr["query"])){
            parse_str($arr["query"], $arrs);
            unset($arrs["page"]);
            $url = $arr["path"].'?'.http_build_query($arrs);
        }

        if(strstr($url, '?')) {
            if(substr($url, -1)!='?')
                $url = $url.'&';
        }else{
            $url = $url.'?';
        }

        return $url;
    }

    public function auto_get($except=array()){
        $p_url=$_SERVER['QUERY_STRING'];
        parse_str($p_url,$arr);
        foreach($except as $k=>$v){
            if(array_key_exists($v,$arr)){
                unset($arr[$v]);
            }
        }
        return http_build_query($arr);
    }
}