<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AiBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\AiBundle\Model\MessageInterface;
use Enhavo\Bundle\AiBundle\Model\SessionInterface;
use Enhavo\Bundle\AiBundle\Repository\MessageRepository;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'ai_message')]
class Message implements MessageInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SessionInterface::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public ?SessionInterface $session = null;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    public ?string $role = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTime $createdAt = null;
}
