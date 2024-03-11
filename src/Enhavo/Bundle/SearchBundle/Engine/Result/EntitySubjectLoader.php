<?php

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;

class EntitySubjectLoader implements SubjectLoaderInterface
{
    private $subject = null;
    private $loaded = false;

    public function __construct(
        private EntityResolverInterface $entityResolver,
        private string $name,
        private int $id
    )
    {
    }

    public function getSubject(): mixed
    {
        if ($this->loaded) {
            return $this->subject;
        }

        $this->subject = $this->entityResolver->getEntity($this->id, $this->name);
        $this->loaded = true;
        return $this->subject;
    }
}
