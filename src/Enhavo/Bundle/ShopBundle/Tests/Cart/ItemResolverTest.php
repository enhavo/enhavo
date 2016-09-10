<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 9/9/16
 * Time: 11:55 AM
 */

namespace Enhavo\Bundle\ShopBundle\Tests\Cart;

use Enhavo\Bundle\ShopBundle\Cart\ItemResolver;

class ItemResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolve()
    {
        $item = $this->getMockBuilder('Enhavo\Bundle\ShopBundle\Model\OrderItemInterface')->getMock();
        $item->expects($this->once())->method('setProduct');
        $item->expects($this->once())->method('setUnitPrice');

        $modifier = $this->getMockBuilder('Enhavo\Bundle\ShopBundle\Modifier\OrderItemQuantityModifier')
            ->disableOriginalConstructor()
            ->getMock();
        $modifier->expects($this->once())->method('modify');

        $product = $this->getMockBuilder('Enhavo\Bundle\ShopBundle\Model\ProductInterface')->getMock();

        $productRepository = $this->getMockBuilder('Sylius\Component\Resource\Repository\RepositoryInterface')->getMock();
        $productRepository->method('find')->willReturn($product);

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->method('get')->willReturn('1');

        $itemResolver = new ItemResolver($productRepository, $modifier);
        $itemResolver->resolve($item, $request);
    }
}