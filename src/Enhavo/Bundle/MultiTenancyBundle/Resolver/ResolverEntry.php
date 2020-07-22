<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;


class ResolverEntry
{
    /** @var integer */
    private $priority;

    /** @var ResolverInterface */
    private $resolver;

    /**
     * ResolverEntry constructor.
     * @param int $priority
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver, int $priority = 100)
    {
        $this->priority = $priority;
        $this->resolver = $resolver;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return ResolverInterface
     */
    public function getResolver(): ResolverInterface
    {
        return $this->resolver;
    }
}
