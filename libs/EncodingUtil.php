<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 16-9-28
 * Time: 下午10:27.
 */

namespace PhUtils;

/**
 * Class EncodingUtil.
 */
class EncodingUtil
{
    /**
     * @param $content
     *
     * @return string
     */
    public static function decodeGZipContent($content)
    {
        return gzinflate(substr($content, 10, -8));
    }

    /**
     * @param $content
     *
     * @return bool
     */
    public static function isGzipEncoded($content)
    {
        return substr($content, 0, 3) === "\x1f\x8b\x08";
    }

    /**
     * Checks wether the given string is an UTF8-encoded string.
     *
     * Taken from http://www.php.net/manual/de/function.mb-detect-encoding.php
     * (comment from "prgss at bk dot ru")
     *
     * @param string $string The string
     *
     * @return bool TRUE if the string is UTF-8 encoded.
     */
    public static function isUtf8String($string)
    {
        $sample = @iconv('utf-8', 'utf-8', $string);

        return md5($sample) == md5($string);
    }

    /**
     * Decodes all HTML-entities in the given string including numeric and hexadecimal character references.
     *
     * @param string $string
     *
     * @return string
     */
    public static function decodeHtmlEntities($string)
    {
        // Entities-replacements
        $entities = ["'&(quot|#34);'i",
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i"];

        $entity_replacements = array('"',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            chr(162),
            chr(163),
            chr(169), );

        $string = preg_replace($entities, $entity_replacements, $string);


        // Numeric haracter reference replacement (non-HEX), like &#64; => "@"
        $string = preg_replace_callback(
            '/&#([0-9]{1,4});/ i', function ($m) {
                return chr($m[1]);
            }, $string
        );

        // Numeric character reference replacement (HEX), like &#x2f; => "/"
        $string = preg_replace_callback(
            '/&#x([0-9a-z]{2,4});/ i', function ($m) {
                return chr(hexdec($m[1]));
            }, $string
        );

        return $string;
    }
}
