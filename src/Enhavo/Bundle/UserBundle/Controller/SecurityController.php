<?php
/**
 * SecurityController.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\SimpleRequestConfigurationFactoryInterface;
use Enhavo\Bundle\AppBundle\Controller\ViewHandler;
use Enhavo\Bundle\AppBundle\Viewer\ViewerFactory;
use FOS\RestBundle\View\ViewHandler as FOSViewHandler;
use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityController extends FOSSecurityController
{
    /**
     * @var SimpleRequestConfigurationFactoryInterface
     */
    protected $requestConfigurationFactory;

    /**
     * @var ViewerFactory
     */
    protected $viewerFactory;

    /**
     * @var ViewHandler
     */
    protected $viewHandler;

    public function __construct(
        SimpleRequestConfigurationFactoryInterface $requestConfigurationFactory,
        ViewerFactory $viewerFactory,
        FOSViewHandler $viewHandler,
        CsrfTokenManagerInterface $tokenManager
    ) {
        parent::__construct($tokenManager);
        $this->viewerFactory = $viewerFactory;
        $this->viewHandler = $viewHandler;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
    }

    public function renderLogin(array $data)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $configuration = $this->requestConfigurationFactory->createSimple($request);
        return $this->render(
            $configuration->getTemplate('EnhavoUserBundle:Theme:Security/login.html.twig'),
            $data
        );
    }
}