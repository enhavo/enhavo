<?php
/**
 * RouteLoader.php
 *
 * @since 04/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Admin;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader implements LoaderInterface
{
    private $loaded = false;

    /**
     * @var AdminRegister
     */
    private $register;

    public function __construct(AdminRegister $register)
    {
        $this->register = $register;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $collection = new RouteCollection();

        /** @var $admin Admin */
        foreach($this->register->getAdmins() as $admin) {
            $collection->addCollection($admin->getRouteCollection());
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }

    public function getResolver()
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }

    public function setResolver(LoaderResolverInterface $resolver)
    {
        // same as above
    }
} 