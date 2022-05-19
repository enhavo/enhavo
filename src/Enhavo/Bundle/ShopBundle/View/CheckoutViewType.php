<?php

namespace Enhavo\Bundle\ShopBundle\View;

use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\Type\ApiViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactory;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class CheckoutViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    private OrderInterface $resource;

    public function __construct(
        private CartContextInterface $cartContext,
        private ResourceFormFactory $resourceFormFactory,
        private ResourceManager $resourceManager,
        private RouterInterface $router,
        private EventDispatcherInterface $eventDispatcher,
    )
    {}

    public static function getName(): ?string
    {
        return 'shop_checkout';
    }

    public static function getParentType(): ?string
    {
        return ApiViewType::class;
    }

    public function init($options)
    {
        $this->resource = $this->cartContext->getCart();
    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $this->getRequestConfiguration($options);

        $form = $this->resourceFormFactory->create($configuration, $this->resource);

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {

                $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $this->resource);
                if ($event->isStopped()) {
                    if ($event->getResponse()) {
                        return $event->getResponse();
                    }
                    throw new HttpException($event->getErrorCode(), $event->getMessage());
                }

                $this->resourceManager->update($this->resource, $configuration->getStateMachineTransition(), $configuration->getStateMachineGraph());

                $event = $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $this->resource);
                if ($event->isStopped()) {
                    if ($event->getResponse()) {
                        return $event->getResponse();
                    }
                    throw new HttpException($event->getErrorCode(), $event->getMessage());
                }

                if ($configuration->getRedirectRoute(null)) {
                    return new RedirectResponse($this->router->generate($configuration->getRedirectRoute(null), $configuration->getRedirectParameters()));
                }
                return new RedirectResponse($request->get('_route'));
            }
        }

        $event = $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::UPDATE, $configuration, $this->resource);
        if ($event->isStopped()) {
            if ($event->getResponse()) {
                return $event->getResponse();
            }
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }

        $templateData['form'] = $form->createView();

        return null;
    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        $templateData['resource'] = $this->resource;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'theme/shop/checkout/checkout.html.twig'
        ]);

        $resolver->setRequired([
            'request_configuration'
        ]);
    }
}
