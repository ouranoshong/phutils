<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 16-10-15
 * Time: 下午12:59
 */

namespace PhUtils;


/**
 * Class DNSUtil
 *
 * @package PhUtils
 */
class DNSUtil
{
    /**
     * @var array
     */
    public static $HOST_IP_TABLE = [];

    /**
     * @param string $name
     *
     * @return mixed|null|string
     */
    public static function getIpByHostName($name = '') 
    {

        if (!$name) {
            return null;
        }

        if (isset(self::$HOST_IP_TABLE[$name])) {

            return self::$HOST_IP_TABLE[$name];

        } else {
            $ip = gethostbyname($name);
            self::$HOST_IP_TABLE[$name] = $ip;
            return $ip;
        }

        return null;
    }

}
