<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 6/6/16
 * Time: 10:06 AM
 */
namespace Enhavo\Bundle\ShopBundle\Cart;

use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Sylius\Component\Cart\Model\CartItemInterface;
use Sylius\Component\Cart\Resolver\ItemResolverInterface;
use Sylius\Component\Cart\Resolver\ItemResolvingException;
use Doctrine\ORM\EntityManager;

class ItemResolver implements ItemResolverInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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

        $item->setUnitPrice($product->getPrice());
        /** @var $item OrderItem */
        $item->setProduct($product);

        for($i = 0; $i < $quantity; $i++) {
            $item->increaseQuantity();
        }

        // Everything went fine, return the item.
        return $item;
    }

    private function getProductRepository()
    {
        return $this->entityManager->getRepository('EnhavoShopBundle:Product');
    }
}