<?php
declare(strict_types=1);

namespace App\Utils;

/**
 * Class StringUtil
 * @package App\Utils
 */
class StringUtil
{

    /**
     * 生成密码
     * @param string $pass
     * @param string $salt
     * @return string
     */
    public static function generatePassword(string $pass, string $salt): string
    {
        return md5(md5($pass) . md5($salt));
    }

    /**
     * 生成随机字符串
     * @param int $length
     * @return string
     */
    public static function generateRandStr(int $length = 32): string
    {
        $md5 = md5(uniqid(md5((string)time())) . mt_rand(10000, 9999999));
        return substr($md5, 0, $length - 1);
    }
}