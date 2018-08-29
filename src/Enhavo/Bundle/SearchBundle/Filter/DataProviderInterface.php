<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.08.18
 * Time: 16:41
 */

namespace Enhavo\Bundle\SearchBundle\Filter;


use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface DataProviderInterface extends TypeInterface
{
    /**
     * @param object $resource
     * @param array $options
     * @return Data
     */
    function getData($resource, $options);

    /**
     * @param OptionsResolver $optionsResolver
     * @return void
     */
    function configureOptions(OptionsResolver $optionsResolver);
}