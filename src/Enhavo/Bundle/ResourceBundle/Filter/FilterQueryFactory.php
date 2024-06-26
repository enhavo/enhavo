<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.10.18
 * Time: 15:14
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;


use Doctrine\ORM\EntityManagerInterface;

class FilterQueryFactory
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * FilterQueryFactory constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $class
     * @return FilterQuery
     */
    public function create($class)
    {
        return new FilterQuery($this->em, $class);
    }
}
