<?php
namespace app\index\controller;
class Index
{
    public function index()
    {
        echo 'ÎÒÊÇindex';
    }

    public function singwa() {
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 'hessdggsg' . $name.time();
    }
    public function connectionredis(){
        redis = new \Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->set('sing',"24124");
        
    }
}
