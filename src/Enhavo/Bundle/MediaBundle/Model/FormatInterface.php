<?php

/**
 * FileInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Model;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface FormatInterface extends FileContentInterface
{
    public function getId(): ?int;

    public function setMimeType(string $mimeType);

    public function setExtension(?string $extension): void;

    public function getName(): string;

    public function setName(string $filename);

    public function getParameters(): array;

    public function setParameters(array $parameters): void;

    public function setContent(ContentInterface $content);

    public function setFile(FileInterface $file);

    public function getFile(): FileInterface;

    public function getLockAt(): ?\DateTime;

    public function setLockAt(?\DateTime $lockAt);
}
