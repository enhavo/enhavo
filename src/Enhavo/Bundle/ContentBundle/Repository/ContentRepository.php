<?php

/**
 * ContentRepository.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class ContentRepository extends EntityRepository
{
    use PublishRepositoryTrait;
}
