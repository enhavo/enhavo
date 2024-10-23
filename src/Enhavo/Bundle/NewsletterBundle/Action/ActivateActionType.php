<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Action\Type\UpdateActionType;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class ActivateActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data->set('url', $this->getUrl($options, $resource));
    }

    private function getUrl(array $options, $resource = null)
    {
        if ($resource instanceof PendingSubscriber) {
            $parameters = [];
            $parameters['id'] = $resource->getId();

            $parameters = array_merge_recursive($parameters, $options['route_parameters']);

            return $this->router->generate($options['route'], $parameters);
        }

        return $this->router->generate($options['route'], $options['route_parameters']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'check',
            'label' => 'subscriber.label.activate',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_pending_subscriber_activate'
        ]);
    }

    public static function getParentType(): ?string
    {
        return UpdateActionType::class;
    }


    public static function getName(): ?string
    {
        return 'newsletter_subscriber_activate';
    }
}
