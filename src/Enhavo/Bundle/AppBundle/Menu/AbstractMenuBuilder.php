<?php
/**
 * AbstractMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

abstract class AbstractMenuBuilder extends AbstractType implements MenuBuilderInterface
{
    /**
     * @var string
     */
    protected $role;

    /**
     * {@inheritdoc}
     */
    public function createMenu(array $options)
    {
        if(array_key_exists('role', $options)) {
            $this->role = $options['role'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted()
    {
        if(is_string($this->role)) {
            return $this->getAuthorizedChecker()->isGranted($this->role);
        }
        return true;
    }

    /**
     * @return MenuFactory
     */
    protected function getFactory()
    {
        return $this->container->get('knp_menu.factory');
    }

    /**
     * @return AuthorizationChecker
     */
    protected function getAuthorizedChecker()
    {
        return $this->container->get('security.authorization_checker');
    }

    /**
     * @return RouterInterface
     */
    protected function getRouter()
    {
        return $this->container->get('router');
    }
}