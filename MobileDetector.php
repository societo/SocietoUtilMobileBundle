<?php

/**
 * SocietoUtilMobileBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\MobileBundle;

class MobileDetector
{
    private static $cache = array();

    private $request;

    /**
     * This list is from django-mobile 0.2.1 (New BSD License) by Gregor Müllegger
     * http://pypi.python.org/pypi/django-mobile/
     */
    private $mobilePrefixList = array(
        'w3c', 'acs-', 'alav', 'alca', 'amoi', 'audi',
        'avan', 'benq', 'bird', 'blac', 'blaz', 'brew',
        'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric',
        'hipt', 'inno', 'ipaq', 'java', 'jigs', 'kddi',
        'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
        'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi',
        'mot-', 'moto', 'mwbp', 'nec-', 'newt', 'noki',
        'xda', 'palm', 'pana', 'pant', 'phil', 'play',
        'port', 'prox', 'qwap', 'sage', 'sams', 'sany',
        'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
        'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-',
        'symb', 't-mo', 'teli', 'tim-', 'tosh', 'tsm-',
        'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa',
        'wapi', 'wapp', 'wapr', 'webc', 'winw', 'winw',
        'xda-',
    );

    /**
     * This list is from django-mobile 0.2.1 (New BSD License) by Gregor Müllegger
     * http://pypi.python.org/pypi/django-mobile/
     */
    private $mobileLongRegexp = '/(?:up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|windows ce|pda|mobile|mini|palm|netfront)/i';

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function isMobile()
    {
        $result = false;

        $userAgent = $this->request->headers->get('User-Agent', '');

        if (isset(self::$cache[$userAgent])) {
            return self::$cache[$userAgent];
        }

        if (false !== strpos($this->request->headers->get('Accept'), 'application/vnd.wap.xhtml+xml')) {
            $result = true;

        } else {
            $mobileRegexp = sprintf('/^(?:%s)/i', implode('|', $this->mobilePrefixList));
            if (preg_match($mobileRegexp, $userAgent)) {
                $result = true;
            } elseif (preg_match($this->mobileLongRegexp, $userAgent)) {
                $result = true;
            }
        }

        self::$cache[$userAgent] = $result;

        return $result;
    }
}
