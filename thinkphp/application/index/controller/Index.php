<?php
namespace app\index\controller;
class Index
{
    public function index()
    {
        echo '����index';
    }

    public function singwa() {
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 'hessdggsg' . $name.time();
    }

}
