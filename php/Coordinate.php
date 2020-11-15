<?php
/**
球是一个近乎标准的椭球体，它的赤道半径为6378.140千米，极半径为 6356.755千米，平均半径6371.004千米。如果我们假设地球是一个完美的球体，那么它的半径就是地球的平均半径，记为R。如果以0度经线为基 准，那么根据地球表面任意两点的经纬度就可以计算出这两点间的地表距离（这里忽略地球表面地形对计算带来的误差，仅仅是理论上的估算值）。设第一点A的经 纬度为(LonA, LatA)，第二点B的经纬度为(LonB, LatB)，按照0度经线的基准，东经取经度的正值(Longitude)，西经取经度负值(-Longitude)，北纬取90-纬度值(90- Latitude)，南纬取90+纬度值(90+Latitude)，则经过上述处理过后的两点被计为(MLonA, MLatA)和(MLonB, MLatB)。那么根据三角推导，可以得到计算两点距离的如下公式：

C=sin(MLatA)*sin(MLatB)*cos(MLonA-MLonB)+cos(MLatA)*cos(MLatB)

Distance=R*Arccos(C)*Pi/180

这里，R和Distance单位是相同，如果是采用6371.004千米作为半径，那么Distance就是千米为单位，如果要使用其他单位，比如mile，还需要做单位换算，1千米=0.621371192mile，如果仅对经度作正负的处理，而不对纬度作90-Latitude(假设都是北半球，南半球只有澳洲具有应用意义)的处理，那么公式将是：

C=sin(LatA)*sin(LatB)+cos(LatA)*cos(LatB)*cos(MLonA-MLonB)

Distance=R*Arccos(C)*Pi/180

以上通过简单的三角变换就可以推出。

如果三角函数的输入和输出都采用弧度值，那么公式还可以写作：

C=sin(LatA*Pi/180)*sin(LatB*Pi/180)+cos(LatA*Pi/180)*cos(LatB*Pi/180)*cos((MLonA-MLonB)*Pi/180)

Distance=R*Arccos(C)*Pi/180

也就是：

C=sin(LatA/57.2958)*sin(LatB/57.2958)+cos(LatA/57.2958)*cos(LatB/57.2958)*cos((MLonA-MLonB)/57.2958)

Distance=R*Arccos(C)=6371.004*Arccos(C) kilometer=0.621371192*6371.004*Arccos(C)mile=3958.758349716768*Arccos(C) mile
*/
class Coordinate
{
    //单位千米
    const EARTH_RADIUS = 6370.996; 
    const PI = 3.1415926;
        /**
     * 计算两点地理坐标之间的距离
     * @param  Decimal $longitude1 起点经度
     * @param  Decimal $latitude1  起点纬度
     * @param  Decimal $longitude2
     * @param  Decimal $latitude2  终点纬度
     * @param  Int     $unit       单位 1:米 2:公里
     * @param  Int     $decimal    精度 保留小数位数
     * @return Decimal
     */
    public function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2)
    {
        // 地球半径系数
        $EARTH_RADIUS = self::EARTH_RADIUS;
        $PI = self::PI;

        //用haversine公式计算球面两点间的距离。
        //经纬度转换成弧度
        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if($unit==2){
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
    /**
     *
     * @param lat1    纬度1
     * @param lng1    经度1
     * @param lat2    纬度2
     * @param lng2    经度2
     * @return        方向
     */
    public function getDirection($lat1, $lng1, $lat2, $lng2) 
    {
        $angle = $this->getAngle($lat1, $lng1, $lat2, $lng2);
        if (($angle <= 10) || ($angle > 350))
            return "东";
        if (($angle > 10) && ($angle <= 80))
            return "东北";
        if (($angle > 80) && ($angle <= 100))
            return "北";
        if (($angle > 100) && ($angle <= 170))
            return "西北";
        if (($angle > 170) && ($angle <= 190))
            return "西";
        if (($angle > 190) && ($angle <= 260))
            return "西南";
        if (($angle > 260) && ($angle <= 280))
            return "南";
        if (($angle > 280) && ($angle <= 350))
            return "东南";
        return "";
    }
    /**
     * 获取偏移角度
     */
    public function getAngle($lat1, $lng1, $lat2, $lng2) {
        $x1 = $lng1;
        $y1 = $lat1;
        $x2 = $lng2;
        $y2 = $lat2;
        $pi = self::PI;
        $w1 = $y1 / 180 * $pi;
        $j1 = $x1 / 180 * $pi;
        $w2 = $y2 / 180 * $pi;
        $j2 = $x2 / 180 * $pi;
        $ret = 0.00;
        if ($j1 == $j2) {
            if ($w1 > $w2)
                return 270; // 北半球的情况，南半球忽略
            elseif ($w1 < $w2)
                return 90;
            else
                return -1;// 位置完全相同
        }
        $ret = 4 * pow(sin(($w1 - $w2) / 2), 2)- pow(sin(($j1 - $j2) / 2) * (cos($w1) - cos($w2)),2);
        $ret = sqrt($ret);
        $temp = (sin(abs($j1 - $j2) / 2) * (cos($w1) + cos($w2)));
        $ret = $ret / $temp;
        $ret = atan($ret) / $pi * 180;
        if ($j1 > $j2){ // 1为参考点坐标
            if ($w1 > $w2)
                $ret += 180;
            else
                $ret = 180 - $ret;
        } elseif ($w1 > $w2)
            $ret = 360 - $ret;
        return $ret;
    }
}