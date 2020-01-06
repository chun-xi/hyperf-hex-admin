<?php


namespace App\Utils;


class DateUtil
{
    /**
     * 获取当前时间
     * @param string|null $format
     * @return string
     */
    public static function current(string $format = null): string
    {
        return $format ? date($format, time()) : date("Y-m-d H:i:s", time());
    }
}