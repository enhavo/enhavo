<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 14:21
 */

namespace Enhavo\Bundle\AppBundle\Toolbar\Widget;

use Enhavo\Bundle\AppBundle\Toolbar\AbstractToolbarWidgetType;

class IconToolbarWidgetType extends AbstractToolbarWidgetType
{
    public function createViewData(array $options)
    {
        return parent::createViewData($options);
    }

    public function getType()
    {
        return 'icon';
    }
}
