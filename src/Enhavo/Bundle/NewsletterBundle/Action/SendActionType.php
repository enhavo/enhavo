<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data = array_merge($data, [
            'resourceId' => $resource->getId(),
        ]);
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'newsletter-send',
            'label' => 'newsletter.action.send.label',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'icon' => 'send',
        ]);
    }

    public function getType()
    {
        return 'newsletter_send';
    }
}
