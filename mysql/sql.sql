-- 按时间分组统计
select from_unixtime(createtime , '%Y-%m-%d %h') date, sum(energy)  from tl_chat_log where createtime >= UNIX_TIMESTAMP('2021-06-29 00:00:00') and createtime <= UNIX_TIMESTAMP('2021-06-30 00:00:00') and to_user_id = 69272 group by date
-- 修改自增的值
alter table tl_roomid_list AUTO_INCREMENT=100000000;