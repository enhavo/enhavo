<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 16:02
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class NodeOne implements NodeInterface
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id;

    #[ORM\Column(length: 255, nullable: true)]
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
}
