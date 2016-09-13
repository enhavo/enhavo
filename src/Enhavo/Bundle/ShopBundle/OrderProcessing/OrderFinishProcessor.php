<?php
/**
 * OrderConfirmProcessor.php
 *
 * @since 04/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;


use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Sylius\Component\Cart\Provider\CartProviderInterface;

class OrderFinishProcessor implements ProcessorInterface
{
    /**
     * @var CartProviderInterface
     */
    protected $cartProvider;

    public function __construct(CartProviderInterface $cartProvider)
    {
        $this->cartProvider = $cartProvider;
    }

    public function process(OrderInterface $order)
    {
        $this->cartProvider->abandonCart();
    }
}