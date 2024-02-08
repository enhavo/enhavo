<?php

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

use Doctrine\ORM\EntityRepository;

class EntitySubjectLoader implements SubjectLoaderInterface
{
    private $subject = null;
    private $loaded = false;

    public function __construct(
        private EntityRepository $repository,
        private int $id
    )
    {
    }

    public function getSubject(): mixed
    {
        if ($this->loaded) {
            return $this->subject;
        }

        $this->subject = $this->repository->find($this->id);
        $this->loaded = true;
        return $this->subject;
    }
}
