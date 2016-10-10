<?php
/**
 * CartSummaryTrait.php
 *
 * @since 09/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

trait CartSummaryTrait
{
    protected function getCartSummaryRoute()
    {
        return 'enhavo_shop_theme_cart_summary';
    }

    protected function redirectToCartSummary(RequestConfiguration $configuration)
    {
        $format = $configuration->getRequest()->getRequestFormat('html');
        if (null === $configuration->getParameters()->get('redirect')) {
            return $this->getRedirectHandler()->redirectToRoute($configuration, $this->getCartSummaryRoute(), [
                '_format' => $format
            ]);
        }

        return $this->getRedirectHandler()->redirectToRoute($configuration, $this->getCartSummaryRoute(), [
            '_format' => $format
        ]);
    }

    /**
     * @return RedirectHandlerInterface
     */
    protected function getRedirectHandler()
    {
        if(!property_exists($this, 'redirectHandler')) {
            throw new \Exception('CartSummaryTrait should be used inside a Resource Controller');
        }
        return $this->redirectHandler;
    }

}