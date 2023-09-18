<?php

namespace Enhavo\Bundle\ApiBundle\Tests\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Method;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Property;
use PHPUnit\Framework\TestCase;

class DocumentationTest extends TestCase
{
    public function testParameters()
    {
        $documentation = new Documentation();

        $documentation
            ->version('3.1.2')
            ->path('/test/url')->end();

        $output = $documentation->getOutput();

        $this->assertEquals('3.1.2', $output['openapi']);
        $this->assertArrayHasKey('/test/url', $output['paths']);
    }

    public function testContent()
    {
        $documentation = new Documentation();

        $documentation
            ->path('/test/url')
                ->method(Method::POST)
                    ->response(200)
                        ->description('success')
                        ->content()
                            ->schema()
                                ->object()
                                    ->property('test', Property::STRING)->end()
                                    ->property('children', Property::OBJECT)
                                        ->property('size', Property::INTGEGER)->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->example([
                                'test' => 'hello'
                            ])
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        $output = $documentation->getOutput();

        $this->assertEquals([
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'test' => [
                        'type' => 'string'
                    ],
                    'children' => [
                        'type' => 'object',
                        'properties' => [
                            'size' => [
                                'type' => 'integer'
                            ]
                        ]
                    ]
                ]
            ],
            'examples' => [
                'test' => 'hello'
            ]
        ], $output['paths']['/test/url']['post']['responses'][200]['application/json']);
    }

    public function testInfo()
    {
        $documentation = new Documentation();

        $documentation = $documentation
            ->info()
                ->title('This is a title')
                ->description('This is a description')
                ->version('This is a version')
            ->end();

        $output = $documentation->getOutput();

        $this->assertEquals('This is a title', $output['info']['title']);
        $this->assertEquals('This is a description', $output['info']['description']);
        $this->assertEquals('This is a version', $output['info']['version']);
    }
}
