<?php


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
