<?php

namespace Enhavo\Bundle\ShopBundle\View;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\Type\ApiViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CartSummaryViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ResourceManager $resourceManager,
        private ResourceFormFactoryInterface $resourceFormFactory,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private RequestStack $requestStack,
        private CartContextInterface $cartContext,
        private NormalizerInterface $normalizer,
        private VueForm $vueForm,
    ) {}

    public static function getName(): ?string
    {
        return 'shop_cart_summary';
    }

    public static function getParentType(): ?string
    {
        return ApiViewType::class;
    }

    public function createViewData($options, ViewData $data)
    {
        $configuration = $this->getRequestConfiguration();
        $cart = $this->cartContext->getCart();

        $form = $this->resourceFormFactory->create($configuration, $cart);

        $data['cart'] = $this->normalizer->normalize($cart, null, [
            'groups' => ['cart']
        ]);

        $data['form'] = $this->vueForm->createData($form->createView());
    }

    private function getRequestConfiguration(): RequestConfiguration
    {
        $request = $this->requestStack->getMainRequest();
        return $this->requestConfigurationFactory->create($this->resourceManager->getMetadata('sylius', 'order'), $request);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'theme/shop/cart/summary.html.twig',
        ]);

        $resolver->setRequired('request_configuration');
    }
}
