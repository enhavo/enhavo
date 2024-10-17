<?php


namespace Enhavo\Bundle\ShopBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class VariantActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        if (!$resource instanceof ProductInterface) {
            throw new \InvalidArgumentException(sprintf('Resource need to be type of "%s"', ProductInterface::class));
        }

        $data->set('url', $this->router->generate($options['route'], array_merge($options['route_parameters'], [
            'promotionId' => $resource->getId()
        ])));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'texture',
            'target' => '_frame',
            'frame_key' => 'variants',
            'route' => 'sylius_product_variant_index',
            'translation_domain' => 'EnhavoShopBundle',
            'label' => 'product.label.variants'
        ]);
    }

    public static function getParentType(): ?string
    {
        return OpenActionType::class;
    }

    public static function getName(): ?string
    {
        return 'shop_variant';
    }
}
