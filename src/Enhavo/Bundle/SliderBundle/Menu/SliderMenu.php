<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SliderBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;

class SliderMenu extends BaseMenu
{
    public function render(array $options)
    {
        $this->setDefaultOption('icon', $options, 'images');
        $this->setDefaultOption('label', $options, 'slider.label.slider');
        $this->setDefaultOption('translationDomain', $options, 'EnhavoSliderBundle');
        $this->setDefaultOption('route', $options, 'enhavo_slider_slide_index');
        $this->setDefaultOption('role', $options, 'ROLE_ENHAVO_SLIDER_SLIDE_INDEX');

        return parent::render($options);
    }

    public function getType()
    {
        return 'slider';
    }
}