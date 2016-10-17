<?php

/**
 * Created by PhpStorm.
 * User: hong
 * Date: 10/17/16
 * Time: 11:51 AM
 */
class LinkUtilTest extends \PHPUnit\Framework\TestCase
{
    public function testParse() {
        $uri = 'http://username:password@hostname:9090/path?arg=value#anchor';

        $parts = \PhUtils\LinkUtil::parse($uri);

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
}
