<?php

/**
 * ContentRepository.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ContentBundle\Entity\Content;

class ContentRepository extends EntityRepository
{
    use PublishRepositoryTrait;
}