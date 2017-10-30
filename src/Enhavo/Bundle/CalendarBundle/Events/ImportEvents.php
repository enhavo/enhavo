<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.17
 * Time: 11:34
 */

namespace Enhavo\Bundle\CalendarBundle\Events;


class ImportEvents
{
    const PRE_IMPORT = 'enhavo_calendar.event.pre_import';
    const PRE_CREATE = 'enhavo_calendar.event.pre_create';
}