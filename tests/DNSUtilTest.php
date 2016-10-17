<?php

/**
 * Created by PhpStorm.
 * User: hong
 * Date: 10/17/16
 * Time: 11:17 AM
 */
class DNSUtilTest extends \PHPUnit\Framework\TestCase
{
    public function testGetIpByHostName() {

        $hostName = 'www.baidu.com';

        $hostIp = \PhUtils\DNSUtil::getIpByHostName($hostName);

        $this->assertContains($hostIp, \PhUtils\DNSUtil::$HOST_IP_TABLE[$hostName]);

        $this->assertNull(\PhUtils\DNSUtil::getIpByHostName());

        $hostIp2 = \PhUtils\DNSUtil::getIpByHostName($hostName);

        $this->assertEquals($hostIp, $hostIp2);

    }
}
