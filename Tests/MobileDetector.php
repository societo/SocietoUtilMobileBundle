<?php

/**
 * This file is applied CC0 <http://creativecommons.org/publicdomain/zero/1.0/>
 */

namespace Societo\Util\MobileBundle\Tests\Listener;

use Societo\Util\MobileBundle\MobileDetector as Detector;
use Symfony\Component\HttpFoundation\ParameterBag;

class MobileDetector extends \PHPUnit_Framework_TestCase
{
    public function testIsMobile()
    {
        // most of the fllowing is from http://www.useragentstring.com/
        $this->assertFalse($this->executeDetector('Mozilla/4.0 (compatible; MSIE 6.0; MSN 2.5; Windows 98)'));
        $this->assertFalse($this->executeDetector('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)'));

        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Windows NT 5.0; rv:5.0) Gecko/20100101 Firefox/5.0'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Windows 98; U; en; rv:1.8.0) Gecko/20060728 Firefox/1.5.0'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0a2) Gecko/20110816 Firefox/7.0a2'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (X11; Linux x86_64) Gecko Firefox/5.0'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (X11; FreeBSD amd64; rv:5.0) Gecko/20100101 Firefox/5.0'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Macintosh; PPC MacOS X; rv:5.0) Gecko/20110615 Firefox/5.0'));

        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Windows; U; Windows NT 6.1; tr-TR) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; de-at) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_8; zh-cn) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27'));

        $this->assertFalse($this->executeDetector('Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.1 (KHTML, like Gecko) Ubuntu/11.04 Chromium/14.0.825.0 Chrome/14.0.825.0 Safari/535.1'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.815.0 Safari/535.1'));
        $this->assertFalse($this->executeDetector('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_7) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.813.0 Safari/535.1'));

        $this->assertFalse($this->executeDetector('Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00'));
        $this->assertFalse($this->executeDetector('Opera/9.80 (X11; Linux x86_64; U; fr) Presto/2.9.168 Version/11.50'));
        $this->assertFalse($this->executeDetector('Opera/9.80 (Windows 98; U; de) Presto/2.6.30 Version/10.61'));
        $this->assertFalse($this->executeDetector('Opera/9.80 (Macintosh; Intel Mac OS X; U; nl) Presto/2.6.30 Version/10.61'));

        $this->assertTrue($this->executeDetector('Mozilla/5.0 (Linux; U; Android 2.3.4; fr-fr; HTC Desire Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'));
        $this->assertTrue($this->executeDetector('Mozilla/5.0 (BlackBerry; U; BlackBerry 9850; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.115 Mobile Safari/534.11+'));
        $this->assertTrue($this->executeDetector('Mozilla/4.0 (compatible; MSIE 6.0; Windows 95; PalmSource; Blazer 3.0) 16; 160x160'));
        $this->assertTrue($this->executeDetector('SamsungI8910/SymbianOS/9.1 Series60/3.0'));
        $this->assertTrue($this->executeDetector('Mozilla/5.0 (SymbianOS/9.4; Series60/5.0 NokiaC6-00/20.0.042; Profile/MIDP-2.1 Configuration/CLDC-1.1; zh-hk) AppleWebKit/525 (KHTML, like Gecko) BrowserNG/7.2.6.9 3gpp-gba'));
        $this->assertTrue($this->executeDetector('NokiaN97/21.1.107 (SymbianOS/9.4; Series60/5.0 Mozilla/5.0; Profile/MIDP-2.1 Configuration/CLDC-1.1) AppleWebkit/525 (KHTML, like Gecko) BrowserNG/7.1.4'));
        $this->assertTrue($this->executeDetector('MOT-L7/NA.ACR_RB MIB/2.2.1 Profile/MIDP-2.0 Configuration/CLDC-1.1'));
        $this->assertTrue($this->executeDetector('Mozilla/5.0 (X11; U; Linux arm7tdmi; rv:1.8.1.11) Gecko/20071130 Minimo/0.025'));
        $this->assertTrue($this->executeDetector('SAMSUNG-C5212/C5212XDIK1 NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1'));
        $this->assertTrue($this->executeDetector('Mozilla/4.0 (compatible; Linux 2.6.22) NetFront/3.4 Kindle/2.5 (screen 824x1200;rotate)'));
        $this->assertTrue($this->executeDetector('SonyEricssonK800c/R8BF Browser/NetFront/3.3 Profile/MIDP-2.0 Configuration/CLDC-1.1'));
        $this->assertTrue($this->executeDetector('Opera/9.80 (J2ME/MIDP; Opera Mini/9.80 (S60; SymbOS; Opera Mobi/23.348; U; en) Presto/2.5.25 Version/10.54'));
        $this->assertTrue($this->executeDetector('Opera/9.80 (J2ME/MIDP; Opera Mini/9 (Compatible; MSIE:9.0; iPhone; BlackBerry9700; AppleWebKit/24.746; U; en) Presto/2.5.25 Version/10.54'));
        $this->assertTrue($this->executeDetector('Opera/9.80 (Windows Mobile; Opera Mini/5.1.21594/22.387; U; ru) Presto/2.5.25 Version/10.54'));
        $this->assertTrue($this->executeDetector('DoCoMo/2.0 P07A3(c500;TB;W24H15)'));
        $this->assertTrue($this->executeDetector('SoftBank/1.0/008SH/SHJ001 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.'));
        $this->assertTrue($this->executeDetector('Vodafone/1.0/V905SH/SHJ001 Java/VF-Java/1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1'));
        $this->assertTrue($this->executeDetector('KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0'));
        $this->assertTrue($this->executeDetector('KDDI-XX99 UP.Browser/6.2_7.2.7.1.K.8.400 (GUI) MMP/2.0'));

        $this->assertTrue($this->executeDetector('*******', 'text/html,application/vnd.wap.xhtml+xml,application/xhtml+xml'));
    }

    public function executeDetector($ua = '', $accept = '')
    {
        $request = $this->getRequestMock($ua, $accept);
        $detector = new Detector($request);

        return $detector->isMobile();
    }

    public function getRequestMock($ua = '', $accept = '')
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');

        $paraemters = array();
        if ($ua) {
            $paraemters['HTTP_USER_AGENT'] = $ua;
        }

        if ($accept) {
            $paraemters['HTTP_ACCEPT'] = $accept;
        }

        $request->headers = new ParameterBag($paraemters);

        return $request;
    }
}
