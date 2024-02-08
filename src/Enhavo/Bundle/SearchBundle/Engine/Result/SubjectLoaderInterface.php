<?php

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

interface SubjectLoaderInterface
{
    public function getSubject(): mixed;
}