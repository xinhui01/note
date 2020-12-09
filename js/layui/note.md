# tablex.js
## 来源网络,自己改写

### 相对layui原来的,该模块添加求平均值,设置计算后保留多少位小数点问题,缺点没有配置avg的css,导致显示有些问题
```
avgRowTofixed #设置整体保留的小数点,cols里则对某列有效,其他的和统计的一样
```
# laydate.js
## 来源网络,支持快捷日期
```js
layui.use(['laydate'], function(){

    //定义接收本月的第一天和最后一天
    var startDate1 = new Date(new Date().setDate(1));
    //定义接收上个月的第一天和最后一天
    var startDate2 = new Date(new Date(new Date().setMonth(new Date().getMonth() - 1)).setDate(1));
    var endDate2 = new Date(new Date().setDate(0));

    var now = new Date(); //当前日期
    var nowDayOfWeek = now.getDay(); //今天本周的第几天
    var laydate = layui.laydate;
    lay(".layui-ldate").each(function(){
        laydate.render({
            elem: this,
            type: 'date',
            format: 'yyyy-MM-dd',
            range: '~',
            trigger: 'click',
            extrabtns: [
                {id: 'today', text: '今天', range: [now, now]},
                {
                    id: 'yesterday',
                    text: '昨天',
                    range: [new Date(new Date().setDate(new Date().getDate() - 1)), new Date(new Date().setDate(new Date().getDate() - 1))]
                },
                {
                    id: 'week',
                    text: '本周',
                    range: [new Date(new Date().setDate(new Date().getDate() - nowDayOfWeek + 1)), new Date()]
                },
                {
                    id: 'lastday-7',
                    text: '过去7天',
                    range: [new Date(new Date().setDate(new Date().getDate() - 7)), new Date(new Date().setDate(new Date().getDate() - 1))]
                },
                {
                    id: 'lastday-30',
                    text: '过去30天',
                    range: [new Date(new Date().setDate(new Date().getDate() - 30)), new Date(new Date().setDate(new Date().getDate() - 1))]
                },
                {id: 'thismonth', text: '本月', range: [startDate1, now]},
                {id: 'lastmonth', text: '上个月', range: [startDate2, endDate2]}
            ],
            done: function (val, stdate, ovdate) {
                //当确认选择时间后调用这里
            }
        });
    });
});
```