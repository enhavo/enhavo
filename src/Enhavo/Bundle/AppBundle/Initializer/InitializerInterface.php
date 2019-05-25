<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:08
 */

namespace Enhavo\Bundle\AppBundle\Initializer;

use Symfony\Bundle\MakerBundle\ConsoleStyle;

interface InitializerInterface
{
    public function init();

    public function getName();

    public function buildOutput(ConsoleStyle $io);
}
