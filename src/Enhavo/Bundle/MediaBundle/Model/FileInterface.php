<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Model;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface FileInterface extends FileContentInterface
{
    public function getId();

    public function getToken(): ?string;

    public function setToken(string $token);

    public function setMimeType(string $mimeType);

    public function getMimeType(): string;

    public function setExtension(?string $extension);

    public function setOrder(?int $order);

    public function getOrder(): ?int;

    public function setFilename(string $filename);

    public function setBasename(string $basename);

    public function setParameter(string $key, mixed $value);

    public function getParameter(string $key): mixed;

    public function getParameters(): array;

    public function setParameters(array $parameters);

    public function isGarbage(): bool;

    public function setGarbage(bool $garbage, ?\DateTime $garbageTimestamp = null);

    public function getGarbageTimestamp(): ?\DateTime;

    public function setGarbageTimestamp(?\DateTime $garbageTimestamp);

    public function isImage(): bool;

    public function setContent(ContentInterface $content);

    public function getShortChecksum(): string;

    public function setChecksum(string $checksum);

    public function setGarbageCheckedAt(?\DateTime $garbageCheckedAt);
}
