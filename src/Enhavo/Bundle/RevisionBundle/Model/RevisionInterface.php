<?php

namespace Enhavo\Bundle\RevisionBundle\Model;

use Doctrine\Common\Collections\Collection;

interface RevisionInterface
{
    const STATE_MAIN = 'main';
    const STATE_REVISION = 'revision';
    const STATE_PUBLISHED = 'published';
    const STATE_DELETED = 'deleted';
    const STATE_ARCHIVE = 'archive';

    public function setRevisionDate(?\DateTime $date);
    public function getRevisionDate(): ?\DateTime;
    public function setRevisionState(?string $state);
    public function getRevisionState(): ?string;
    public function setRevisionSubject(?RevisionInterface $revision);
    public function getRevisionSubject(): ?RevisionInterface;
    public function setRevisionParameters(array $parameters = []);
    public function getRevisionParameters(): array;
    /** @return Collection<RevisionInterface> */
    public function getRevisions(): Collection;
    public function getRevisionTitle(): ?string;
}
