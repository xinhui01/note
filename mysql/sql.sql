-- 按时间分组统计
select from_unixtime(createtime , '%Y-%m-%d %h') date, sum(energy)  from tl_chat_log where createtime >= UNIX_TIMESTAMP('2021-06-29 00:00:00') and createtime <= UNIX_TIMESTAMP('2021-06-30 00:00:00') and to_user_id = 69272 group by date
-- 修改自增的值
alter table tl_roomid_list AUTO_INCREMENT=100000000;

-- 通过whereor 优先匹配排序

SELECT v.*
FROM video v
WHERE v.name LIKE '%topspeed%' OR v.name LIKE '%audi%'
ORDER BY (v.name LIKE '%topspeed%') + (v.name LIKE '%audi%') DESC;

-- 理论基础:在数值上下文中，如果为真，则MySQL条件表达式返回1，否则返回0。因此，两个条件都匹配的视频得到的值是2，而其中一个条件匹配的视频只得到1。您可以对此使用降序排序。我在ORDER BY子句中添加了另一个条件，通过打破约束来获得确定性排序