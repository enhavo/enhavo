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

class SliderMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'slideshow',
            'label' => 'slider.label.slider',
            'translation_domain' => 'EnhavoSliderBundle',
            'route' => 'enhavo_slider_slider_index',
            'role' => 'ROLE_ENHAVO_SLIDER_SLIDER_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'slider';
    }
}
