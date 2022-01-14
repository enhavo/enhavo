<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08.12.18
 * Time: 13:01
 */

namespace Enhavo\Bundle\AppBundle\View;


use Sylius\Component\Resource\Metadata\MetadataInterface;

class DummyMetadata implements MetadataInterface
{
    public function getAlias(): string
    {
        return '';
    }

    public function getApplicationName(): string
    {
        return '';
    }

    public function getName(): string
    {
        return '';
    }

    public function getHumanizedName(): string
    {
        return '';
    }

    public function getPluralName(): string
    {
        return '';
    }

    public function getDriver(): string
    {
        return '';
    }

    public function getTemplatesNamespace(): ?string
    {
        return '';
    }

    public function getParameter(string $name)
    {
        return '';
    }

    public function getParameters(): array
    {
        return [];
    }

    public function hasParameter(string $name): bool
    {
        return '';
    }

    public function getClass(string $name): string
    {
        return '';
    }

    public function hasClass(string $name): bool
    {
        return '';
    }

    public function getServiceId(string $serviceName): string
    {
        return '';
    }

    public function getPermissionCode(string $permissionName): string
    {
        return '';
    }
}
