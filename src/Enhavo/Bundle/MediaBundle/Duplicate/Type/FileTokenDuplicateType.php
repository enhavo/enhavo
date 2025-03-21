<?php

namespace Enhavo\Bundle\MediaBundle\Duplicate\Type;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\AbstractDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileTokenDuplicateType extends AbstractDuplicateType
{
    public function __construct(
        private readonly TokenGeneratorInterface $tokenGenerator,
    )
    {
    }

    public function duplicate($options, SourceValue $sourceValue, TargetValue $targetValue, $context): void
    {
        $targetValue->setValue($this->tokenGenerator->generateToken());
    }
}
