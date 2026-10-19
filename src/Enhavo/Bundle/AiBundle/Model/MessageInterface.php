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

interface MessageInterface
{
    public const ROLE_SYSTEM = 'system';
    public const ROLE_USER = 'user';
    public const ROLE_ASSISTANT = 'assistant';

    public ?int $id { get; }
    public ?SessionInterface $session { get; set; }
    public ?string $role { get; set; }
    public ?string $content { get; set; }
    public ?\DateTime $createdAt { get; set; }
}
