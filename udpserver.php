<?php
//����Server���󣬼��� 127.0.0.1:9502�˿ڣ�����ΪSWOOLE_SOCK_UDP
$serv = new swoole_server("0.0.0.0", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP); 

//�������ݽ����¼�
$serv->on('Packet', function ($serv, $data, $clientInfo) {
    $serv->sendto($clientInfo['address'], $clientInfo['port'], "Server ".$data);
    var_dump($clientInfo);
});

//����������
$serv->start(); 