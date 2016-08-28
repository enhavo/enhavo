<?php
/**
 * CartSummaryRenderer.php
 *
 * @since 28/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Render;


use Enhavo\Bundle\ShopBundle\Calculator\OrderCompositionCalculator;

class CartSummaryRenderer extends AbstractRenderer
{
    public function render($options)
    {
        $resolvedOptions = $this->resolveOptions([
            'template' => 'EnhavoShopBundle:Cart:summary.html.twig',
        ], $options);

        $cart = $this->get('sylius.cart_provider')->getCart();

        $calculator = $this->get('enhavo_shop.calculator.order_composition_calculator');
        $orderComposition = $calculator->calculateOrder($cart);

        return $this->renderTemplate($resolvedOptions['template'], [
            'cart' => $cart,
            'total' => $orderComposition
        ]);
    }
}