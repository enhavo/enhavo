<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 13:31
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Metadata;


class Reference
{
    /**
     * @var string
     */
    private $targetClass;

    /**
     * @var string
     */
    private $targetProperty;

    /**
     * @var string
     */
    private $classProperty;

    /**
     * @var string
     */
    private $idProperty;

    /**
     * @var string
     */
    private $targetClassResolver;
}
