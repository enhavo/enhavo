<?php

namespace Enhavo\Behat\Context;

use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class SearchContext extends KernelContext
{
    /**
     * @var MetadataFactory
     */
    public static $factory;

    public static $resource;

    public static $result;

    /**
     * @Given search metadata factory
     */
    public function searchMetadataFactory()
    {
        self::$factory = $this->get('enhavo_search.metadata.metadata_factory');
    }

    /**
     * @Given search resource
     */
    public function searchResource()
    {
        self::$resource = new Page();
    }

    /**
     * @Given get metadata result from factory for search resource
     */
    public function getMetadataResultFromFactoryForSearchResource()
    {
        self::$result = self::$factory->create(self::$resource);
    }

    /**
     * @Then the metadata result should be ok
     */
    public function theMetadataResultShouldBeOk()
    {
        Assert::assertInstanceOf(Metadata::class, self::$result);
    }
}