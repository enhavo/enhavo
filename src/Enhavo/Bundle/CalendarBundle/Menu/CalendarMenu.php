<?php
/**
 * CalendarMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CalendarBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'event',
            'label' => 'label.calendar',
            'translation_domain' => 'EnhavoCalendarBundle',
            'route' => 'enhavo_calendar_appointment_index',
            'role' => 'ROLE_ENHAVO_CALENDAR_APPOINTMENT_INDEX'
        ]);
    }

    public function getType()
    {
        return 'calendar';
    }
}