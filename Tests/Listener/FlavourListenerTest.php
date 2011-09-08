<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace Societo\Util\MobileBundle\Tests\Listener;

use Societo\Util\MobileBundle\Listener\FlavourListener as Listener;
use Symfony\Component\HttpFoundation\ParameterBag;

class FlavourListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsMobileFlavoured()
    {
        $listener = new Listener(null, $this->getMobileDetectorMock());
        $this->assertFalse($listener->isMobileFlavoured($this->getRequestMock()));
        $this->assertFalse($listener->isMobileFlavoured($this->getRequestMock('/m')));
        $this->assertFalse($listener->isMobileFlavoured($this->getRequestMock('', 'm.example.com')));

        $_listener = clone $listener;
        $_listener->setMobileFlavourPath('/m');
        $this->assertFalse($_listener->isMobileFlavoured($this->getRequestMock()));
        $this->assertTrue($_listener->isMobileFlavoured($this->getRequestMock('/m')));

        $_listener = clone $listener;
        $_listener->setMobileFlavourHost('m.example.com');
        $this->assertFalse($_listener->isMobileFlavoured($this->getRequestMock()));
        $this->assertTrue($_listener->isMobileFlavoured($this->getRequestMock('', 'm.example.com')));
    }

    public function testGetFlavourFromRequest()
    {
        $listener = new Listener(null, $this->getMobileDetectorMock(false));
        $this->assertNull($listener->getFlavourFromRequest($this->getRequestMock()));

        $this->assertEquals('mobile', $listener->getFlavourFromRequest($this->getRequestMock('', '', array('flavour' => 'mobile'))));
        $this->assertEquals('_dummy_', $listener->getFlavourFromRequest($this->getRequestMock('', '', array('flavour' => '_dummy_'))));
        $this->assertEquals('mobile', $listener->getFlavourFromRequest($this->getRequestMock('', '', array(), array('flavour' => 'mobile'))));
        $this->assertEquals('_dummy_', $listener->getFlavourFromRequest($this->getRequestMock('', '', array(), array('flavour' => '_dummy_'))));
        $this->assertEquals('query', $listener->getFlavourFromRequest($this->getRequestMock('', '', array('flavour' => 'cookie'), array('flavour' => 'query'))));

        $listener = new Listener(null, $this->getMobileDetectorMock(true));
        $this->assertEquals('mobile', $listener->getFlavourFromRequest($this->getRequestMock()));
        $this->assertEquals('query', $listener->getFlavourFromRequest($this->getRequestMock('', '', array('flavour' => 'cookie'), array('flavour' => 'query'))));
        $this->assertEquals('query', $listener->getFlavourFromRequest($this->getRequestMock('', '', array(), array('flavour' => 'query'))));
    }

    public function getRequestMock($path = '', $host = '', $cookie = array(), $query = array())
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');

        if ($path) {
            $request->expects($this->any())
                ->method('getBasePath')
                ->will($this->returnValue($path));
        }

        if ($host) {
            $request->expects($this->any())
                ->method('getHost')
                ->will($this->returnValue($host));
        }

        $request->cookies = new ParameterBag($cookie);
        $request->query = new ParameterBag($query);
        $request->headers = new ParameterBag(array());

        return $request;
    }

    public function getMobileDetectorMock($isMobile = false)
    {
        $mock = $this->getMock('Societo\Util\MobileBundle\MobileDetector', array(), array($this->getRequestMock()));
        $mock->expects($this->any())
            ->method('isMobile')
            ->will($this->returnValue($isMobile));

        return $mock;
    }
}
