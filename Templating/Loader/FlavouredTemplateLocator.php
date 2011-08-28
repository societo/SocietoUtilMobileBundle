<?php

/**
 * SocietoUtilMobileBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\MobileBundle\Templating\Loader;

use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;
use Societo\Util\MobileBundle\SocietoUtilMobileBundle;
use Symfony\Component\Templating\TemplateReferenceInterface;

class FlavouredTemplateLocator extends TemplateLocator
{
    private $flavour = null;

    public function setFlavour($flavour)
    {
        $this->flavour = $flavour;
    }

    public function locate($template, $currentPath = null, $first = true)
    {
        if (!$template instanceof TemplateReferenceInterface) {
            return parent::locate($template, $currentPath, $first);
        }

        $prefix = '';
        if (SocietoUtilMobileBundle::DEFAULT_FLAVOUR_NAME !== $this->flavour) {
            // TODO: be configurable flavoured template directory
            $prefix = $this->flavour.'/';
        }

        $originalName = $template->get('name');
        try {
            $template->set('name', $prefix.$originalName);

            return parent::locate($template, $currentPath, $first);
        } catch (\InvalidArgumentException $e) {
            if (!$prefix) {
                throw $e;
            }
            // retry to load non-flavoured template
            $template->set('name', $originalName);

            return parent::locate($template, $currentPath, $first);
        }
    }
}
