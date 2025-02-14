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
class EntityContainer
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Entity::class, cascade: ['all'], fetch: 'LAZY')]
    public ?Entity $entity = null;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $name = null;
}
