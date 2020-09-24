<?php

/*
 * This file is part of the Serendipity HQ Console Styles Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Bundle\ConsoleStyles\Console\Style;

use PackageVersions\Versions;
use SerendipityHQ\Bundle\ConsoleStyles\Console\Style\SF4\SerendipityHQStyleSF4;

if (false !== \strpos(Versions::getVersion('symfony/console'), 'v5')) {
    class SerendipityHQStyle extends SerendipityHQStyleSF5
    {
    }
} else {
    class SerendipityHQStyle extends SerendipityHQStyleSF4
    {
    }
}
