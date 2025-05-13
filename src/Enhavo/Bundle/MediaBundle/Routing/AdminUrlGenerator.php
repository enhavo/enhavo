<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Routing;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;
use Symfony\Component\Routing\RouterInterface;

class AdminUrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function generate(FileInterface $file, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        return $this->router->generate('enhavo_media_admin_api_file', [
            'token' => $file->getToken(),
        ], $referenceType);
    }

    public function generateFormat(FileInterface $file, string $format, $referenceType = SymfonyUrlGenerator::ABSOLUTE_PATH): string
    {
        return $this->router->generate('enhavo_media_admin_api_file_format', [
            'token' => $file->getToken(),
            'format' => $format,
        ], $referenceType);
    }
}
