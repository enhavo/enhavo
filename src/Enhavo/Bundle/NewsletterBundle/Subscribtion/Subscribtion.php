<?php
/*
 * Subscribtion.php
 *
 * @since 04.09.20, 12:44
 * @author blutze
 */

namespace Enhavo\Bundle\NewsletterBundle\Subscribtion;


use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;

class Subscribtion
{
    /** @var string */
    private $name;

    /** @var Strategy */
    private $strategy;

    /**
     * Subscribtion constructor.
     * @param string $name
     * @param Strategy $strategy
     */
    public function __construct(string $name, Strategy $strategy)
    {
        $this->name = $name;
        $this->strategy = $strategy;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return Strategy
     */
    public function getStrategy(): Strategy
    {
        return $this->strategy;
    }

}
