<?php

namespace Enhavo\Bundle\RevisionBundle\Model;

use Doctrine\Common\Collections\Collection;

trait RevisionTrait
{
    private ?\DateTime $revisionDate = null;
    private ?string $revisionState = null;
    private array $revisionParameters = [];
    private ?RevisionInterface $revisionSubject = null;
    private Collection $revisions;

    public function getRevisionDate(): ?\DateTime
    {
        return $this->revisionDate;
    }

    public function setRevisionDate(?\DateTime $revisionDate): void
    {
        $this->revisionDate = $revisionDate;
    }

    public function getRevisionState(): ?string
    {
        return $this->revisionState;
    }

    public function setRevisionState(?string $revisionState): void
    {
        $this->revisionState = $revisionState;
    }

    public function getRevisionSubject(): ?RevisionInterface
    {
        return $this->revisionSubject;
    }

    public function setRevisionSubject(?RevisionInterface $revisionSubject): void
    {
        $this->revisionSubject = $revisionSubject;
    }

    public function getRevisionParameters(): array
    {
        return $this->revisionParameters;
    }

    public function setRevisionParameters(array $revisionParameters = []): void
    {
        $this->revisionParameters = $revisionParameters;
    }

    public function getRevisions(): Collection
    {
        return $this->revisions;
    }
}
