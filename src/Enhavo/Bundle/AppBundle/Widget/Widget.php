<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Widget
{
    /**
     * @var WidgetTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    public function __construct(WidgetTypeInterface $type, $options)
    {
        $this->type = $type;
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function createViewData($resource = null)
    {
        return $this->type->createViewData($this->options, $resource);
    }

    public function getTemplate()
    {
        return $this->type->getTemplate($this->options);
    }
}
