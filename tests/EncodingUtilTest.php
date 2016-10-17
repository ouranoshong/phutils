<?php

/**
 * Created by PhpStorm.
 * User: hong
 * Date: 10/17/16
 * Time: 9:35 AM
 */
class EncodingUtilTest extends \PHPUnit\Framework\TestCase
{

    protected function data() {
        return 'Hello World';
    }

    protected function genGizEncodingData() {
        return gzencode(($this->data()));
    }

    public function testIsGzipEncoded() {

        $this->assertEquals(true, \PhUtils\EncodingUtil::isGzipEncoded($this->genGizEncodingData()));

        $this->assertEquals(false, \PhUtils\EncodingUtil::isGzipEncoded($this->data()));
    }

    public function testDecodeGZipContent() {
        $this->assertEquals($this->data(), \PhUtils\EncodingUtil::decodeGZipContent($this->genGizEncodingData()));
    }

    public function testIsUtf8String() {
        $this->assertEquals(mb_check_encoding($this->data(), 'utf-8'), \PhUtils\EncodingUtil::isUtf8String($this->data()));
    }

    public function testDecodeHtmlEntities() {

        $this->assertEquals(' ', \PhUtils\EncodingUtil::decodeHtmlEntities('&nbsp;'));
        $this->assertEquals(' ', \PhUtils\EncodingUtil::decodeHtmlEntities('&#160;'));

        $this->assertEquals('<', \PhUtils\EncodingUtil::decodeHtmlEntities('&lt;'));
        $this->assertEquals('<', \PhUtils\EncodingUtil::decodeHtmlEntities('&#60;'));

        $this->assertEquals('>', \PhUtils\EncodingUtil::decodeHtmlEntities('&gt;'));
        $this->assertEquals('>', \PhUtils\EncodingUtil::decodeHtmlEntities('&#62;'));

        $this->assertEquals('&', \PhUtils\EncodingUtil::decodeHtmlEntities('&amp;'));
        $this->assertEquals('&', \PhUtils\EncodingUtil::decodeHtmlEntities('&#38;'));

        // 'inverted exclamation mark'
        $this->assertEquals(chr(161), \PhUtils\EncodingUtil::decodeHtmlEntities('&iexcl;'));
        $this->assertEquals(chr(161), \PhUtils\EncodingUtil::decodeHtmlEntities('&#161;'));

        // '￠'
        $this->assertEquals(chr(162), \PhUtils\EncodingUtil::decodeHtmlEntities('&cent;'));
        $this->assertEquals(chr(162), \PhUtils\EncodingUtil::decodeHtmlEntities('&#162;'));

        //'£'
        $this->assertEquals(chr(163), \PhUtils\EncodingUtil::decodeHtmlEntities('&pound;'));
        $this->assertEquals(chr(163), \PhUtils\EncodingUtil::decodeHtmlEntities('&#163;'));

        //copyright
        $this->assertEquals(chr(169), \PhUtils\EncodingUtil::decodeHtmlEntities('&copy;'));
        $this->assertEquals(chr(169), \PhUtils\EncodingUtil::decodeHtmlEntities('&#169;'));
    }

}
