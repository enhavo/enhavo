<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.12.18
 * Time: 18:56
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LoginController extends AbstractController
{
    /**
     * @var string
     */
    private $loginRoute;

    /**
     * @var array
     */
    private $loginRouteParameters;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * IndexController constructor.
     *
     * @param RouterInterface $router
     * @param string $loginRoute
     * @param array $loginRouteParameters
     */
    public function __construct(RouterInterface $router, string $loginRoute, array $loginRouteParameters = [])
    {
        $this->loginRoute = $loginRoute;
        $this->loginRouteParameters = $loginRouteParameters;
        $this->router = $router;
    }

    public function indexAction()
    {
        $url = $this->router->generate($this->loginRoute, $this->loginRouteParameters);
        return new RedirectResponse($url);
    }
}
