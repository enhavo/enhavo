<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Duplicate('property', ['groups' => ['duplicate', 'revision', 'restore']])]
    public array $directions = [];

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Duplicate('property', ['groups' => ['duplicate', 'revision', 'restore']])]
    public ?string $leftText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Duplicate('property', ['groups' => ['duplicate', 'revision', 'restore']])]
    public ?string $rightText = null;
}
