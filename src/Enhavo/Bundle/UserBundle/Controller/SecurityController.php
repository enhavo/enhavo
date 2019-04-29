<?php
/**
 * SecurityController.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use FOS\RestBundle\View\ViewHandler;
use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;
use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityController extends FOSSecurityController
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var ViewHandler
     */
    private $viewHandler;

    public function __construct(
        ViewFactory $viewFactory,
        ViewHandler $viewHandler,
        CsrfTokenManagerInterface $tokenManager
    ) {
        parent::__construct($tokenManager);
        $this->viewFactory = $viewFactory;
        $this->viewHandler = $viewHandler;
    }

    public function renderLogin(array $data)
    {
        if(isset($data['error']) && $data['error']) {
            $this->addFlash('error', $this->container->get('translator')->trans('login.error.credentials', [], 'EnhavoUserBundle'));
        }

        $view = $this->viewFactory->create('login', [
            'parameters' => $data,
        ]);

        return $this->viewHandler->handle($view);
    }
}
