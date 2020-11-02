<?php

namespace Enhavo\Bundle\AppBundle\Locale;

class ChainResolverEntry
{
    /** @var integer */
    private $priority;

    /** @var LocaleResolverInterface */
    private $resolver;

    /**
     * ResolverEntry constructor.
     * @param int $priority
     * @param LocaleResolverInterface $resolver
     */
    public function __construct(LocaleResolverInterface $resolver, int $priority = 100)
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
     * @return LocaleResolverInterface
     */
    public function getResolver(): LocaleResolverInterface
    {
        return $this->resolver;
    }
}
