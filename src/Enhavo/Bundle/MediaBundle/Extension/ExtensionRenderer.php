<?php

namespace Enhavo\Bundle\MediaBundle\Extension;

use Enhavo\Bundle\AppBundle\Button\ExtensionInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;

class ExtensionRenderer extends AbstractRenderer
{
    public function render($type, $options, $resource)
    {
        /** @var $type ExtensionInterface */
        $type = $this->getType($type);
        return $type->render($options, $resource);
    }

    public function getName()
    {
        return 'extension_render';
    }
}