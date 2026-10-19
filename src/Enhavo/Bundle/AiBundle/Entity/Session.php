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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\AiBundle\Model\MessageInterface;
use Enhavo\Bundle\AiBundle\Model\SessionInterface;
use Enhavo\Bundle\AiBundle\Repository\SessionRepository;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ORM\Table(name: 'ai_session')]
class Session implements SessionInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    public ?\DateTime $updatedAt = null;

    /** @var Collection<int, MessageInterface> */
    #[ORM\OneToMany(targetEntity: MessageInterface::class, mappedBy: 'session', cascade: ['persist', 'remove', 'refresh'], orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    public Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function addMessage(MessageInterface $message): void
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->session = $this;
        }
    }

    public function removeMessage(MessageInterface $message): void
    {
        if ($this->messages->removeElement($message)) {
            $message->session = null;
        }
    }
}
