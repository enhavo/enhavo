<?php

namespace Enhavo\Bundle\UserBundle\View\Type;

use Enhavo\Bundle\AppBundle\Grid\GridManager;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use ResourceUpdateEndpointType;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordEndpointTypeResource extends ResourceUpdateEndpointType
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        array $formThemes,
        ActionManager $actionManager,
        RequestStack $requestStack,
        ViewUtil $util,
        RouterInterface $router,
        TranslatorInterface $translator,
        ResourceManager $resourceManager,
        GridManager $gridManager,
        ResourceFormFactoryInterface $resourceFormFactory,
        NormalizerInterface $normalizer,
        EventDispatcherInterface $eventDispatcher,
        SingleResourceProviderInterface $singleResourceProvider,
        TokenStorageInterface $tokenStorage,
    )
    {
        parent::__construct(
            $formThemes,
            $actionManager,
            $requestStack,
            $util,
            $router,
            $translator,
            $resourceManager,
            $gridManager,
            $resourceFormFactory,
            $normalizer,
            $eventDispatcher,
            $singleResourceProvider
        );
        $this->tokenStorage = $tokenStorage;
    }

    public function createResource($options) : ResourceInterface
    {
        $resource = $this->tokenStorage->getToken()->getUser();
        if ($resource === null) {
            throw new NotFoundHttpException();
        }
        return $resource;
    }

    protected function createSecondaryActions($options): array
    {
        return [];
    }

    public static function getName(): ?string
    {
        return 'change_password';
    }
}
