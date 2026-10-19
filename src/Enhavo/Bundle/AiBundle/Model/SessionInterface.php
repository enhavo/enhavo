<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AiBundle\Model;

use Doctrine\Common\Collections\Collection;

interface SessionInterface
{
    public ?int $id { get; }
    public ?string $title { get; set; }
    public ?\DateTime $createdAt { get; set; }
    public ?\DateTime $updatedAt { get; set; }

    /** @var Collection<int, MessageInterface> */
    public Collection $messages { get; }

    public function addMessage(MessageInterface $message): void;

    public function removeMessage(MessageInterface $message): void;
}
