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

//        $UnknownUri = \PhUtils\LinkUtil::normalizeURL('hepp/fe/no a uri');
//
//        $this->assertNull($UnknownUri);

        $uri = \PhUtils\LinkUtil::normalizeURL('http://www.foo.com:80/path/');

        $this->assertEquals('http://www.foo.com/path/', $uri);

    }


}
