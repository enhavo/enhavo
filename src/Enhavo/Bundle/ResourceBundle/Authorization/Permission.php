<?php

namespace Enhavo\Bundle\ResourceBundle\Authorization;

class Permission
{
    public const SHOW = 'show';
    public const INDEX = 'index';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';

    public function __construct(
        public string $name,
        public string $action,
    )
    {
    }
}
