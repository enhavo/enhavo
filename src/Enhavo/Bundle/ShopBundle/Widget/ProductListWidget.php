<?php
/**
 * ProductListWidget.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Widget;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListWidget extends AbstractWidgetType
{
    public function __construct(
        private EntityRepository $repository
    )
    {}

    public function getType()
    {
        return 'shop_product_list';
    }

    public function createViewData(array $options, $resource = null)
    {
        $products = $this->repository->findAll();

        return [
            'products' => $products
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'template' => 'theme/widget/product-list.html.twig',
        ]);
    }
}
