<?php

class Licence_API
{
    private static $url = "http://new.aijqr.com/";
    private static $POST = "POST1"; //POST1 或者 POST2

    private $ID;
    private $Key;

    function __construct($_id, $_key)
    {
        $this->ID = $_id;
        $this->Key = $_key;
    }

    public function act($_act)
    {
        return self::_act($this->ID, $this->Key, $_act);
    }

    public static function _act($_id, $_key, $_act)
    {

        $POST = (self::$POST == "POST1" or self::$POST == "POST2") ? self::$POST : "POST1";
        $return = call_user_func(
            array("self",$POST),
            self::$url . "api.php?ID=" . $_id,
            base64_encode(self::AES_Encode(json_encode($_act), $_key))
        );
        return json_decode($return,true);
    }

    static function AES_Encode($data, $key)
    {
        $data = self::addPKCS7Padding($data);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        return $iv . $encrypted;
    }

    /**
     * 解密方法
     * @param string $str
     * @return string
     */
    static function AES_Decode($data, $key)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
        $iv = mb_substr($data, 0, 32, 'latin1');
        mcrypt_generic_init($td, $key, $iv);
        $data = mb_substr($data, 32, mb_strlen($data, 'latin1'), 'latin1');
        $data = mdecrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return self::stripPKCS7Padding(trim($data));
    }

    /**
     * 填充算法
     * @param string $source
     * @return string
     */
    static function addPKCS7Padding($source)
    {
        $source = trim($source);
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($source) % $block);
        if ($pad <= $block) {
            $char = chr($pad);
            $source .= str_repeat($char, $pad);
        }
        return $source;
    }

    /**
     * 移去填充算法
     * @param string $source
     * @return string
     */
    static function stripPKCS7Padding($source)
    {
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        $char = substr($source, -1, 1);
        $num = ord($char);
        if ($num > $block) {
            return $source;
        }
        $len = strlen($source);
        for ($i = $len - 1; $i >= $len - $num; $i--) {
            if (ord(substr($source, $i, 1)) != $num) {
                return $source;
            }
        }
        $source = substr($source, 0, -$num);
        return $source;
    }

    static function POST1($url, $post_data = '', $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }


    static function POST2($url, $data)
    {
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $data
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
