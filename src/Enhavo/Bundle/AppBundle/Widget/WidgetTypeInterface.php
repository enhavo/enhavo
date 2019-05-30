<?php

namespace Enhavo\Bundle\AppBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface WidgetTypeInterface extends TypeInterface
{
    public function createViewData(array $options, $resource = null);

    public function configureOptions(OptionsResolver $optionsResolver);

    public function getTemplate($options);
}
