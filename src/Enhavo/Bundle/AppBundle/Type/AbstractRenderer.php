<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Type;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

abstract class AbstractRenderer extends AbstractExtension
{
    /**
     * @var CollectorInterface
     */
    protected $collector;

    /**
     * AbstractRenderer constructor.
     */
    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction($this->getName(), [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Return the Type
     *
     * @return TypeInterface
     */
    protected function getType($type)
    {
        return $this->collector->getType($type);
    }
}
