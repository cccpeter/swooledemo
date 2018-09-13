<?php
// 创建内存表
$table = new swoole_table(1024);
//table会随着进程的关闭而释放内存
//table底层基于hashmap set和get都是原子性操作，锁是行锁
// 内存表增加一列
$table->column('id', $table::TYPE_INT, 4);
$table->column('name', $table::TYPE_STRING, 64);
$table->column('age', $table::TYPE_INT, 3);
$table->create();

$table->set('cpeter2', ['id' => 1, 'name'=> 'singwa', 'age' => 30]);
// 另外一种方案
$table['cpeter2'] = [
    'id' => 2,
    'name' => 'singwa2',
    'age' => 31,
];

$table->decr('cpeter2', 'age', 2);
print_r($table['cpeter2']);

echo "delete start:".PHP_EOL;
$table->del('cpeter2');
print_r($table['cpeter2']);