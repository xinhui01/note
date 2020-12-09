<?php
/*
 * @Author: xinhui
 * @Date: 2020-12-09 11:48:04
 * @Description: Date操作
 */

class Date
{
    /**
     * 将秒数转换成时分秒
     *
     * @param 秒数 $seconds
     * @return void
     */
    public function sec2Time($time)
    {
        if (is_numeric($time)) {
            $value = array(
                "years" => 0, "days" => 0, "hours" => 0,
                "minutes" => 0, "seconds" => 0,
            );
            $t = '';
            if ($time >= 31556926) {
                $value["years"] = floor($time / 31556926);
                $time = ($time % 31556926);
                $t .= $value["years"] . "年";
            }
            if ($time >= 86400) {
                $value["days"] = floor($time / 86400);
                $time = ($time % 86400);
                $t .= $value["days"] . "天";
            }
            if ($time >= 3600) {
                $value["hours"] = floor($time / 3600);
                $time = ($time % 3600);
                $t .= $value["hours"] . "小时";
            }
            if ($time >= 60) {
                $value["minutes"] = floor($time / 60);
                $time = ($time % 60);
                $t .= $value["minutes"] . "分";
            }
            $value["seconds"] = floor($time);
            //return (array) $value;
            $t .= $value["seconds"] . "秒";
            return $t;

        } else {
            return (bool) false;
        }
    }
    /**
     * 获取两个时间中的天数
     */
    public function periodDate($start_time,$end_time){
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $i=0;
        while ($start_time<=$end_time){
            $arr[$i]=date("Y-m-d",$start_time);
            $start_time = strtotime('+1 day',$start_time);
            $i++;
        }

        return $arr;
    }
}


  echo date("Ymd",strtotime("now")), "\n";
  echo date("Ymd",strtotime("-1 week Monday")), "\n";
  echo date("Ymd",strtotime("-1 week Sunday")), "\n";
  echo date("Ymd",strtotime("+0 week Monday")), "\n";
  echo date("Ymd",strtotime("+0 week Sunday")), "\n";
 
  echo "*********第几个月:";
  echo date('n');
  echo "*********本周周几:";
  echo date("w");
  echo "*********本月天数:";
  echo date("t");
  echo "*********";
 
  echo '<br>上周起始时间:<br>';
  echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"))),"\n";
  echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"))),"\n";
  echo '<br>本周起始时间:<br>';
  echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))),"\n";
  echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))),"\n";

  //从第几周找出该周的开始日期和结束日期
  $dayNumber = date('W') * 7;
  $weekDayNumber = date("w", mktime(0, 0, 0, 1, $dayNumber, date("Y")));//当前周的第几天
  $startNumber = $dayNumber - $weekDayNumber;
  echo date("Y-m-d", mktime(0, 0, 0, 1, $startNumber + 1, date("Y")));//开始日期
  echo date("Y-m-d", mktime(0, 0, 0, 1, $startNumber + 7, date("Y")));//结束日期 

  echo '<br>上月起始时间:<br>';
  echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y"))),"\n";
  echo date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y"))),"\n";
  echo '<br>本月起始时间:<br>';
  echo date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y"))),"\n";
  echo date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y"))),"\n";
 
  $season = ceil((date('n'))/3);//当月是第几季度
  echo '<br>本季度起始时间:<br>';
  echo date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))),"\n";
  echo date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))),"\n";
 
  $season = ceil((date('n'))/3)-1;//上季度是第几季度
  echo '<br>上季度起始时间:<br>';
  echo date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))),"\n";
  echo date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))),"\n";
?>