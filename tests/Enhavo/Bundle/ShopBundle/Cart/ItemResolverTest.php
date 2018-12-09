<?php

namespace Enhavo\Bundle\ShopBundle\Cart;

use Enhavo\Bundle\ShopBundle\Cart\ItemResolver;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Modifier\OrderItemQuantityModifier;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use PHPUnit\Framework\TestCase;

class ItemResolverTest extends TestCase
{
    public function testResolve()
    {
        $item = $this->getMockBuilder(OrderItemInterface::class)->getMock();
        $item->expects(static::once())->method('setProduct');
        $item->expects(static::once())->method('setUnitPrice');
        $item->expects(static::once())->method('setName');

        $modifier = $this->getMockBuilder(OrderItemQuantityModifier::class)
            ->disableOriginalConstructor()
            ->getMock();
        $modifier->expects(static::once())->method('modify');

        $product = $this->getMockBuilder(ProductInterface::class)->getMock();

        $productRepository = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        $productRepository->method('find')->willReturn($product);

        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->method('get')->willReturn('1');

        $itemResolver = new ItemResolver($productRepository, $modifier);
        $itemResolver->resolve($item, $request);
    }
}