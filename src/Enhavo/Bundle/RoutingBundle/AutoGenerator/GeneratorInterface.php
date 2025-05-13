<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface GeneratorInterface extends TypeInterface
{
    /**
     * @param object $resource
     * @param array  $options
     *
     * @return void
     */
    public function generate($resource, $options = []);

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
