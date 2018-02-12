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

class SliderMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'images',
            'label' => 'slider.label.slider',
            'translationDomain' => 'EnhavoSliderBundle',
            'route' => 'enhavo_slider_slide_index',
            'role' => 'ROLE_ENHAVO_SLIDER_SLIDE_INDEX'
        ]);
    }

    public function getType()
    {
        return 'slider';
    }
}