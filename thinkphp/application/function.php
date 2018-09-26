<?php
/**
 * @ 加密函数
 * @param $txt @加密文本
 * @param string $key @密钥
 * @return string
 */
function passport_encrypt($txt, $key = '0330118d86425b476dc7fa05dcbc99ab')
{
    $txt = 'lta.' . $txt;
    srand((double)microtime() * 1000000);
    $encrypt_key = md5(rand(0, 32000));
    $ctr = 0;
    $tmp = '';
    for ($i = 0; $i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]);
    }
    return base64_encode(passport_key($tmp, $key));
}

/**
 * @ 解密函数
 * @param $txt @解密文本
 * @param string $key @密钥
 * @return mixed
 */
function passport_decrypt($txt, $key = '0330118d86425b476dc7fa05dcbc99ab')
{
    $txt = passport_key(base64_decode($txt), $key);
    $tmp = '';
    for ($i = 0; $i < strlen($txt); $i++) {
        $md5 = $txt[$i];
        $tmp .= $txt[++$i] ^ $md5;
    }
    $value = explode('.', $tmp);
    return $value[1];
}

function passport_key($txt, $encrypt_key)
{
    $encrypt_key = md5($encrypt_key);
    $ctr = 0;
    $tmp = '';
    for ($i = 0; $i < strlen($txt); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
    }
    return $tmp;
}
/**
 * [pwd加密]
 * @param  [type] $password [description]
 * @return [type]           [description]
 */
function pwd_encrypt($password){
    // return md5(md5(SECRET_KEY).$password);
    return md5(md5(config('conf.SECRET_KEY')).$password);
}