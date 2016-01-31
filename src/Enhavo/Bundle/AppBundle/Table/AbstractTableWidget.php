<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 13:48
 */

namespace Enhavo\Bundle\AppBundle\Table;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractTableWidget implements TableWidgetInterface {

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Return the value the given property and object.
     *
     * @param $resource
     * @param $property
     * @return mixed
     * @throws PropertyNotExistsException
     */
    public function getProperty($resource, $property)
    {
        $method = sprintf('get%s', ucfirst($property));
        if(method_exists($resource, $method)) {
            return call_user_func(array($resource, $method));
        }
        throw new PropertyNotExistsException(sprintf(
            'Trying to call "%s" on class "%s", but method does not exists. Maybe you spell it wrong you did\'t add the getter for property "%s"',
            $method,
            get_class($resource),
            $property
        ));
    }
}