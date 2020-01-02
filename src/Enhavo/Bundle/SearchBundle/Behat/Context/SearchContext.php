<?php

namespace Enhavo\Behat\Context;

use Enhavo\Bundle\AppBundle\Metadata\MetadataRepository;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
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