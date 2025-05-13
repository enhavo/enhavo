<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Model;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

interface RevisionInterface
{
    public const STATE_MAIN = 'main';
    public const STATE_REVISION = 'revision';
    public const STATE_PUBLISHED = 'published';
    public const STATE_DELETED = 'deleted';
    public const STATE_ARCHIVE = 'archive';

    public function setRevisionDate(?\DateTime $date);

    public function getRevisionDate(): ?\DateTime;

    public function setRevisionState(?string $state);

    public function getRevisionState(): ?string;

    public function setRevisionSubject(?RevisionInterface $revision);

    public function getRevisionSubject(): ?RevisionInterface;

    public function setRevisionUser(?UserInterface $revision);

    public function getRevisionUser(): ?UserInterface;

    public function setRevisionParameters(array $parameters = []);

    public function getRevisionParameters(): array;

    /** @return Collection<RevisionInterface> */
    public function getRevisions(): Collection;

    public function getRevisionTitle(): ?string;
}
