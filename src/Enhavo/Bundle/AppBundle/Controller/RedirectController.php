<?php
/**
 * RedirectController.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectController extends Controller
{
    public function indexAction()
    {
        $redirectRoute = $this->container->getParameter('enhavo_app.login_redirect');
        $url = $this->get('router')->generate($redirectRoute);
        return new RedirectResponse($url);
    }
}