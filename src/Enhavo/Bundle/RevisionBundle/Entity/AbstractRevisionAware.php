<?php

namespace Enhavo\Bundle\RevisionBundle\Entity;

use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;

abstract class AbstractRevisionAware
{
    private ?int $id = null;
    private ?RevisionInterface $subject = null;
    private ?int $subjectId = null;
    private ?string $subjectClass = null;
    private ?string $title = null;
    private ?\DateTime $date = null;
    private ?string $resourceAlias = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?RevisionInterface
    {
        return $this->subject;
    }

    public function setSubject(?RevisionInterface $subject): void
    {
        $this->subject = $subject;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function setSubjectId(?int $subjectId): void
    {
        $this->subjectId = $subjectId;
    }

    public function getSubjectClass(): ?string
    {
        return $this->subjectClass;
    }

    public function setSubjectClass(?string $subjectClass): void
    {
        $this->subjectClass = $subjectClass;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getResourceAlias(): ?string
    {
        return $this->resourceAlias;
    }

    public function setResourceAlias(?string $resourceAlias): void
    {
        $this->resourceAlias = $resourceAlias;
    }
}
