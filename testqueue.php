<?php
$splq = new SplQueue;
$time1=microtime(true);
for($i = 0; $i < 1000000; $i++)
{
    $data = "hello $i\n";
    $splq->push($data);
	
    if ($i % 100 == 99 and count($splq) > 100)
    {
        $popN = rand(10, 99);
        for ($j = 0; $j < $popN; $j++)
        {
            $splq->shift();
        }
    }
}

$popN = count($splq);
for ($j = 0; $j < $popN; $j++)
{
    $splq->pop();
}
$time2=microtime(true);
print($time2-$time1);

$arrq = array();
$time3=microtime(true);

for($i = 0; $i <100000; $i++)
{
    $data = "hello $i\n";
    $arrq[] = $data;
    if ($i % 100 == 99 and count($arrq) > 100)
    {
        $popN = rand(10, 99);
        for ($j = 0; $j < $popN; $j++)
        {
            array_shift($arrq);
        }
    }
}
$popN = count($arrq);
for ($j = 0; $j < $popN; $j++)
{
    array_shift($arrq);
}
$time4=microtime(true);
print_r("  " );
print($time4-$time3);