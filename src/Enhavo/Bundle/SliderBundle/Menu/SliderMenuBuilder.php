<?php
/**
 * SliderMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SliderBundle\Menu;


use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class SliderMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'images');
        $this->setOption('label', $options, 'slider.label.slider');
        $this->setOption('translationDomain', $options, 'EnhavoSliderBundle');
        $this->setOption('route', $options, 'enhavo_slider_slide_index');
        $this->setOption('role', $options, 'ROLE_ADMIN_ENHAVO_SLIDER_SLIDE_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'slider';
    }
}