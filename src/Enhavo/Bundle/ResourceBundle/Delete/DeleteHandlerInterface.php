<?php

namespace Enhavo\Bundle\ResourceBundle\Delete;

interface DeleteHandlerInterface
{
    public function delete(object $resource): void;
}
