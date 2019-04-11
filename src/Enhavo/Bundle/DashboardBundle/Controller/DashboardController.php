<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-04-12
 * Time: 00:04
 */

namespace Enhavo\Bundle\DashboardBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AbstractViewController;

class DashboardController extends AbstractViewController
{
    public function indexAction()
    {
        $view = $this->viewFactory->create('dashboard', []);
        return $this->viewHandler->handle($view);
    }
}
