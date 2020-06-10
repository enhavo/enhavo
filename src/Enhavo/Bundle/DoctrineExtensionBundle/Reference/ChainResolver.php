<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 05.08.18
 * Time: 17:17
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Reference;


class ChainResolver
{
    /**
     * @var TargetClassResolverInterface
     */
    private $resolver;

    /**
     * @var integer
     */
    private $priority;

    /**
     * @return TargetClassResolverInterface
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param TargetClassResolverInterface $resolver
     */
    public function setResolver(TargetClassResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
}
