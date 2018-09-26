<?php
namespace app\index\controller;

use think\Controller;
use app\common\lib\Util;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
class Login extends Controller
{
    public function index() {
        // phone code   http://119.29.189.104:8811/?s=index/Login/index&phone_num=13217554571&code=1369
        $phoneNum = intval($_GET['phone_num']);
        $code = intval($_GET['code']);
        if(empty($phoneNum) || empty($code)) {
            return Util::show(config('code.error'), 'phone or code is error');
        }

        // redis code
        try {
            $redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));
        }catch (\Exception $e) {
            echo $e->getMessage();
        }
//echo $redisCode.''.$code;die; noproblem
        if($redisCode == $code) {
            // 写入redis
            $data = [
                'user' => $phoneNum,
                'srcKey' => md5(Redis::userkey($phoneNum)),
                'time' => time(),
                'isLogin' => true,
            ];
            Predis::getInstance()->set(Redis::userkey($phoneNum), $data);
			//setcookie('key','123',time()+3600);//cookiesession
			echo passport_encrypt('123');
            return Util::show(config('code.success'), 'ok', $data);
        } else {
            return Util::show(config('code.error'), 'login error');
        }
        // redis.so
    }
}
