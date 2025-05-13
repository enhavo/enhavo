<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Model;

interface TenantInterface
{
    public function getKey(): string;

    public function getName(): string;

    public function getRole(): string;

    public function getBaseUrl(): string;

    public function getDomains(): array;

    public function getLocale(): ?string;
}
