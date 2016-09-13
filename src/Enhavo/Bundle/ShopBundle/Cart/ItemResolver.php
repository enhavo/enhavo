<?php

namespace Enhavo\Bundle\ShopBundle\Cart;

use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Enhavo\Bundle\ShopBundle\Modifier\OrderItemQuantityModifier;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Sylius\Component\Cart\Model\CartItemInterface;
use Sylius\Component\Cart\Resolver\ItemResolverInterface;
use Sylius\Component\Cart\Resolver\ItemResolvingException;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ItemResolver implements ItemResolverInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $productRepository;

    /**
     * @var OrderItemQuantityModifier
     */
    protected $modifier;

    /**
     * ItemResolver constructor.
     *
     * @param RepositoryInterface $productRepository
     * @param OrderItemQuantityModifier $modifier
     */
    public function __construct(RepositoryInterface $productRepository, OrderItemQuantityModifier $modifier)
    {
        $this->productRepository = $productRepository;
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
        if (!$productId || !$product = $this->productRepository->find($productId)) {
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
}