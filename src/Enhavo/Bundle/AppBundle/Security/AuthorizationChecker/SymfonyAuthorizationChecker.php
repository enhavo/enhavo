<?php
/**
 * SymfonyAuthorizationChecker.php
 *
 * @since 18/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Security\AuthorizationChecker;


use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface as SymfonyAuthorizationCheckerInterface;

class SymfonyAuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @var SymfonyAuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(SymfonyAuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function isGranted(RequestConfiguration $configuration, $permission)
    {
        if(is_string($permission)) {
            return $this->authorizationChecker->isGranted($permission);
        }
        return true;
    }
}