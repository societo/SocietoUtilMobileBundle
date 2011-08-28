<?php

/**
 * SocietoUtilMobileBundle
 * Copyright (C) 2011 Kousuke Ebihara
 *
 * This program is under the EPL/GPL/LGPL triple license.
 * Please see the Resources/meta/LICENSE file that was distributed with this file.
 */

namespace Societo\Util\MobileBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SocietoUtilMobileBundle extends Bundle
{
    const FLAVOUR_ATTRIBUTE_NAME = '_flavour';
    const MOBILE_FLAVOUR_NAME = 'mobile';
    const DEFAULT_FLAVOUR_NAME = 'full';
}
