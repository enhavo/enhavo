<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Events;

class ImportEvents
{
    public const PRE_IMPORT = 'enhavo_calendar.event.pre_import';
    public const PRE_CREATE = 'enhavo_calendar.event.pre_create';
}
