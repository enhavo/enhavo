<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

/**
 * CiteText
 */
class CiteText implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $cite;

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
     * @return mixed
     */
    public function getCite()
    {
        return $this->cite;
    }

    /**
     * @param mixed $cite
     */
    public function setCite($cite)
    {
        $this->cite = $cite;
    }
}
