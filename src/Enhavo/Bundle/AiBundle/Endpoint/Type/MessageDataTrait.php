<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AiBundle\Endpoint\Type;

use Enhavo\Bundle\AiBundle\Model\MessageInterface;

trait MessageDataTrait
{
    private function getMessageData(MessageInterface $message): array
    {
        return [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $message->content,
            'createdAt' => $message->createdAt?->format(\DateTimeInterface::ATOM),
        ];
    }
}
