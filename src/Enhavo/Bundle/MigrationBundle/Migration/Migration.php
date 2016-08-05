<?php

/**
 * Migration.php
 *
 * @since 25/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MigrationBundle\Migration;

interface Migration
{
    public function migrate();
}