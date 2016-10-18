<?php

/**
 * Created by PhpStorm.
 * User: hong
 * Date: 10/17/16
 * Time: 11:51 AM
 */


class LinkUtilTest extends \PHPUnit\Framework\TestCase
{

    protected $uri = 'http://username:password@hostname:9090/path?arg=value#anchor';

    public function testParse() {
        $parts = \PhUtils\LinkUtil::parse($this->uri);

        $this->assertArrayHasKey('protocol', $parts);
        $this->assertArrayHasKey('auth_username', $parts);
        $this->assertArrayHasKey('auth_password', $parts);
        $this->assertArrayHasKey('host', $parts);
        $this->assertArrayHasKey('domain', $parts);
        $this->assertArrayHasKey('port', $parts);
        $this->assertArrayHasKey('path', $parts);

        $this->assertArrayHasKey('query', $parts);
        $this->assertArrayHasKey('fragment', $parts);

    }

    /**
     * @depends testParse
     */
    public function testBuildFromParts() {
        $uri = \PhUtils\LinkUtil::buildURLFromParts(\PhUtils\LinkUtil::parse($this->uri));

        $this->assertEquals($this->uri , $uri);
    }

    /**
     * @depends testBuildFromParts
     */
    public function testNormalizeURL() {

        $uri = \PhUtils\LinkUtil::normalizeURL('http://www.foo.com:80/path/');

        $this->assertEquals('http://www.foo.com/path/', $uri);

    }

    public function testCheckRegexPattern() {
        $this->assertTrue(\PhUtils\LinkUtil::checkRegexPattern('/an/'));

        $this->assertFalse(\PhUtils\LinkUtil::checkRegexPattern('fhs'));
    }

    public function testBuildFromLink() {

        $this->assertEquals(
            'http://foo.htm',
            \PhUtils\LinkUtil::buildURLFromLink("//foo.htm", ['protocol'=> 'http'])
        );

        $this->assertEquals(
            'http://test.com/foo.htm',
            \PhUtils\LinkUtil::buildURLFromLink(
                '/foo.htm',
                [
                    'protocol' => 'http://',
                    'host' => 'test.com',
                    'port' => ''
                ]
            )
            );

        $this->assertEquals(
            'http://test.com/test/foo.htm',
            \PhUtils\LinkUtil::buildURLFromLink(
                './foo.htm',
                [
                    'protocol' => 'http://',
                    'host' => 'test.com',
                    'port' => '',
                    'path' => '/test/'
                ]
            )
        );

        $this->assertEquals(
            'http://test.com/test/foo.htm',
            \PhUtils\LinkUtil::buildURLFromLink(
                'foo.htm',
                [
                    'protocol' => 'http://',
                    'host' => 'test.com',
                    'port' => '',
                    'path' => '/test/'
                ]
            )
        );

        $this->assertEquals(
            'http://test.com/test/foo.htm',
            \PhUtils\LinkUtil::buildURLFromLink(
                'http://test.com/test/foo.htm',
                [
                    'protocol' => 'http',
                    'host' => 'test.com',
                    'path' => 'test'
                ]
            )
        );

        $this->assertEquals('',
            \PhUtils\LinkUtil::buildURLFromLink('javascript:tset', [])
        );

        $this->assertEquals(
            'http://www.abc.com/test.php',
             \PhUtils\LinkUtil::buildURLFromLink(
                 '../../test.php',
                 [
                     'protocol'=> 'http://',
                     'host'=> 'www.abc.com',
                     'path'=> '/test/test/',
                     'port'=> ''
                 ]
             )
            );

        $this->assertEquals('',
            \PhUtils\LinkUtil::buildURLFromLink(
                '#s',
                []
            )
        );

        $this->assertEquals(
            'http://test.com/test/foo.htm?test',
            \PhUtils\LinkUtil::buildURLFromLink(
                '?test',
                [
                    'protocol' => 'http://',
                    'host' => 'test.com',
                    'port' => '',
                    'path' => '/test/',
                    'query' => '?test',
                    'file' => 'foo.htm'
                ]
            )
        );
    }

