<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SliderBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlideMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'slideshow',
            'label' => 'slide.label.slide',
            'translation_domain' => 'EnhavoSliderBundle',
            'route' => 'enhavo_slider_slide_index',
            'role' => 'ROLE_ENHAVO_SLIDER_SLIDE_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'slide';
    }
}
