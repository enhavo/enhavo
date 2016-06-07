<?php

namespace Enhavo\Bundle\AppBundle\Block;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;

class BlockRenderer extends AbstractRenderer
{
    public function render($type, $options)
    {
        /** @var $type BlockInterface */
        $type = $this->getType($type);
        return $type->render($options);
    }

    public function getName()
    {
        return 'block_render';
    }
}