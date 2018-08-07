<?php

namespace Enhavo\Bundle\NewsletterBundle\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class ViewButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        if(!array_key_exists('route', $options)) {
            throw new \InvalidArgumentException('Route option is not defined for view button');
        }

        return $this->renderTemplate('EnhavoNewsletterBundle:Button:view.html.twig', [
            'type' => $this->getType(),
            'icon' => 'eye',
            'display' => true,
            'role' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_VIEW',
            'label' => 'newsletter.action.view',
            'translationDomain' => 'EnhavoNewsletterBundle',
            'route' => $options['route'],
            'resource' => $resource
        ]);
    }

    public function getType()
    {
        return 'newsletter_view';
    }
}
