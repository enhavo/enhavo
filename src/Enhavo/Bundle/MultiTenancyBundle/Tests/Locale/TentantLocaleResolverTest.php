<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Tests\Locale;

use Enhavo\Bundle\MultiTenancyBundle\Locale\TenantLocaleResolver;
use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TentantLocaleResolverTest extends TestCase
{
    public function testResolve()
    {
        /** @var ResolverInterface|MockObject $resolver */
        $resolver = $this->getMockBuilder(ResolverInterface::class)->getMock();
        $tenant = new Tenant();
        $resolver->expects($this->exactly(2))->method('getTenant')->willReturn($tenant);
        $localeResolver = new TenantLocaleResolver($resolver);

        $this->assertNull($localeResolver->resolve());

        $tenant->setLocale('ru');
        $result = $localeResolver->resolve();
        $this->assertEquals('ru', $result);
    }
}
