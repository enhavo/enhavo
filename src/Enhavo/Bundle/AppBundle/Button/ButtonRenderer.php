<?php

namespace Enhavo\Bundle\AppBundle\Button;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ButtonRenderer extends AbstractRenderer
{
    use ContainerAwareTrait;

    public function render($type, $options, $resource)
    {
        /** @var $type ButtonInterface */
        $type = $this->getType($type);
        if(isset($options['role']) && !$this->container->get('security.authorization_checker')->isGranted($options['role'])) {
            return '';
        }
        if(isset($options['display']) && !$options['display']) {
            return '';
        }
        return $type->render($options, $resource);
    }

    public function getName()
    {
        return 'button_render';
    }
}