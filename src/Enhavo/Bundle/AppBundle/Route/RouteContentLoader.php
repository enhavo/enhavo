<?php
/**
 * RouteContent.php
 *
 * @since 18/05/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\Route;

use Doctrine\Common\Persistence\ObjectRepository;

class RouteContentLoader
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $repository;

    /**
     * @var string
     */
    private $className;

    public function __construct($type, $className, $repository)
    {
        $this->type = $type;
        $this->className = $className;
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $repository
     */
    public function setRepository( $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }
}