<?php
class Ws {

    CONST HOST = "0.0.0.0";
    CONST PORT = 9912;
    public $ws = null;
    public function __construct() {
	$this->ws=new swoole_websocket_server(self::HOST,self::PORT);
	//配置静态文件的根目录
	$this->ws->set([
		// 'enable_static_handler'=>true,
		// 'document_root'=>"/var/www/swooledemo",
		'worker_num'=>2,
		'task_worker_num'=>2,
	]);
	//监听websocket连接的打开事件
	// $server->on('open','onOpen');
	// function opOpen($server,$request){
	// 	print_r($request->fd);
	// }
	// $server->on('message',function(swoole_websocket_server $server,$frame){
	// 	echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
	// 	$server->push($frame->fd,"singwa-push-secesss");
	// });
	// $server->on('close',function($ser,$fd){
	// 	echo "client {$fd} close\n";
	// });
	$this->ws->on('open',[$this,'opOpen']);
	$this->ws->on('message',[$this,'onMessage']);
	$this->ws->on('task',[$this,'onTask']);
	$this->ws->on('finish',[$this,'onFinish']);
	$this->ws->on('close',[$this,'onClose']);
	$this->ws->start();
}
	/**
	 *监听事件
	 *@param $ws 
	 *@param $request 
	 */
		public function opOpen($ws,$request){
			print_r($request->fd);
		}
 /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame) {
        echo "ser-push-message:{$frame->data}\n";
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
    }
    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     * @return string
     */
    public function onTask($serv, $taskId, $workerId, $data) {
        print_r($data);
        // 耗时场景 10s
        sleep(10);
        return "on task finish"; // 告诉worker，并返回给onFinish的$data
    }
    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }
    /**
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd) {
        echo "clientid:{$fd}\n";
    }
}
$obj = new Ws();