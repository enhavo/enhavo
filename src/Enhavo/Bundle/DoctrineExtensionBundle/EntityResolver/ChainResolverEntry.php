<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 05.08.18
 * Time: 17:17
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver;


class ChainResolverEntry
{
    /**
     * @var EntityResolverInterface
     */
    private $resolver;

    /**
     * @var integer
     */
    private $priority;

    /**
     * ChainResolverEntry constructor.
     * @param EntityResolverInterface $resolver
     * @param int $priority
     */
    public function __construct(EntityResolverInterface $resolver, int $priority)
    {
        $this->resolver = $resolver;
        $this->priority = $priority;
    }

    /**
     * @return EntityResolverInterface
     */
    public function getResolver(): EntityResolverInterface
    {
        return $this->resolver;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
