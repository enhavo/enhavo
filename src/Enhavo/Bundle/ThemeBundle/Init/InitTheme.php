<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 11:50
 */

namespace Enhavo\Bundle\ThemeBundle\Init;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Init\InitInterface;
use Enhavo\Bundle\AppBundle\Init\Output;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class InitTheme implements InitInterface
{
    use ContainerAwareTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * InitTaxonomy constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function init(Output $io)
    {

    }

    public function getType()
    {
        return 'theme';
    }
}
