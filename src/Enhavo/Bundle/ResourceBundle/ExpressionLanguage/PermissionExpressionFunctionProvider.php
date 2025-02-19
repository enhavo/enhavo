<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\Exception\MetadataException;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class PermissionExpressionFunctionProvider implements ResourceExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly ResourceManager $resourceManager
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'permission',
                function () {
                    return 'new Permission()';
                },
                function ($args, string|object $resource, string $action) {
                    if (is_string($resource)) {
                        return new Permission($resource, $action);
                    }

                    $name = $this->resourceManager->getMetadata($resource)?->getName();
                    if ($name === null) {
                        throw MetadataException::notExists($resource);
                    }
                    return new Permission($name, $action);
                }
            )
        ];
    }
}
