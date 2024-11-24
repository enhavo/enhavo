<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 13:09
 */

namespace Enhavo\Bundle\MediaBundle\Routing;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;

interface UrlGeneratorInterface
{
    public function generate(FileInterface $file, $referenceType = UrlGenerator::ABSOLUTE_PATH): string;

    public function generateFormat(FileInterface $file, string $format, $referenceType = UrlGenerator::ABSOLUTE_PATH): string;
}
