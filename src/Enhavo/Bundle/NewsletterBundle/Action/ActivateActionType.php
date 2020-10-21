<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\UpdateActionType;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivateActionType extends UpdateActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'check',
            'label' => 'subscriber.label.activate',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_pending_subscriber_activate'
        ]);
    }

    protected function getUrl(array $options, $resource = null)
    {
        if ($resource instanceof PendingSubscriber) {
            $parameters = [];
            $parameters['id'] = $resource->getId();

            $parameters = array_merge_recursive($parameters, $options['route_parameters']);

            return $this->router->generate($options['route'], $parameters);
        }

        return $this->router->generate($options['route'], $options['route_parameters']);
    }

    public function getType()
    {
        return 'newsletter_subscriber_activate';
    }

}
