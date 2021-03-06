<?php
class Http {
    CONST HOST = "0.0.0.0";
    CONST PORT = 9911;

    public $http = null;
    public function __construct() {
        $this->http = new swoole_http_server(self::HOST, self::PORT);

        $this->http->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/usr/local/nginx/html/swooledemo/thinkphp/public/static",
                'worker_num' => 4,
            ]
        );

        $this->http->on("workerstart", [$this, 'onWorkerStart']);
        $this->http->on("request", [$this, 'onRequest']);
        $this->http->on("close", [$this, 'onClose']);

        $this->http->start();
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
        // /var/www/swooledemo/../../../../thinkphp/base.php
        define('APP_PATH', __DIR__.'/thinkphp/application/');
        //print_r(APP_PATH);
        // 加载框架里面的文件
        require __DIR__.'/thinkphp/base.php';
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
	//var_dump($_POST);
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
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd) {
        echo "clientid:{$fd}\n";
    }
}
//访问的路径=http://dear-wl.com.cn:9911/?s=index/index/singwa
new Http();