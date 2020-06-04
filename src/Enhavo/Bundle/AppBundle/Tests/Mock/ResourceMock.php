<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 12:05
 */

namespace Enhavo\Bundle\AppBundle\Tests\Mock;


use Sylius\Component\Resource\Model\ResourceInterface;

class ResourceMock implements ResourceInterface
{
    /** @var int */
    private $id;

    public function __construct($id = 1)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
