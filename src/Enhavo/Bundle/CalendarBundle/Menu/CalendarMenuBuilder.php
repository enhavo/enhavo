<?php
/**
 * CalendarMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CalendarBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class CalendarMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'calendar-1');
        $this->setOption('label', $options, 'label.calendar');
        $this->setOption('translationDomain', $options, 'EnhavoCalendarBundle');
        $this->setOption('route', $options, 'enhavo_calendar_appointment_index');
        $this->setOption('role', $options, 'ROLE_ADMIN_ENHAVO_CALENDAR_APPOINTMENT_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'calendar';
    }
}