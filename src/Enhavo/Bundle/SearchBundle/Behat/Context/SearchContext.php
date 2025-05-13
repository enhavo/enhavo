<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Behat\Context;

use Enhavo\Bundle\AppBundle\Behat\Context\KernelContext;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class SearchContext extends KernelContext
{
    /**
     * @var MetadataRepository
     */
    public static $repository;

    public static $resource;

    public static $result;

    /**
     * @Given search metadata repository
     */
    public function searchMetadataRepository()
    {
        self::$repository = $this->get('enhavo_search.metadata.repository');
    }

    /**
     * @Given search resource
     */
    public function searchResource()
    {
        self::$resource = new Page();
    }

    /**
     * @Given get metadata result from repository for search resource
     */
    public function getMetadataResultFromRepositoryForSearchResource()
    {
        self::$result = self::$repository->getMetadata(self::$resource);
    }

    /**
     * @Then the metadata result should be ok
     */
    public function theMetadataResultShouldBeOk()
    {
        Assert::assertInstanceOf(Metadata::class, self::$result);
    }
}
