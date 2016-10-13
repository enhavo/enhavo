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

class ViewHandler extends SyliusViewHandler
{
    public function handle(RequestConfiguration $requestConfiguration, View $view)
    {
        if($view->getResponse()->getContent()) {
            return $view->getResponse();
        }

        return parent::handle($requestConfiguration, $view);
    }
}