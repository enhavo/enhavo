<?php
/**
 * CalendarMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CalendarBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'event',
            'label' => 'label.calendar',
            'translation_domain' => 'EnhavoCalendarBundle',
            'route' => 'enhavo_calendar_admin_appointment_index',
            'permission' => 'ROLE_ENHAVO_CALENDAR_APPOINTMENT_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'calendar';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
