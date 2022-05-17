<?php

namespace Enhavo\Bundle\ShopBundle\View;

use Enhavo\Bundle\AppBundle\Exception\ResourceStopException;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\Type\ApiViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactory;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $metadata = $this->getMetadata($options);

        $form = $this->resourceFormFactory->create($configuration, $this->resource);

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                try {
                    $this->resourceManager->update($this->resource, [
                        'event_name' => $configuration->getEvent() ?? ResourceActions::UPDATE,
                        'application_name' => $metadata->getApplicationName(),
                        'entity_name' => $metadata->getName(),
                        'transition' => $configuration->getStateMachineTransition(),
                        'graph' => $configuration->getStateMachineGraph(),
                    ]);
                } catch (ResourceStopException $e) {
                    return $e->getResponse();
                }

                if ($configuration->getRedirectRoute(null)) {
                    return new RedirectResponse($this->router->generate($configuration->getRedirectRoute(null), $configuration->getRedirectParameters()));
                }
                return new RedirectResponse($request->get('_route'));
            }
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
