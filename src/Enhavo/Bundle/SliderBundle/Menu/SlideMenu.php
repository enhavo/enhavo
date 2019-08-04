<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SliderBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlideMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'slideshow',
            'label' => 'slider.label.slider',
            'translation_domain' => 'EnhavoSliderBundle',
            'route' => 'enhavo_slider_slide_index',
            'role' => 'ROLE_ENHAVO_SLIDER_SLIDE_INDEX'
        ]);
    }

    public function getType()
    {
        return 'slide';
    }
}
