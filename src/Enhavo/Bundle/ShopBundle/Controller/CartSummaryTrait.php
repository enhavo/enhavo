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
use Symfony\Component\HttpFoundation\Response;

trait CartSummaryTrait
{
    protected function getCartSummaryRoute(): string
    {
        return 'enhavo_shop_theme_cart_summary';
    }

    protected function redirectToCartSummary(RequestConfiguration $configuration): Response
    {
        $format = $configuration->getRequest()->getRequestFormat('html');
        $defaultParams = [
            '_format' => $format
        ];

        if (null !== $configuration->getParameters()->get('redirect')) {
            $redirect = $configuration->getParameters()->get('redirect');

            $params = array();
            if(is_array($redirect)) {
                $route = $redirect['route'];
                $params = isset($redirect['parameters']) ? $redirect['parameters'] : array();
            } else {
                $route = $redirect;
            }

            return $this->getRedirectHandler()->redirectToRoute($configuration, $route, $params);
        }

        return $this->getRedirectHandler()->redirectToRoute($configuration, $this->getCartSummaryRoute(), $defaultParams);
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
