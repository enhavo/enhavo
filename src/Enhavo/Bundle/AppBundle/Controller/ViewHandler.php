<?php
/**
 * ViewHandler.php
 *
 * @since 13/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ViewHandler as SyliusViewHandler;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewHandler extends SyliusViewHandler
{
    public function handle(RequestConfiguration $requestConfiguration, View $view)
    {
        $response = $view->getResponse();
        if($response->getContent() || $response instanceof StreamedResponse) {
            return $view->getResponse();
        }

        return parent::handle($requestConfiguration, $view);
    }
}