<?php

/**
 * SocietoUtilMobileBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\MobileBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Symfony\Component\HttpFoundation\Cookie;
use Societo\Util\MobileBundle\SocietoUtilMobileBundle;
use Societo\Util\MobileBundle\MobileDetector;

class FlavourListener
{
    private $templateLocator;

    private $needToSetFlavourCookie = false;
    private $mobileDetector;

    private $mobileFlavourPath = '';
    private $mobileFlavourHost = '';
    private $flavorParameterName = 'flavour';

    public function __construct($templateLocator, $mobileDetector = null)
    {
        $this->templateLocator = $templateLocator;
        $this->mobileDetector = $mobileDetector;
    }

    public function setMobileFlavourPath($path)
    {
        $this->mobileFlavourPath = $path;
    }

    public function setMobileFlavourHost($host)
    {
        $this->mobileFlavourHost = $host;
    }

    public function setFlavorParameterName($name)
    {
        $this->flavorParameterName = $name;
    }

    public function isMobileFlavoured($request)
    {
        if ($this->mobileFlavourPath && $request->getBasePath() === $this->mobileFlavourPath) {
            return true;
        }

        if ($this->mobileFlavourHost && $request->getHost() === $this->mobileFlavourHost) {
            return true;
        }

        return false;
    }

    public function getFlavourFromRequest($request)
    {
        $flavour = null;

        if ($request->query->has($this->flavorParameterName)) {
            $flavour = $request->query->get($this->flavorParameterName);

            $this->needToSetFlavourCookie = true;
        } elseif ($request->cookies->has($this->flavorParameterName)) {
            $flavour = $request->cookies->get($this->flavorParameterName);
        } elseif ($this->mobileDetector->isMobile()) {
            $flavour = SocietoUtilMobileBundle::MOBILE_FLAVOUR_NAME;
        }

        return $flavour;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $this->mobileDetector = new MobileDetector($request);

        // TODO: support available flavours injection
        $available = array(SocietoUtilMobileBundle::DEFAULT_FLAVOUR_NAME, SocietoUtilMobileBundle::MOBILE_FLAVOUR_NAME);

        $flavour = null;

        if ($this->isMobileFlavoured($request)) {
            $flavour = SocietoUtilMobileBundle::MOBILE_FLAVOUR_NAME;
        } else {
            $flavour = $this->getFlavourFromRequest($request);
        }

        if (!$flavour || !in_array($flavour, $available)) {
            $flavour = SocietoUtilMobileBundle::DEFAULT_FLAVOUR_NAME;
        }

        $request->attributes->set(SocietoUtilMobileBundle::FLAVOUR_ATTRIBUTE_NAME, $flavour);
        $this->templateLocator->setFlavour($flavour);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$this->needToSetFlavourCookie) {
            return null;
        }

        $cookie = new Cookie($this->flavorParameterName, $event->getRequest()->attributes->get(SocietoUtilMobileBundle::FLAVOUR_ATTRIBUTE_NAME), strtotime('+ 10 years'));
        $event->getResponse()->headers->setCookie($cookie);
    }
}
