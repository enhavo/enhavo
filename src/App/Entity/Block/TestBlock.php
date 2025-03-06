<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-02
 * Time: 17:41
 */

namespace App\Entity\Block;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;
use Enhavo\Bundle\RevisionBundle\Model\RevisionTrait;

#[ORM\Entity]
#[ORM\Table(name: 'app_test_block')]
class TestBlock extends AbstractBlock
{
    use RevisionTrait;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Duplicate('property', ['groups' => ['duplicate', 'revision', 'restore']])]
    public ?string $direction = null;

    public array $directions = [];

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Duplicate('property', ['groups' => ['duplicate', 'revision', 'restore']])]
    public ?string $leftText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Duplicate('property', ['groups' => ['duplicate', 'revision', 'restore']])]
    public ?string $rightText = null;
}
