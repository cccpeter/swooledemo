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
                'document_root'=>'/usr/local/nginx/html/swooledemo/thinkphp/public/static',
                'worker_num'=>4,
                'task_worker_num'=>4,
            ]
        );
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('workerstart',[$this,'onWorkerStart']);
        $this->ws->on('request',[$this,'onRequest']);
        $this->ws->on('task',[$this,'onTask']);
        // $this->ws->on('finish',[$this,'onFinish']);
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
    public function onMessage($ws,$frame){
		echo "ser-push-message:{$frame->data}\n";
		$ws->push($frame->fd,"server-push:".date("Y-m-d H:i:s"));
	}
	/**
     * 此事件在Worker进程/Task进程启动时发生,这里创建的对象可以在进程生命周期内使用
     * 在onWorkerStart中加载框架的核心文件后
     * 1.不用每次请求都加载框架核心文件，提高性能
     * 2.可以在后续的回调中继续使用框架的核心文件或者类库
     *
     * @param $server
     * @param $worker_id
     */
    public function onWorkerStart($server,  $worker_id) {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/application/');
        // 加载框架里面的文件
        require __DIR__ . '/thinkphp/base.php';
    }

    /**
     * request回调
     * 解决上一次输入的变量还存在的问题例：$_SERVER  =  []
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response) {
        $_SERVER  =  [];
        if(isset($request->server)) {
            foreach($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->header)) {
            foreach($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if(isset($request->get)) {
            foreach($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
        $_POST = [];
        if(isset($request->post)) {
            foreach($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $_POST['http_server'] = $this->http;

        ob_start();
        // 执行应用并响应
        try {
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        }catch (\Exception $e) {
            // todo
        }

        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }
      /**
     * Task任务分发
     */
    public function onTask($serv, $taskId, $workerId, $data) {

        // 分发 task 任务机制，让不同的任务 走不同的逻辑
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $flag = $obj->$method($data['data']);

        return $flag; // 告诉worker
    }
    public function onClose($ws,$fd){
		\app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'), $fd);
        echo "clientid:{$fd}\n";
	}
}