<?php
class Ws{
    CONST HOST='0.0.0.0';
    CONST PORT='8811';
    public $ws=null;
    public function _construct(){
        $this->ws=new swoole_hwebsocket_server(self::HOST,self::PORT);
        $this->ws->set(
            [
                'enable_static_handler'=>true,
                'document_root'=>'/var/www/swooledemo/thinkphp/public/static',
                'worker_num'=>4,
                'task_worker_num'=>4,
            ]
        );
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('workerstart',[$this,'onWorkerStart']);
        $this->ws->on('request',[$this,'onRequest']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->start();
    }
    public function onOpen($ws,$request){
        var_dump($request->fd);
        // if($request->fd==1){
        //     swoole_timer_tick(2000,function($timer_id){
        //         echo "2s:timerId:{$timer_id}\n";
        //     });
        // }
    }
    public 
}