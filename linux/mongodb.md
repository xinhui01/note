## MongoDb
- 集合=mysql中的表
- 文档=mysql表中的行
- php 方法
```php
//链接mongodb
$manager = new MongoDB\Driver\Manager('mongodb://root:sjhc168@10.10.10.104:27017');

//查询
$filter = ['user_id'=>['$gt'=>0]]; //查询条件 user_id大于0
$options = [
'projection' => ['_id' => 0], //不输出_id字段
'sort' => ['user_id'=>-1] //根据user_id字段排序 1是升序，-1是降序
];
$query = new MongoDB\Driver\Query($filter, $options); //查询请求
$list = $manager->executeQuery('location.box',$query); // 执行查询 location数据库下的box集合
foreach ($list as $document) {
    print_r($document);
}


//插入
$bulk = new MongoDB\Driver\BulkWrite; //默认是有序的，串行执行
//$bulk = new MongoDB\Driver\BulkWrite(['ordered' => flase]);//如果要改成无序操作则加flase，并行执行
$bulk->insert(['user_id' => 2, 'real_name'=>'中国',]);
$bulk->insert(['user_id' => 3, 'real_name'=>'中国人',]);
$manager->executeBulkWrite('location.box', $bulk); //执行写入 location数据库下的box集合


//修改
$bulk->update(
    ['user_id' => 2],
    ['$set'=>['real_name'=>'中国国']
]);
//$set相当于mysql的 set，这里和mysql有两个不同的地方，
//1：字段不存在会添加一个字段;
//2：mongodb默认如果条件不成立，新增加数据，相当于insert
//如果条件不存在不新增加，可以通过设置upsert
//db.collectionName.update(query, obj, upsert, multi);
$bulk->update(
    ['user_id' => 5],
    [
        '$set'=>['fff'=>'中国国']
    ],
    ['multi' => true, 'upsert' => false]
//multi为true,则满足条件的全部修改,默认为true，如果改为false，则只修改满足条件的第一条
//upsert为 treu：表示不存在就新增
);
$manager->executeBulkWrite('location.box', $bulk); //执行写入 location数据库下的box集合


//删除
$bulk = new MongoDB\Driver\BulkWrite; //默认是有序的，串行执行
//$bulk = new MongoDB\Driver\BulkWrite(['ordered' => flase]);//如果要改成无序操作则加flase，并行执行
$bulk->delete(['user_id'=>5]);//删除user_id为5的字段
$manager->executeBulkWrite('location.box', $bulk); //执行写入 location数据库下的box集合
```
