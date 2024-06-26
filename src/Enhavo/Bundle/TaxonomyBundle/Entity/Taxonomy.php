<?php
namespace Enhavo\Bundle\TaxonomyBundle\Entity;

use Enhavo\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class Taxonomy implements ResourceInterface, TaxonomyInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $terms;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->terms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
