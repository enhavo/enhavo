<?php
/**
 * SecurityController.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppControllerTrait;
use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;

class SecurityController extends FOSSecurityController
{
    use AppControllerTrait;

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