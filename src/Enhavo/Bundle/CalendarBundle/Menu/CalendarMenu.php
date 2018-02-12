<?php
/**
 * CalendarMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CalendarBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class CalendarMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'calendar-1');
        $this->setDefaultOption('label', $options, 'label.calendar');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoCalendarBundle');
        $this->setDefaultOption('route', $options, 'enhavo_calendar_appointment_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_CALENDAR_APPOINTMENT_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'calendar';
    }
}