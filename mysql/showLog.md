## 开启慢查询
```sql
-- show variables like 'slow_query%';
-- set global slow_query_log_file='/data/mysql/slow_query.log'
-- set global slow_query_log='ON'
set global long_query_time=1;
```
```
delete from tl_entry where id in ( select id from (select id from tl_entry where device_id in ( select device_id from `tl_entry`  group by device_id having count(*) > 1) and id not in ( select min(id) as id from `tl_entry`  group by device_id having count(*) > 1)) e)


select id from tl_entry where device_id in ( select device_id from `tl_entry`  group by device_id having count(*) > 1) and id not in ( select min(id) as id from `tl_entry`  group by device_id having count(*) > 1)
```