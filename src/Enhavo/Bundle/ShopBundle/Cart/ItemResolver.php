<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 6/6/16
 * Time: 10:06 AM
 */
namespace Enhavo\Bundle\ShopBundle\Cart;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Enhavo\Bundle\ShopBundle\Modifier\OrderItemQuantityModifier;
use Sylius\Component\Cart\Model\CartItemInterface;
use Sylius\Component\Cart\Resolver\ItemResolverInterface;
use Sylius\Component\Cart\Resolver\ItemResolvingException;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;

class ItemResolver implements ItemResolverInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var OrderItemQuantityModifier
     */
    protected $modifier;


    public function __construct(EntityManagerInterface $entityManager, OrderItemQuantityModifier $modifier)
    {
        $this->entityManager = $entityManager;
        $this->modifier = $modifier;
    }

    public function resolve(CartItemInterface $item, $request)
    {
        $productId = $request->get('product');
        $quantity = intval($request->get('quantity'));

        if($quantity < 1) {
            throw new ItemResolvingException('Quantity must be 1 or higher');
        }

        // If no product id given, or product not found, we throw exception with nice message.
        if (!$productId || !$product = $this->getProductRepository()->find($productId)) {
            throw new ItemResolvingException('Requested product was not found');
        }
        /** @var $product ProductInterface */
        $item->setUnitPrice($product->getPrice());
        /** @var $item OrderItem */
        $item->setProduct($product);

        $this->modifier->modify($item, $quantity);

        // Everything went fine, return the item.
        return $item;
    }

    private function getProductRepository()
    {
        return $this->entityManager->getRepository('EnhavoShopBundle:Product');
    }
}