    public function testGetBaseUrlFromMetaTag() {
        $html = '<base href="http://www.w3school.com.cn/i/" />';
        $html_none = '';
        $this->assertEquals(
            'http://www.w3school.com.cn/i/',
            \PhUtils\LinkUtil::getBaseUrlFromMetaTag($html)
        );

        $this->assertNull(\PhUtils\LinkUtil::getBaseUrlFromMetaTag($html_none));

    }

    public function testGetRedirectURLFromHeader() {
        $header = 'location: '.$this->uri."\n";

        $header_none = '';

        $this->assertEquals(
            $this->uri,
            \PhUtils\LinkUtil::getRedirectURLFromHeader($header)
        );

        $this->assertNull(\PhUtils\LinkUtil::getRedirectURLFromHeader($header_none));

    }

    public function testCheckStringAgainstRegexArray() {
        $str = 'hello World';
        $this->assertTrue(
            \PhUtils\LinkUtil::checkStringAgainstRegexArray($str, ['/hello/', '/www/'])
        );

        $this->assertFalse(
            \PhUtils\LinkUtil::checkStringAgainstRegexArray($str, ['/www/', '/html/'])
        );
    }

    public function testGetHeaderValue() {
         $header = "\r\nContent-Type: text/html\r\n";

        $this->assertEquals('text/html',
            \PhUtils\LinkUtil::getHeaderValue($header, 'Content-Type'));

        $this->assertNull(\PhUtils\LinkUtil::getHeaderValue($header, 'transfer'));
    }

    public function testGetRootUrl() {
        $root_url = 'http://www.baidu.com';
        $url = $root_url . '/index.html';

        $this->assertEquals($root_url,
            \PhUtils\LinkUtil::getRootUrl($url));
    }

    public function testSerialize() {
        $file = "/test.text";
        $dir = "./test";

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        \PhUtils\LinkUtil::serializeToFile($dir.$file, 'test');

        $this->assertFileExists($dir.$file);

        $this->assertEquals(
            'test',
            \PhUtils\LinkUtil::deserializeFromFile($dir.$file)
        );

    }

    /**
     * @depends testSerialize
     */
    public function testRmDir() {

        $dir = './test';

        \PhUtils\LinkUtil::rmDir($dir);

        $this->assertFileNotExists($dir);
    }

    public function testSort2dArray() {
         $arr = [
             'test1'=> [
                 'sort'=>3,
             ],

             'test2' => [
                 'sort'=>1,
             ],

            'test3'=> [
                'sort' => 2
            ]
         ];

        $arr_sort = [

            'test2' => [
                'sort'=>1,
            ],

            'test3'=> [
                'sort' => 2
            ],

            'test1'=> [
                'sort'=>3,
            ],
        ];

        $arr2 = [
            'test1'=> [
                'sort'=>3,
            ],

            'test2' => [
                'sort'=>1,
            ],

            'test3'=> [
                'sort' => 2
            ]
        ];

        $arr2_sort = [
            'test1'=> [
                'sort'=>3,
            ],

            'test3'=> [
                'sort' => 2
            ],

            'test2' => [
                'sort'=> 1,
            ]
        ];


        \PhUtils\LinkUtil::sort2dArray($arr, 'sort');

        $this->assertEquals($arr_sort, $arr);

        \PhUtils\LinkUtil::sort2dArray($arr2, [1 , 3, 2]);

        $this->assertEquals(
            $arr2_sort,
            $arr2
        );
    }

    public function testGetSystemTempDir() {

        $this->assertFileExists(\PhUtils\LinkUtil::getSystemTempDir());
    }

    public function testGetMetaTagAttributes() {

        $html = '<meta name="keywords" content="HTML,ASP,PHP,SQL">';

        $html_none = '';

        $this->assertEquals(['keywords'=> 'html,asp,php,sql'], \PhUtils\LinkUtil::getMetaTagAttributes($html));

        $this->assertEmpty(\PhUtils\LinkUtil::getMetaTagAttributes($html_none));

    }

    public function testIsValidUrlString() {

        $this->assertTrue(\PhUtils\LinkUtil::isValidUrlString('http://www.baidu.com/index.php'));

        $this->assertFalse(\PhUtils\LinkUtil::isValidUrlString('||'));

    }

}
