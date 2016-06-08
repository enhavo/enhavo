<?php

namespace Enhavo\Bundle\NewsletterBundle\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class SendButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        return $this->renderTemplate('EnhavoAppBundle:Button:default.html.twig', [
            'type' => $this->getType(),
            'route' => 'enhavo_newsletter_newsletter_send',
            'icon' => 'eye',
            'display' => true,
            'role' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_SEND',
            'label' => 'newsletter.action.sendAndSave',
            'translationDomain' => 'EnhavoNewsletterBundle'
        ]);
    }

    public function getType()
    {
        return 'send';
    }
}