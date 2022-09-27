<?php

namespace Enhavo\Bundle\UserBundle\Security\Authentication;

interface ErrorMessageInterface
{
    public function supports($error): bool;

    public function getMessage(): string;
}
