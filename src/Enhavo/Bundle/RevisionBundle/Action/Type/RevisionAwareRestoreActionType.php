<?php

namespace Enhavo\Bundle\RevisionBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class RevisionAwareRestoreActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly CsrfTokenManagerInterface $tokenManager,
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data->set('reload', $options['reload']);
        $data->set('token', $this->tokenManager->getToken('resource_revision')->getValue());
        $data->set('url', $this->router->generate($options['route'], [
            'id' => $resource->getId()
        ]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'restore.action.restore',
            'reload' => false,
            'translation_domain' => 'EnhavoRevisionBundle',
            'icon' => 'replay',
            'model' => 'RevisionAwareRestoreAction',
            'confirm_label_ok' => 'restore.action.restore',
            'confirm_label_cancel' => 'restore.action.cancel',
        ]);

        $resolver->setRequired(['route']);
    }
}
