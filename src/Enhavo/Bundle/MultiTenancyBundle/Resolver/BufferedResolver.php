<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;

class BufferedResolver implements ResolverInterface
{
    /** @var ResolverInterface */
    private $decoratedResolver;

    /** @var Tenant|null */
    private $bufferedResult;

    /** @var bool */
    private $resolved = false;

    /**
     * BufferedResolver constructor.
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
