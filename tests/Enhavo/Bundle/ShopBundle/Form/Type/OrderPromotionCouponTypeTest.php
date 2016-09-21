<?php
/**
 * OrderPromotionCouponTest.php
 *
 * @since 19/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Tests\Form\Type;

use Enhavo\Bundle\ShopBundle\Entity\Order;
use Enhavo\Bundle\ShopBundle\Form\Type\OrderPromotionCouponType;
use Sylius\Bundle\PromotionBundle\Form\DataTransformer\CouponToCodeTransformer;
use Sylius\Bundle\PromotionBundle\Form\Type\CouponToCodeType;
use Symfony\Component\Form\Test\TypeTestCase;
use Sylius\Component\Core\Model\Coupon;
use Symfony\Component\Form\PreloadedExtension;

class OrderPromotionCouponTest extends TypeTestCase
{
    public function testSubmitData()
    {
        $order = new Order();
        $formType = new OrderPromotionCouponType('Enhavo\Bundle\ShopBundle\Entity\Order');
        $form = $this->factory->create($formType, $order);
        $form->submit([
            'promotionCoupon' => '12345'
        ]);

        $this->assertTrue($form->isValid());
        $this->assertInstanceOf('Sylius\Component\Core\Model\Coupon', $order->getPromotionCoupon());
    }

    protected function getExtensions()
    {
        $coupon = new Coupon();
        $coupon->setCode('12345');

        $objectRepository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $objectRepository->method('findOneBy')->willReturn($coupon);

        $eventDispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock();

        $transformer = new CouponToCodeTransformer($objectRepository, $eventDispatcher);
        $type = new CouponToCodeType($transformer);
        return [
            new PreloadedExtension([$type], [])
        ];
    }
}