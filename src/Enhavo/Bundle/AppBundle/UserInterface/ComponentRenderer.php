<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 05.06.18
 * Time: 00:28
 */

namespace Enhavo\Bundle\AppBundle\UserInterface;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ComponentRenderer
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $configuration;

    public function __construct(array $configuration)
    {

    }

    public function render(ComponentInterface $component)
    {

    }
}