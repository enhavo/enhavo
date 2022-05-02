<?php

namespace Enhavo\Bundle\ShopBundle\Component;

use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('shop_cart_summary')]
class CartSummaryComponent
{
    #[ExposeInTemplate]
    public $cart;

    #[ExposeInTemplate]
    public $immutable = true;

    public function __construct(
        private CartContextInterface $cartContext,
    )
    {}

    #[PreMount]
    public function preMount(array $data): array
    {
        return [
            'cart' => $this->cartContext->getCart()
        ];
    }
}
