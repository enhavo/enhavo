<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;

class BufferedResolver implements ResolverInterface
{
    /** @var ResolverInterface */
    private $decoratedResolver;

    /** @var Tenant|null */
    private $bufferedResult = null;

    /** @var bool */
    private $resolved = false;

    /**
     * BufferedResolver constructor.
     * @param ResolverInterface $decoratedResolver
     */
    public function __construct(ResolverInterface $decoratedResolver)
    {
        $this->decoratedResolver = $decoratedResolver;
    }

    public function getTenant(): ?TenantInterface
    {
        if (!$this->resolved) {
            $this->bufferedResult = $this->decoratedResolver->getTenant();
            $this->resolved = true;
        }

        return $this->bufferedResult;
    }
}
